<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword',PasswordType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre mot de passe actuel'
                ],
                
                'required' => true,
            ])
            ->add('newPassword',PasswordType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre nouveau mot de passe'
                ],
                'required' => true,
                
                
            ])
            ->add('confirmPassword',PasswordType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Veuillez confirmer votre nouveau mot de passe'
                ],
                'required' => true,
                
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
