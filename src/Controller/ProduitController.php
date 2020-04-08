<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Entity\ProduitRecherche;   
use App\Form\ProduitRechercheType; 
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface; 
use Knp\Component\Pager\PaginatorInterface;


class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function index(ProduitRepository $repository, Request $request, SessionInterface $session, PaginatorInterface $paginator)
    {
        // créer l'objet et le formulaire de recherche
        $produitRecherche = new ProduitRecherche();
        $formRecherche = $this->createForm(ProduitRechercheType::class, $produitRecherche);
        $formRecherche->handleRequest($request);
        if ($formRecherche->isSubmitted() && $formRecherche->isValid()) {
            $produitRecherche = $formRecherche->getData();
            // cherche les produits correspondant aux critères, triés par libellé
            // requête construite dynamiquement alors il est plus simple d'utiliser le querybuilder
            // $lesProduits =$repository->findAllByCriteria($produitRecherche);
            $session->set('ProduitsCriteres', $produitRecherche);
            $lesProduits= $paginator->paginate(
                $repository->findAllByCriteria($produitRecherche),
                $request->query->getint('page',1),
                5
            ); 
        } else {
            if ($session->has('ProduitsCriteres')){
                $produitRecherche = $session->get("ProduitsCriteres");
                // $lesProduits = $repository->findAllByCriteria($produitRecherche);
                $formRecherche = $this->createForm(ProduitRechercheType::class, $produitRecherche);
                $formRecherche->setData($produitRecherche);
                $lesProduits= $paginator->paginate(
                    $repository->findAllByCriteria($produitRecherche),
                    $request->query->getint('page',1),
                    5
                );    
            }
            else{
                $p = ProduitRecherche();
                $lesProduits = $paginator->paginate(
                    $repository->findAllByCriteria($p), 
                    $request->query->getint('page', 1), 
                    5
                );
                // $lesProduits = $repository->findAllOrderByLibelle();
            }
        }

        return $this->render('produit/index.html.twig', [
            'lesProduits' => $lesProduits,
            'formRecherche' => $formRecherche->createView(),
        ]);
    }

    /**
     * @Route("/produit/ajouter", name="produit_ajouter")
     */
    public function ajouter(Produit $produit=null, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // cas où le formulaire d'ajout a été soumis par l'utilisateur et est valide
            $produit = $form->getData();
            // on met à jour la base de données 
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le produit ' . $produit->getLibelle() . ' a été ajouté.'
            );
            return $this->redirectToRoute('produit');
        } else {
            // cas où l'utilisateur a demandé l'ajout, onaffiche le formulaire d'ajout
            return $this->render('produit/ajouter.html.twig', [
                'titre' => 'Créer un produit',
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/produit/modifier/{id<\d+>}", name="produitModifier")
     */
    public function modifier(Produit $produit = null, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // cas où le formulaire  a été soumis par l'utilisateur et est valide
            //pas besoin de "persister" l'entité : en effet, l'objet a déjà été retrouvé à partir de Doctrine ORM.
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le produit '.$produit->getLibelle().' a été modifié.'
            );

            return $this->redirectToRoute('produit');
        }
        // cas où l'utilisateur a demandé la modification, on affiche le formulaire pour la modification
        return $this->render('produit/ajouter.html.twig', [
            'titre' => 'Modifier un produit',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/produit/supprimer/{id<\d+>}", name="produitSupprimer")
     */
    public function supprimer(Produit $produit, Request $request, EntityManagerInterface $entityManager)
    {
        if ($this->isCsrfTokenValid('action-item'.$produit->getId(), $request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le produit '.$produit->getLibelle().' a été supprimé.'
            );

            return $this->redirectToRoute('produit');
        }
    }



}
