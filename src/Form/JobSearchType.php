<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\JobOffer;
use App\Entity\CoverLetter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class JobSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
            'label' => 'Poste recherché',
            'attr' => [
                'placeholder' => 'Entrez le poste recherché',
                'class' => 'form-label form-control',
            ]
        ])
            ->add('location', TextType::class, [
                'label' => 'Ville ou Code Postal',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez la ville ou le code postal',
                    'class' => 'form-label form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobOffer::class,
        ]);
    }
}
