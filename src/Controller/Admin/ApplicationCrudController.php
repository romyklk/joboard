<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Application;
use Symfony\Component\Validator\Constraints\Date;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ApplicationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Application::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->disable(Action::DELETE)
            ->disable(Action::EDIT)
            ->disable(Action::NEW);
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('status', 'Statut')->onlyOnIndex(),
            AssociationField::new('user', 'Candidat')->onlyOnIndex(),
            AssociationField::new('Offer', 'Offre')->onlyOnIndex(),
            TextEditorField::new('message', 'Message')->onlyOnIndex(),
                        DateTimeField::new('createdAt', 'Date de création')->onlyOnIndex(),
        ];
    }
    
}
