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
use Symfony\Component\HttpFoundation\Request;

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
    public function getOffers(OfferRepository $offerRepository,TagRepository $tagRepository,Request $request): Response
    {
        /*  $offers = $offerRepository->findAll();
        $tags = $tagRepository->findAll();
        return $this->render('home/offer_list.html.twig', [
            'offers' => $offers,
            'tags' => $tags
        ]); */

        // 

        $tags = $tagRepository->findAll();

        //Récupérer le numéro de la page courante dans l'URL
        // 1 est la valeur par défaut si aucun numéro de page n'est spécifié dans l'URL
        $page = $request->query->getInt('page', 1);

        $offers = $offerRepository->findPaginatedOffers($page, 9);
        
        return $this->render('home/offer_list.html.twig', [
            'offers' => $offers,
            'tags' => $tags
        ]);
    } 

}
