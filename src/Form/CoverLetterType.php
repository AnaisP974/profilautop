<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\JobOffer;
use App\Entity\CoverLetter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoverLetterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jobOffer', EntityType::class, [
                'class' => JobOffer::class,
                'label'=> 'Société',
                'choice_label' => 'company',
                'attr' => [
                    'class' => 'form-label form-select',
                ],
            ])
            ->add('content', TextareaType::class, [
            'label' => 'Contenu',
            'attr' => [
                'class' => 'form-label form-control',
                'placeholder' => 'Détail de mes motivations',
                'row' => '15'
            ],
        ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoverLetter::class,
        ]);
    }
}
