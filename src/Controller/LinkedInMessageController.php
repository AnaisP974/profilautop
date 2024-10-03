<?php

namespace App\Controller;

use App\Entity\LinkedInMessage;
use App\Form\LinkedInMessageType;
use App\Repository\LinkedInMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/linked/in/message')]
final class LinkedInMessageController extends AbstractController
{
    #[Route(name: 'app_linked_in_message_index', methods: ['GET'])]
    public function index(LinkedInMessageRepository $linkedInMessageRepository): Response
    {
        return $this->render('linked_in_message/index.html.twig', [
            'linked_in_messages' => $linkedInMessageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_linked_in_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $linkedInMessage = new LinkedInMessage();
        $form = $this->createForm(LinkedInMessageType::class, $linkedInMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($linkedInMessage);
            $entityManager->flush();

            return $this->redirectToRoute('app_linked_in_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('linked_in_message/new.html.twig', [
            'linked_in_message' => $linkedInMessage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_linked_in_message_show', methods: ['GET'])]
    public function show(LinkedInMessage $linkedInMessage): Response
    {
        return $this->render('linked_in_message/show.html.twig', [
            'linked_in_message' => $linkedInMessage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_linked_in_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LinkedInMessage $linkedInMessage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LinkedInMessageType::class, $linkedInMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_linked_in_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('linked_in_message/edit.html.twig', [
            'linked_in_message' => $linkedInMessage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_linked_in_message_delete', methods: ['POST'])]
    public function delete(Request $request, LinkedInMessage $linkedInMessage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$linkedInMessage->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($linkedInMessage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_linked_in_message_index', [], Response::HTTP_SEE_OTHER);
    }
}
