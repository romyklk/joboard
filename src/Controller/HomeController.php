<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Application;
use App\Entity\HomeSetting;
use App\Form\ApplicationType;
use App\Entity\EntrepriseProfil;
use App\Repository\ApplicationRepository;
use App\Repository\TagRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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


    //Afficher les details d'une offre
    #[Route('/offre-emploi/{id}/{slug}', name: 'app_offre_emploi_show', methods: ['GET', 'POST'])]
    public function showOffer(int $id, string $slug, Request $request,OfferRepository $offerRepository,ApplicationRepository $applicationRepository,EntityManagerInterface $em): Response
    {
        // Récupérer l'offre d'emploi
        $offer = $offerRepository->findOneBy([
            'id' => $id,
            'slug' => $slug
        ]);

        // Si l'offre n'existe pas, on affiche une erreur 404
        if(!$offer) {
            throw $this->createNotFoundException('Cette offre n\'existe pas');
        }

        // Si le candidat a déjà postulé à cette offre, on le redirige vers la page de l'offre

        $existingApplication = $applicationRepository->findOneBy([
            'user' => $this->getUser(),
            'Offer' => $offer // ATTENTION J'UTILISE LA PROPRIETE Offer AVEC UNE MAJUSCULE
        ]);
        //dd($existingApplication);

        if ($existingApplication) {
            //$this->addFlash('danger', 'Vous avez déjà postulé à cette offre');
            notyf()
            ->position('x', 'right')
                ->position('y', 'top')
                ->dismissible(true)
                ->addWarning('Vous avez déjà postulé à cette offre.');
            //return $this->redirectToRoute('app_offre_emploi');
        }

        // Envoyer le formulaire de candidature
        $application = new Application();
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur connecté
            $user = $this->getUser();

            // Récupérer le message
            $message = $form->get('message')->getData();

            $application->setUser($user);
            $application->setOffer($offer);
            $application->setMessage($message);
            $application->setCreatedAt(new \DateTimeImmutable());
            $application->setStatus('STATUS_PENDING');

            // Enregistrer l'application 
            $em->persist($application);
            $em->flush();
            // Envoyer un message flash
            notyf()
            ->position('x', 'right')
                ->position('y', 'top')
                ->dismissible(true)
                ->addSuccess('Votre candidature a bien été envoyée.');

            // Rediriger l'utilisateur vers la page des offres
            return $this->redirectToRoute('app_offre_emploi');
        }

        return $this->render('home/offer_show.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
            'existingApplication' => $existingApplication ?? null
        ]);
    }

}
