<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account')]
class OfferController extends AbstractController
{

    #[Route('/offer', name: 'app_offer')]
    public function index(OfferRepository $offerRepository): Response
    {
        $user = $this->getUser();

        // Récupération de l'entreprise de l'utilisateur connecté
        $company = $user->getEntrepriseProfil();

        // Si l'utilisateur n'a encore créer de profil entreprise, on le redirige vers la page de création de profil
        if (!$company) {
            return $this->redirectToRoute('app_entreprise_profil');
        }

        // Récupération des offres de l'entreprise connectée
        $offers = $offerRepository->findByEntreprise($company);


        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/offer/new', name: 'app_offer_new')]
    public function new(Request $request,EntityManagerInterface $em,): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();
        // Récupération de l'entreprise de l'utilisateur connecté
        $company = $user->getEntrepriseProfil();
        // Si l'utilisateur n'a encore créer de profil entreprise, on le redirige vers la page de création de profil
        if (!$company) {
            return $this->redirectToRoute('app_entreprise_profil');
        }
        
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);
        //$slugify = new Slugify();

        if ($form->isSubmitted() && $form->isValid()) {
            
            //$offer->setSlug($slugify->slugify($offer->getTitle()));
            $offer->setEntreprise($company);

            $em->persist($offer);
            $em->flush();

            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->dismissible(true)
                ->addSuccess('Votre offre a bien été créée.');
                
            return $this->redirectToRoute('app_offer');
        }


        return $this->render('offer/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /*   // Suppression de toutes les offres
    #[Route('/offer/delete', name: 'app_offer_delete')]
    public function deleteAll(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $company = $user->getEntrepriseProfil();
        $offers = $company->getOffers();

        foreach ($offers as $offer) {
            $em->remove($offer);
        }
        $em->flush();

        $this->addFlash('success', 'Toutes vos offres ont été supprimées avec succès');
        return $this->redirectToRoute('app_offer');
    } */


    #[Route('/offer/{id}/edit', name: 'app_offer_edit')]
    public function edit(Request $request, int $id, OfferRepository $offerRepository, EntityManagerInterface $em): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();

        // Récupération de l'entreprise de l'utilisateur connecté
        $company = $user->getEntrepriseProfil();

        // Si l'utilisateur n'a encore créer de profil entreprise, on le redirige vers la page de création de profil
        if (!$company) {
            return $this->redirectToRoute('app_entreprise_profil');
        }

        $offer = $offerRepository->find($id);
        // Si l'on ne trouve pas l'offre ou que l'offre ne correspond pas à l'entreprise connectée, on redirige vers la page des offres
        if (!$offer || $offer->getEntreprise() !== $company) {
            return $this->redirectToRoute('app_offer');
        }

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);
        //$slugify = new Slugify();

        if ($form->isSubmitted() && $form->isValid()) {
            //$offer->setSlug($slugify->slugify($offer->getTitle()));
            $em->persist($offer);
            $em->flush();

            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->dismissible(true)
                ->addSuccess('Votre offre a bien été modifiée.');

            return $this->redirectToRoute('app_offer');
        }

        return $this->render('offer/edit.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer,
        ]);
    }

    #[Route('/offer/{id}/delete', name: 'app_offer_delete')]
    public function delete(int $id, OfferRepository $offerRepository, EntityManagerInterface $em): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();

        // Récupération de l'entreprise de l'utilisateur connecté
        $company = $user->getEntrepriseProfil();

        // Si l'utilisateur n'a encore créer de profil entreprise, on le redirige vers la page de création de profil
        if (!$company) {
            return $this->redirectToRoute('app_entreprise_profil');
        }

        $offer = $offerRepository->find($id);
        // Si l'on ne trouve pas l'offre ou que l'offre ne correspond pas à l'entreprise connectée, on redirige vers la page des offres
        if (!$offer || $offer->getEntreprise() !== $company) {
            return $this->redirectToRoute('app_offer');
        }

        $em->remove($offer);
        $em->flush();

        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->dismissible(true)
            ->addSuccess('Votre offre a bien été supprimée.');

        return $this->redirectToRoute('app_offer');
    }

    #[Route('/offer/{id}/show', name: 'app_offer_show')]
    public function show(int $id, OfferRepository $offerRepository): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();

        // Récupération de l'entreprise de l'utilisateur connecté
        $company = $user->getEntrepriseProfil();

        // Si l'utilisateur n'a encore créer de profil entreprise, on le redirige vers la page de création de profil
        if (!$company) {
            return $this->redirectToRoute('app_entreprise_profil');
        }

        $offer = $offerRepository->find($id);
        // Si l'on ne trouve pas l'offre ou que l'offre ne correspond pas à l'entreprise connectée, on redirige vers la page des offres
        if (!$offer || $offer->getEntreprise() !== $company) {
            return $this->redirectToRoute('app_offer');
        }

        return $this->render('offer/show_offer.html.twig', [
            'offer' => $offer,
        ]);
    }

}
