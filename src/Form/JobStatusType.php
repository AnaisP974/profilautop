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
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class JobStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', EnumType::
            class, [
                'class' => JobStatus::class,
                'choice_label' => function ($choice) {
                    return match ($choice) {
                        JobStatus::A_POSTULER => "À postuler",
                        JobStatus::EN_ATTENTE => "En attente",
                        JobStatus::ENTREVUE => "Entretien",
                        JobStatus::REFUSE => "Refusé",
                        JobStatus::ACCEPTE => "Accepté",
                    };
                },
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobOffer::class,
        ]);
    }
}
