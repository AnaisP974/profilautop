<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\JobOffer;
use App\Entity\CoverLetter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FranceTravailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Liste des départements
        $departments = [
            'Ain' => '01',
            'Aisne' => '02',
            'Allier' => '03',
            'Alpes-de-Haute-Provence' => '04',
            'Hautes-Alpes' => '05',
            'Alpes-Maritimes' => '06',
            // Continue pour tous les départements...
            'Paris' => '75',
            'Rhône' => '69',
            'Seine-Maritime' => '76',
            'Seine-et-Marne' => '77',
            // etc.
        ];
        $builder
            ->add('title', TextType::class, [
            'label' => 'Poste recherché',
            'attr' => [
                'placeholder' => 'Entrez le poste recherché',
                'class' => 'form-label form-control',
            ]
        ])
            ->add('location', ChoiceType::class, [
            'label' => 'Département',
            'choices' => $departments,
            'placeholder' => 'Sélectionnez un département',
            'attr' => [
                'class' => 'form-select' // Utilise Bootstrap ou autre style
            ]
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
