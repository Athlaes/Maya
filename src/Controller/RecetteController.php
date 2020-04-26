<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface; 
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Entity\Recette;
use App\Entity\RecetteRecherche;
use App\Form\RecetteRechercheType;
use App\Form\RecetteType;
use Knp\Component\Pager\PaginatorInterface;


class RecetteController extends AbstractController
{
    /**
     * @Route("/recettes", name="recettes")
     * @Route("/recettes/complet/{id}", name="recetteComplet")
     */
    public function index($id = null, RecetteRepository $repository, Request $request, SessionInterface $session, PaginatorInterface $paginator)
    {
        $recetteRecherche = new RecetteRecherche();
        $formRecherche = $this->createForm(RecetteRechercheType::class, $recetteRecherche);
        $formRecherche->handleRequest($request);
        if ($formRecherche->isSubmitted() && $formRecherche->isValid()) {
            $recetteRecherche = $formRecherche->getData();
            $session->set('recetteCriteres', $recetteRecherche);
            $lesRecettes = $paginator->paginate(
                $repository->findAllByCriteria($recetteRecherche),
                $request->query->getint('page',1),
                5
            ); 
        }
        else {
            if($session->has('recetteCriteres')){
                $recetteRecherche = $session->get('recetteCriteres');
                $formRecherche = $this->createForm(RecetteRechercheType::class, $recetteRecherche);
                $formRecherche->setData($recetteRecherche);
                $lesRecettes = $paginator->paginate(
                    $repository->findAllByCriteria($recetteRecherche),
                    $request->query->getint('page',1),
                    5
                ); 
            } else {
                $r = new RecetteRecherche();
                $lesRecettes = $paginator->paginate(
                    $repository->findAllByCriteria($r),
                    $request->query->getint('page',1),
                    5
                ); 
            }
        }
        $laRecette = null;
        if(isset($id)){
            $laRecette = $repository->findOneBy(['id' => $id]);
        }
        return $this->render('recette/index.html.twig', [
            'lesRecettes' => $lesRecettes,
            'laRecette' => $laRecette,
            'formRecherche' => $formRecherche->createView(),
        ]);
    }

    /**
     * @Route("/recettes/ajouter", name="recetteAjouter")
     */
    public function ajouterRecette(Recette $recette = null, RecetteRepository $repository, Request $request, EntityManagerInterface $entityManager, SessionInterface $session, PaginatorInterface $paginator) : response
    {
        if($session->has('recetteCriteres')){
            $recetteRecherche = $session->get('recetteCriteres');
            $formRecherche = $this->createForm(RecetteRechercheType::class, $recetteRecherche);
            $formRecherche->setData($recetteRecherche);
            $lesRecettes = $paginator->paginate(
                $repository->findAllByCriteria($recetteRecherche),
                $request->query->getint('page',1),
                5
            ); 
        } else {
            $r = new RecetteRecherche();
            $formRecherche =$this->createForm(RecetteRechercheType::class, $recetteRecherche);
            $lesRecettes = $paginator->paginate(
                $repository->findAllByCriteria($r),
                $request->query->getint('page',1),
                5
            ); 
        }
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
            return $this->render('recette/index.html.twig', [
                'lesRecettes' => $lesRecettes,
                'laRecette' => null,
                'form' => $form->createView(),
                'formTitle' => "Création d'une recette",
                'formSubmit' => "Ajouter",
                'formRecherche' => $formRecherche->createView(),
            ]);
        }
    }

    /**
     * @Route("/recettes/modifier/{id}", name="recetteModifier")
     */
    public function modifierRecette(Recette $recette = null, EntityManagerInterface $entityManager, Request $request, RecetteRepository $repository, SessionInterface $session, PaginatorInterface $paginator) : Response
    {
        if($session->has('recetteCriteres')){
            $recetteRecherche = $session->get('recetteCriteres');
            $formRecherche = $this->createForm(RecetteRechercheType::class, $recetteRecherche);
            $formRecherche->setData($recetteRecherche);
            $lesRecettes = $paginator->paginate(
                $repository->findAllByCriteria($recetteRecherche),
                $request->query->getint('page',1),
                5
            ); 
        } else {
            $r = new RecetteRecherche();
            $formRecherche =$this->createForm(RecetteRechercheType::class, $recetteRecherche);
            $lesRecettes = $paginator->paginate(
                $repository->findAllByCriteria($r),
                $request->query->getint('page',1),
                5
            ); 
        }
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // cas où le formulaire d'ajout a été soumis par l'utilisateur et est valide
            $recette = $form->getData();
            // on met à jour la base de données 
            $entityManager->flush();
            $this->addFlash(
                'success',
                'La recette ' . $recette->getNom() . ' a été ajouté.'
            );
            return $this->redirectToRoute('recettes');
        } else {
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
