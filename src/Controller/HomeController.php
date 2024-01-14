<?php

namespace App\Controller;


use App\Entity\HomeSetting;
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
        return $this->render('home/index.html.twig', [
            'homeSetting' => $homeSetting,
        ]);
    }
}
