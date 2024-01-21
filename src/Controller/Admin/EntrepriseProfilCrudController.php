<?php

namespace App\Controller\Admin;

use PharIo\Manifest\Url;
use PharIo\Manifest\Email;
use Faker\Provider\ar_EG\Text;
use App\Entity\EntrepriseProfil;
use Doctrine\ORM\Mapping\Entity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EntrepriseProfilCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EntrepriseProfil::class;
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
            IdField::new('id')->hideOnForm(),
            TextField::new('name','Nom de l\'entreprise'),
            //EmailField::new('email','Email'),
            UrlField::new('website','Site Web'),
            //TextEditorField::new('description','Description'),
            //TextField::new('address','Adresse'),
            //TextField::new('city','Ville'),
            //TextField::new('zipCode','Code Postal'),
            TextField::new('country','Pays'),
            TextField::new('phoneNumber','Téléphone'),
            TextField::new('activityArea','Secteur d\'activité'),
            AssociationField::new('user','Utilisateur'),
            AssociationField::new('offers','Offres'),
            ImageField::new('logo', 'Logo')->setBasePath('uploads/')->setUploadDir('public/uploads/')->setUploadedFileNamePattern('[randomhash].[extension]')->setRequired(false),
        ];
    }
    
}
