<?php

namespace App\Form;

use App\Entity\CoverLetter;
use App\Entity\JobOffer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('company')
            ->add('link')
            ->add('location')
            ->add('salary')
            ->add('contactPerson')
            ->add('contactEmail')
            ->add('applicationDate', null, [
                'widget' => 'single_text',
            ])
            ->add('status')
            ->add('app_user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('coverLetter', EntityType::class, [
                'class' => CoverLetter::class,
                'choice_label' => 'id',
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
