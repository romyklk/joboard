<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\HomeSetting;
use App\Entity\EntrepriseProfil;
use App\Repository\OfferRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em): Response
    {
        $homeSetting = $em->getRepository(HomeSetting::class)->findAll();

        // Récupérer les 6 dernières offres
        $offers = $em->getRepository(Offer::class)->findBy([], ['createdAt' => 'DESC'], 6);
        

        // Récupérer le 4 dernières entreprises

        $entreprises = $em->getRepository(EntrepriseProfil::class)->findBy([], ['id' => 'DESC'], 4);

        return $this->render('home/index.html.twig', [
            'homeSetting' => $homeSetting,
            'offers' => $offers,
            'entreprises' => $entreprises
        ]);
    }

    //affiche la liste des offres d'emploi sur la page Offres
    #[Route('/offre-emploi', name: 'app_offre_emploi', methods: ['GET'])]
    public function deleteAll(OfferRepository $offerRepository,TagRepository $tagRepository): Response
    {
        $offers = $offerRepository->findAll();
        $tags = $tagRepository->findAll();
        return $this->render('home/offer_list.html.twig', [
            'offers' => $offers,
            'tags' => $tags
        ]);
    } 

}
