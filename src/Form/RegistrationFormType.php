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
                "label" => "Votre Email",
                'empty_data' => 'my-email@gmail.com',
            ])
            ->add('password', PasswordType::class, [
                "required" => true,
                "label" => "Mot de passe",
            ])
            ->add('confirmPassword', PasswordType::class, [
                "required" => true,
                "label" => "Confirmer le mot de passe",
            ])
            ->add('firstName', TextType::class, [
                "required" => true,
                "label" => "Prénom",
                'empty_data' => 'Nathalie',
            ])
            ->add('lastName', TextType::class, [
                "required" => true,
                "label" => "Nom",
                'empty_data' => 'DUPONT',
            ])
            ->add('image', FileType::class, [
                "required" => false,
                "label" => "Image de profil",
                "mapped" => false,
            ])
            ->add('submit', SubmitType::class, [
                "label" => "M'inscrire",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
