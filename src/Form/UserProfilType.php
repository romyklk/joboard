<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserProfil;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre prénom'
                ]
            ])
            ->add('lastName',TextType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre nom'
                ]
            ])
            ->add('address',TextType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre adresse'
                ]
            ])
            ->add('city',TextType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre ville'
                ]
            ])
            ->add('zipCode',TextType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre code postal'
                ]
            ])
            ->add('country',CountryType::class,[
                'label' => false,
                'preferred_choices' => [
                    'FR',
                    'BE',
                    'CH',
                    'LU',
                    'DE',
                    'IT',
                ]
            ])
            ->add('phoneNumber',TelType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone'
                ]
            ])
            ->add('jobSought',TextType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez le poste recherché'
                ]
            ])
            ->add('presentation',TextareaType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Présentez-vous en quelques mots',
                    'rows' => 10,
                ],
            ])
            ->add('availability',CheckboxType::class,[
                'label' => 'Êtes-vous disponible ?',
                'attr' => [
                    'placeholder' => 'Êtes-vous disponible ?'
                ],
                'required' => false,
            ])
            ->add('website',UrlType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre site web'
                ]
            ])
            ->add('imageFile',FileType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Choisissez votre photo de profil'
                ],
                // Ajout de la contrainte de validation avec Assert
                'required' => false,
            'constraints' => [
                new Image([
                    'maxSize' => '3M',
                    'mimeTypes' => [
                        'image/jpg',
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                    ],
                    'mimeTypesMessage' => 'Veuillez uploader une image au format jpg, jpeg, png ou gif',

                ])
            ]


            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfil::class,
        ]);
    }
}
