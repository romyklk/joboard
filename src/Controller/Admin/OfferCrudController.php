<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offer::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextEditorField::new('shortDescription', 'Description'),
            MoneyField::new('salary', 'Salaire')->setCurrency('EUR'),
            TextField::new('location', 'Ville'),
            AssociationField::new('contractType', 'Type de contrat'),
            AssociationField::new('entreprise', 'Entreprise'),
            AssociationField::new('tags', 'Tags'),
            BooleanField::new('isActive', 'Activée'),

            //Si on veut afficher les noms des tags dans la liste des offres
            /* AssociationField::new('tags', 'Tags')->formatValue(function ($value) {
                return implode(' | ', array_map(function ($tag) {
                    return $tag->getName();
                }, $value->toArray()));
            }), */
        ];
    }
}
