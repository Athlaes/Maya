<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Fournisseur;
use App\Repository\FournisseurRepository;
use App\Form\FournisseurType;

class FournisseurController extends AbstractController
{
    /**
     * @Route("/fournisseur", name="fournisseur")
     * @Route("/fournisseur/demanderModif/{id<\d+>}", name="fournisseurDemanderModif")
     */
    public function index( $id = null, FournisseurRepository $repository, Request $request)
    { 
        $fournisseur = new Fournisseur();
        $formCreation = $this->createForm(FournisseurType::class, $fournisseur);

        $formModificationView = null;

        if ($id != null) {
            // sécurité supplémentaire, on vérifie le token
            if ($this->isCsrfTokenValid('action-item'.$id, $request->get('_token'))) {
                $fournisseurModif = $repository->find($id);   // le fournisseur à modifier
                $formModificationView = $this->createForm(FournisseurType::class, $fournisseurModif)->createView();
            }
        }

        $lesFournisseurs = $repository->findAll();
        return $this->render('fournisseur/index.html.twig', [
            'lesFournisseurs' => $lesFournisseurs,
            'formCreation' => $formCreation->createView(),
            'formModification' => $formModificationView,
            'idFournisseurModif' => $id,
        ]);
    }

    /**
     * @Route("/fournisseur/ajouter", name="fournisseurAjouter")
     */
    public function fournisseurAjouter(Fournisseur $fournisseur = null, Request $request, EntityManagerInterface $entityManager, FournisseurRepository $repository){
        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // c'est le cas du retour du formulaire
            //         l'objet $categorie a été automatiquement "hydraté" par Doctrine
            dump($fournisseur);
            // dire à Doctrine que l'objet sera (éventuellement) persisté
            $entityManager->persist($fournisseur);
            // exécuter les requêtes (indiquées avec persist) ici il s'agit de l'ordre INSERT qui sera exécuté
            $entityManager->flush();
            // ajouter un message flash de succès pour informer l'utilisateur
            $this->addFlash(
                'success',
                'Le fournisseur ' . $fournisseur->getNom() . ' a été ajoutée.'
            );
        } 
        // rediriger vers l'affichage des catégories qui comprend le formulaire pour l"ajout d'une nouvelle catégorie
        return $this->redirectToRoute('fournisseur');
    }

    /**
     * @Route("/fournisseur/modifier/{id<\d+>}", name="fournisseurModifier")
     */
    public function Modifier(Fournisseur $fournisseur = null, $id = null, Request $request, EntityManagerInterface $entityManager, FournisseurRepository $repository){
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // va effectuer la requête d'UPDATE en base de données
            // pas besoin de "persister" l'entité car l'objet a déjà été retrouvé à partir de Doctrine ORM.
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le fournisseur '.$fournisseur->getNom().' a été modifiée.'
            );
            // rediriger vers l'affichage des catégories qui comprend le formulaire pour l"ajout d'une nouvelle catégorie
            return $this->redirectToRoute('fournisseur');

        } else {
            return $this->redirectToRoute('fournisseur');
        }
    }

    /**
     * @Route("/fournisseur/supprimer/{id<\d+>}", name="fournisseurSupprimer")
     */
    public function Supprimer(Fournisseur $fournisseur = null, $id = null, Request $request, EntityManagerInterface $entityManager){
        // vérifier le token
        if ($this->isCsrfTokenValid('action-item'.$fournisseur->getId(), $request->get('_token'))) {
            // supprimer le fournisseur
            $entityManager->remove($fournisseur);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le fournisseur du nom' . $fournisseur->getNom() . ' a été supprimée.'
            );
        }
        return $this->redirectToRoute('fournisseur');
    }

}
