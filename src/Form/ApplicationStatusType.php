<?php

namespace App\Form;

use App\Entity\ApplicationStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ApplicationStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Statut de la candidature',
                'choices' => [
                    'En attente' => ApplicationStatus::STATUS_PENDING,
                    'Accepté' =>ApplicationStatus::STATUS_ACCEPTED,
                    'Refusé' => ApplicationStatus::STATUS_REFUSED,
                ],
                'attr' => [
                    'class' => 'form-control mb-3 w-25',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour le statut',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => ApplicationStatus::class,
        ]);
    }
}
