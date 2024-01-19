<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/account')]
class TagController extends AbstractController
{

    #[Route('/tag', name: 'app_tag_add')]
    public function index(Request $request,EntityManagerInterface $em,TagRepository $tagRepository): Response
    {
        //$allTags = $tagRepository->findAll();
        // Récupérer tous les tags par ordre alphabétique
        $allTags = $tagRepository->findBy([], ['name' => 'ASC']);

        // Créer un nouveau tag
        $tag = new Tag();

        // Créer le formulaire
        $form = $this->createForm(TagType::class, $tag);
        // Récupérer les données du formulaire
        $form->handleRequest($request);
        $slugify = new Slugify();

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $tag = $form->getData();
            // Générer le slug
            //$tag->setSlug($slugify->slugify($tag->getName()));

            // Enregistrer le tag en BDD
            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute('app_tag_add');
        }
        return $this->render('tag/index.html.twig', [
            'form' => $form->createView(),
            'tags' => $allTags,
        ]);
    }

/*     #[Route('/tag/edit/{id}', name: 'app_tag_edit')]
    public function edit(Request $request, EntityManagerInterface $em, Tag $tag): Response
    {

        // Créer le formulaire
        $form = $this->createForm(TagType::class, $tag);
        // Récupérer les données du formulaire
        $form->handleRequest($request);
        $slugify = new Slugify();

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $tag = $form->getData();
            // Générer le slug
            $tag->setSlug($slugify->slugify($tag->getName()));

            // Enregistrer le tag en BDD
            $em->persist($tag);
            $em->flush();

            // Rediriger l'utilisateur vers la page de profil
            return $this->redirectToRoute('app_account');
        }
        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
        ]);
    } */

}
