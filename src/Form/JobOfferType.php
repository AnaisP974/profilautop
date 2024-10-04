<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\JobStatus;
use App\Entity\JobOffer;
use App\Entity\CoverLetter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class JobOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-label form-control',
                    'placeholder' => 'Développeur web Junior'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Le Poste',
                'attr' => [
                    'class' => 'form-label form-control',
                    'placeholder' => 'Description du poste',
                    'row' => '12'
                ],
                'required' => false,
            ])
            ->add('company', TextType::class, [
                'label' => 'Société',
                'attr' => [
                    'class' => 'form-label form-control',
                    'placeholder' => 'SAFRAN'
                ],
                'required' => false,
            ])
            ->add('link', UrlType::class, [
                'label' => 'Lien',
                'attr' => [
                    'class' => 'form-label form-control',
                    'placeholder' => 'https://fr.linkedin.com/',
                ],
                'required' => false,
            ])
            ->add('location', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'class' => 'form-label form-control',
                    'placeholder' => 'Paris(75)',
                ],
                'required' => false,
            ])
            ->add('salary', TextType::class, [
                'label' => 'Salaire',
                'attr' => [
                    'class' => 'form-label form-control',
                    'placeholder' => '35K / an',
                ],
                'required' => false,
            ])
            ->add('contactPerson', TextType::class, [
                'label' => 'Nom du contact',
                'attr' => [
                    'class' => 'form-label form-control',
                    'placeholder' => 'Marie Martinez',
                ],
                'required' => false,
            ])
            ->add('contactEmail', EmailType::class, [
                'label' => 'Email du contact',
                'attr' => [
                    'class' => 'form-label form-control',
                    'placeholder' => 'contact@gmail.com',
                ],
                'required' => false,
            ])
            ->add('coverLetter', EntityType::class, [
                'class' => CoverLetter::class,
                'choice_label' => 'id',
                'attr' => [
                    'class' => 'form-label form-select',
                ],
            ])
            ->add('status', EnumType::class, [
                'class' => JobStatus::class,
                'choice_label' => function (JobStatus $status) {
                    return $status->value; 
                },
                'attr' => [
                    'class' => 'form-label form-select',
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
