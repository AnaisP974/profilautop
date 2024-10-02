<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // sources : https://symfony.com/doc/current/reference/forms/types.html
        $builder
            ->add('email', EmailType::class, [
                "required" => true,
                "help" => "L'email doit être valide. Vous pouvez créer qu'un compte avec votre email.",
                "label" => "Votre Email",
                'empty_data' => 'my-email@gmail.com',
            ])
            ->add('password', PasswordType::class, [
                "required" => true,
                "help" => "",
                "label" => "Mot de passe",
            ])
            ->add('confirmPassword', PasswordType::class, [
                "required" => true,
                "help" => "",
                "label" => "Mot de passe",
            ])
            ->add('firstName', TextType::class, [
                "required" => true,
                "help" => "",
                "label" => "Prénom",
                'empty_data' => 'Nathalie',
            ])
            ->add('lastName', TextType::class, [
                "required" => true,
                "help" => "",
                "label" => "Nom",
                'empty_data' => 'DUPONT',
            ])
            ->add('image', FileType::class, [
                "required" => false,
                "help" => "Image de profil visible par vous uniquement. Format admit .png, .jpg, .jpeg, .gif",
                "label" => "Image de profil",
                "mapped" => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
