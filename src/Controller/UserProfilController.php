<?php

namespace App\Controller;

use App\Entity\UserProfil;
use Cocur\Slugify\Slugify;
use App\Form\UserProfilType;
use App\Repository\UserProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\UploadFilesServices;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

// On définit le préfixe de route pour toutes les routes de ce contrôleur
#[Route('/account')]
class UserProfilController extends AbstractController
{
    #[Route('/user/profil', name: 'app_user_profil')]
    public function index(Request $request, UserProfilRepository $userProfilRepository, UploadFilesServices $uploadFilesServices, EntityManagerInterface $em): Response
    { 
        // On vérifie que l'utilisateur n'a pas déjà un profil
        $user = $this->getUser();
        if ($user->getUserProfil() !== null) {
            return $this->redirectToRoute('app_user_profil_show', [
                'slug' => $user->getUserProfil()->getSlug(),
                'id' => $user->getUserProfil()->getId()
            ]);
        }

        $userProfil = new UserProfil();

        $form = $this->createForm(UserProfilType::class, $userProfil);

        $form->handleRequest($request);

        $slugify = new Slugify();
        if ($form->isSubmitted() && $form->isValid()) {

            $userProfil->setUser($this->getUser());

            $userProfil->setSlug($slugify->slugify($userProfil->getFirstName() . ' ' . $userProfil->getLastName()));

            //dd($form['imageFile']->getData()); // On récupère l'image
            $file = $form['imageFile']->getData(); // On récupère l'image

            // On sauvegarde l'image
            if ($file) {
                $file_name = $uploadFilesServices->saveFile($file); // On sauvegarde l'image
                $userProfil->setPicture($file_name);
            } else {
                $userProfil->setPicture('default.png');
            }

            // On ajoute l'image à l'entité

            //dd($userProfil);
            $em->persist($userProfil);
            $em->flush();

            notyf()
            ->position('x', 'right')
                ->position('y', 'top')
                ->dismissible(true)
                ->addSuccess('Votre profil a bien été créé.');

            return $this->redirectToRoute('app_user_profil', [
                'id' => $userProfil->getId()
            ]);
        }

        return $this->render('user_profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Cette route permet d'afficher le profil de l'utilisateur connecté
    #[Route('/user/profil/{id}', name: 'app_user_profil_show')]
    public function show(int $id, UserProfilRepository $userProfilRepository): Response
    {
        // Si l'utilisateur connecté n'est pas le propriétaire du profil, on le redirige vers son profil
        $user = $this->getUser();
        $userProfil = $userProfilRepository->find($id);

        // Si l'utilisateur connecté n'est pas le propriétaire du profil, on le redirige vers son profil
        if (!$userProfil || $user->getUserProfil() !== $userProfil) {
            return $this->redirectToRoute('app_user_profil_show', [
                'id' => $user->getUserProfil()->getId()
            ]);
        }

        return $this->render('user_profil/show.html.twig', [
            'userProfil' => $userProfil,
        ]);
    }


    // Cette route permet de modifier le profil de l'utilisateur connecté
    #[Route('/user/profil/{id}/edit', name: 'app_user_profil_edit')]
    public function editUser(Request $request, $id, UserProfilRepository $userProfilRepository, UploadFilesServices $uploadFilesServices, EntityManagerInterface $em): Response
    {
        // Si l'utilisateur connecté n'est pas le propriétaire du profil, on le redirige vers son profil
        $user = $this->getUser();
        $userProfil = $userProfilRepository->find($id);
        if (!$userProfil || $user->getUserProfil() !== $userProfil) {
            return $this->redirectToRoute('app_user_profil_show', [
                'id' => $user->getUserProfil()->getId()
            ]);
        }

        $form = $this->createForm(UserProfilType::class, $userProfil);

        $form->handleRequest($request);

        $slugify = new Slugify();
        if ($form->isSubmitted() && $form->isValid()) {

            $userProfil->setUser($this->getUser());

            $userProfil->setSlug($slugify->slugify($userProfil->getFirstName() . ' ' . $userProfil->getLastName()));

            // Si l'utilisateur a ajouté une nouvelle image
            $file = $form['imageFile']->getData();

            if ($file) {
                $file_name = $uploadFilesServices->updateFile($file, $userProfil->getPicture()); // On met à jour l'image
                $userProfil->setPicture($file_name); // On ajoute l'image à l'entité
            }

            //dd($userProfil);
            $em->persist($userProfil);
            $em->flush();

            notyf()
            ->position('x', 'right')
                ->position('y', 'top')
                ->dismissible(true)
                ->addSuccess('Votre profil a bien été modifié.');

            return $this->redirectToRoute('app_user_profil_show', [
                'id' => $userProfil->getId()
            ]);
        }

        return $this->render('user_profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    // Cette route permet de supprimer le profil de l'utilisateur connecté
    #[Route('/user/profil/{id}/delete', name: 'app_user_profil_delete')]
    public function delete(int $id, UserProfilRepository $userProfilRepository, EntityManagerInterface $em, TokenStorageInterface $tokenStorage, SessionInterface $session,UploadFilesServices $uploadFilesServices): Response
    {
        // Si l'utilisateur connecté n'est pas le propriétaire du profil, on le redirige vers son profil
        $user = $this->getUser();
        $userProfil = $userProfilRepository->find($id);
        if (!$userProfil || $user->getUserProfil() !== $userProfil) {
            return $this->redirectToRoute('app_user_profil_show', [
                'id' => $user->getUserProfil()->getId()
            ]);
        }

        // On supprime l'image du dossier uploads
        if ($userProfil->getPicture() !== 'default.png') {
            $uploadFilesServices->deleteFile($userProfil->getPicture());
        }

        $em->remove($userProfil);
        $em->flush();

        

        // Déconnecter l'utilisateur
        $tokenStorage->setToken(null);

        // Invalidate session afin que l'utilisateur ne puisse pas accéder à la page de son profil
        $session->invalidate();
        return $this->redirectToRoute('app_home');
    }
}
