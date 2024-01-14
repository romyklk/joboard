<?php

namespace App\Form;

use App\Entity\ContractType;
use App\Entity\EntrepriseProfil;
use App\Entity\Offer;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label' => false,
                'attr' =>[
                    'placeholder' => 'Entrez le titre de l\'offre'
                ]
            ])
            ->add('shortDescription',TextareaType::class, [
                'label' => false,
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Entrez une courte description de l\'offre',
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'rows' => 10,
                    'placeholder' => 'Entrez le contenu de l\'offre, toutes les informations utiles pour les candidats',
                ],
            ])
            ->add('salary',MoneyType::class,[
                'label' => false,
                'attr' =>[
                    'placeholder' => 'Entrez le salaire de l\'offre'
                ]
            ])
            ->add('location', TextType::class,[
                'label' => false,
                'attr' =>[
                    'placeholder' => 'Entrez la localisation de l\'offre'
                ]
            ])
            ->add('contractType', EntityType::class, [
                'label' => false,
                'class' => ContractType::class,
                'choice_label' => 'name',
            ])
            ->add('tags', EntityType::class, [
                'label' => false,
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
