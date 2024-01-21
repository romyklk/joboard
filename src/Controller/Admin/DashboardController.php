<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Offer;
use App\Entity\UserProfil;
use App\Entity\Application;
use App\Entity\HomeSetting;
use App\Entity\ContractType;
use App\Entity\EntrepriseProfil;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
         return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sym Job Board Admin')
            ->setFaviconPath('uploads/Logo.png')
            ->setLocales([
                'fr' => 'Français 🇫🇷',
                'en' => 'English 🇬🇧',
                ])
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Réglages Accueil', 'fa-solid fa-gear',HomeSetting::class);
        yield MenuItem::linkToCrud('Types de Contrats', 'fa-solid fa-file-contract',ContractType::class);
        yield MenuItem::linkToCrud('Offres', 'fa-solid fa-file-alt',Offer::class);
        yield MenuItem::linkToCrud('Mot clés', 'fa-solid fa-key',Tag::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-user',UserProfil::class);
        yield MenuItem::linkToCrud('Employeurs', 'fa-solid fa-user-tie',EntrepriseProfil::class);
        yield MenuItem::linkToCrud('Candidatures', 'fa-solid fa-file-signature',Application::class);
        yield MenuItem::linkToRoute('Retour au site', 'fa-solid fa-home', 'app_home');
        yield MenuItem::linkToLogout('Logout', 'fa-solid fa-right-from-bracket');
        
    }
}

