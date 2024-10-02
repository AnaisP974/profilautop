<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods:['GET', 'POST'])]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHashed, EntityManagerInterface $entityManager, UserRepository $ur): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $password = $form->get('password')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();
            $email = $form->get('email')->getData();

            $existingEmail = $ur->findByEmail($email);
            if($existingEmail)
            {
                $this->addFlash(
                    'error',
                    'Email déjà utilisé, merci de vous connecter.'
                );

                return $this->redirectToRoute('app_register', [
                    'registrationForm' => $form,
                ]);
            }

            if($password === $confirmPassword)
            {
                // encode the plain password
                $user->setPassword($userPasswordHashed->hashPassword($user, $password));
                $user->setConfirmPassword(null);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Inscription réussie ! Vous pouvez dés à présent vous connecter.'
                );
                return $this->redirectToRoute('app_login');

            } else {
                $this->addFlash(
                    'error',
                    'La confirmation du mot de passe doit être identique au mot de passe.'
                );
                return $this->redirectToRoute('app_register', [
                    'registrationForm' => $form,
                ]);
            }
           
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
