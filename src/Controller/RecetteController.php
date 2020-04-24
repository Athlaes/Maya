<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\RecetteRepository;
use App\Entity\Produit;
use App\Entity\Recette;
use App\Form\RecetteType;


class RecetteController extends AbstractController
{
    /**
     * @Route("/recettes", name="recettes")
     * @Route("/recettes/complet/{id}", name="recetteComplet")
     */
    public function index($id = null, RecetteRepository $repository)
    {
        $laRecette = null;
        if(isset($id)){
            $laRecette = $repository->findOneBy(['id' => $id]);
        }

        $lesRecettes = $repository->findAll();
        return $this->render('recette/index.html.twig', [
            'lesRecettes' => $lesRecettes,
            'laRecette' => $laRecette,
        ]);
    }

    /**
     * @Route("/recettes/ajouter", name="recetteAjouter")
     */
    public function ajouterRecette(Recette $recette = null, RecetteRepository $repository, Request $request, EntityManagerInterface $entityManager) : response
    {
        
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // cas où le formulaire d'ajout a été soumis par l'utilisateur et est valide
            $recette = $form->getData();
            // on met à jour la base de données 
            $entityManager->persist($recette);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'La recette ' . $recette->getNom() . ' a été ajouté.'
            );
            return $this->redirectToRoute('recettes');
        } else {
            $lesRecettes = $repository->findAll();
            return $this->render('recette/index.html.twig', [
                'lesRecettes' => $lesRecettes,
                'laRecette' => null,
                'form' => $form->createView(),
                'formTitle' => "Création d'une recette",
                'formSubmit' => "Ajouter",
            ]);
        }
    }

    /**
     * @Route("/recettes/modifier/{id}", name="recetteModifier")
     */
    public function modifierRecette(Recette $recette = null, EntityManagerInterface $entityManager, Request $request, RecetteRepository $repository) : Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // cas où le formulaire d'ajout a été soumis par l'utilisateur et est valide
            $recette = $form->getData();
            // on met à jour la base de données 
            $entityManager->persist($recette);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'La recette ' . $recette->getNom() . ' a été ajouté.'
            );
            return $this->redirectToRoute('recettes');
        } else {
            $lesRecettes = $repository->findAll();
            return $this->render('recette/index.html.twig', [
                'lesRecettes' => $lesRecettes,
                'laRecette' => null,
                'form' => $form->createView(),
                'formTitle' => "Modification d'une recette",
                'formSubmit' => "Modifier",
            ]);
        }

    }

    /**
     * @Route("/recettes/supprimer/{id}", name="recetteSupprimer")
     */
    public function supprimerRecette(Recette $recette, EntityManagerInterface $entityManager, Request $request) : Response
    {
        if ($this->isCsrfTokenValid('action-item'.$recette->getId(), $request->get('_token'))) {
            $entityManager->remove($recette);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'La recette '.$recette->getNom().' a été supprimé.'
            );
        }

        return $this->redirectToRoute('recettes');
    }

}
