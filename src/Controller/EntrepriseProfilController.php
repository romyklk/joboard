<?php

namespace App\Controller;

use Cocur\Slugify\Slugify;
use App\Entity\EntrepriseProfil;
use App\Form\EntrepriseProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EntrepriseProfilRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Services\UploadFilesServices;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/account')]
class EntrepriseProfilController extends AbstractController
{
    #[Route('/entreprise/profil', name: 'app_entreprise_profil')]
    public function index(Request $request, EntityManagerInterface $em, UploadFilesServices $uploadFilesServices): Response
    {

        // Vérification si l'utilisateur a déjà un profil. Si oui, on le redirige vers son profil

        $user = $this->getUser();

        if ($user->getEntrepriseProfil()) {
            return $this->redirectToRoute('app_entreprise_profil_show', ['id' => $user->getEntrepriseProfil()->getId()]);
        }

        $entrepriseProfil = new EntrepriseProfil();

        $form = $this->createForm(EntrepriseProfilType::class, $entrepriseProfil);
        $form->handleRequest($request);

        $slugify = new Slugify();


        if ($form->isSubmitted() && $form->isValid()) {

            $entrepriseProfil->setUser($this->getUser());

            $entrepriseProfil->setSlug($slugify->slugify($entrepriseProfil->getName()));

            $file = $form['logoEntreprise']->getData();

            if ($file) {
                $file_name = $uploadFilesServices->saveFile($file);
                $entrepriseProfil->setLogo($file_name);
            } else {
                $entrepriseProfil->setLogo('default.png');
            }

            $em->persist($entrepriseProfil);
            $em->flush();

            $this->addFlash('success', 'Profil à créé avec succès');
            return $this->redirectToRoute('app_entreprise_profil_show', ['id' => $entrepriseProfil->getId()]);
        }
        return $this->render('entreprise_profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Cette route permet d'afficher le profil de l'enreprise connecté
    #[Route('/entreprise/profil/{id}', name: 'app_entreprise_profil_show')]
    public function show(int $id, EntrepriseProfilRepository $entrepriseProfilRepository): Response
    {
        // Si l'utilisateur connecté n'est pas le propriétaire du profil, on le redirige vers son profil
        $user = $this->getUser();
        $entrepriseProfil = $entrepriseProfilRepository->find($id);

        if (!$entrepriseProfil || $user->getEntrepriseProfil() !== $entrepriseProfil) {
            return $this->redirectToRoute('app_entreprise_profil_show', [
                'id' => $user->getEntrepriseProfil()->getId()
            ]);
        }

        return $this->render('entreprise_profil/show.html.twig', [
            'entrepriseProfil' => $entrepriseProfil,
        ]);
    }
    // Cette route permet de modifier le profil de l'utilisateur connecté
    #[Route('/entreprise/profil/{id}/edit', name: 'app_entreprise_profil_edit')]
    public function edit(Request $request, int $id, EntrepriseProfilRepository $entrepriseProfilRepository, EntityManagerInterface $em, UploadFilesServices $uploadFilesServices): Response
    {

        // Si l'utilisateur connecté n'est pas le propriétaire du profil, on le redirige vers son profil
        $user = $this->getUser();
        $entrepriseProfil = $entrepriseProfilRepository->find($id);

        if (!$entrepriseProfil || $user->getEntrepriseProfil() !== $entrepriseProfil) {
            return $this->redirectToRoute('app_entreprise_profil_show', [
                'id' => $user->getEntrepriseProfil()->getId()
            ]);
        }

        $form = $this->createForm(EntrepriseProfilType::class, $entrepriseProfil);
        $form->handleRequest($request);

        $slugify = new Slugify();

        if ($form->isSubmitted() && $form->isValid()) {

            $entrepriseProfil->setSlug($slugify->slugify($entrepriseProfil->getName()));

            $file = $form['logoEntreprise']->getData();
            if ($file) {
                $file_name = $uploadFilesServices->saveFile($file);
                $entrepriseProfil->setLogo($file_name);
            }

            $em->persist($entrepriseProfil);
            $em->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès');
            return $this->redirectToRoute('app_account');
        }
        return $this->render('entreprise_profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Cette route permet de supprimer le profil de l'utilisateur connecté
    #[Route('/entreprise/profil/{id}/delete', name: 'app_entreprise_profil_delete')]
    public function delete(int $id, EntrepriseProfilRepository $entrepriseProfilRepository, EntityManagerInterface $em, TokenStorageInterface $tokenStorage, SessionInterface $session): Response
    {
        // Si l'utilisateur connecté n'est pas le propriétaire du profil, on le redirige vers son profil
        $user = $this->getUser();
        $entrepriseProfil = $entrepriseProfilRepository->find($id);

        if (!$entrepriseProfil || $user->getEntrepriseProfil() !== $entrepriseProfil) {
            return $this->redirectToRoute('app_entreprise_profil_show', [
                'id' => $user->getEntrepriseProfil()->getId()
            ]);
        }

        $em->remove($entrepriseProfil);
        $em->flush();

        // Déconnecter l'utilisateur
        $tokenStorage->setToken(null);

        // Invalidate session afin que l'utilisateur ne puisse pas accéder à la page de son profil
        $session->invalidate();

        return $this->redirectToRoute('app_home');
    }
}
