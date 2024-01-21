<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        $user = $this->getUser();
       

        // Si l'utilisateur est une entreprise et qu'il a un profil, on le redirige vers son profil entreprise
        if ($user->getRoles()[0] === 'ROLE_PRO' && $user->getEntrepriseProfil() !== null) {
            return $this->redirectToRoute('app_entreprise_profil_show', [
                'id' => $user->getEntrepriseProfil()->getId()
            ]);
        }

        // Si l'utilisateur est un candidat et qu'il a un profil, on le redirige vers son profil candidat
        if ($user->getRoles()[0] === 'ROLE_USER' && $user->getUserProfil() !== null) {
            return $this->redirectToRoute('app_user_profil_show', [
                'id' => $user->getUserProfil()->getId()
            ]);
        }

        // Si l'utilisateur est un admin, on le redirige vers la page admin
        if ($user->getRoles()[0] === 'ROLE_ADMIN') {
            return $this->redirectToRoute('app_admin');
        }

        // Sinon il verra la page par défaut
        return $this->render('account/index.html.twig', []);
    }
}
