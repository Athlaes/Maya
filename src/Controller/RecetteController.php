<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Entity\Recette;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RecetteRepository;


class RecetteController extends AbstractController
{
    /**
     * @Route("/recettes", name="recettes")
     */
    public function index()
    {
        // $recettes_LC = $this->getDoctrine()
        // ->getRepository(Recette::class)
        // ->findAll();
        $recettesMinTwo_LC = $this->getDoctrine()
        ->getRepository(Recette::class)
        ->findAllRecetteMinTwo();
        // $recettesMinTwo_LC = Array();
        // foreach($recettes_LC as $recette){
        //     $nbProduit_LC = 0;
        //     $produits_LC = $recette->getProduits();
        //     foreach($produits_LC as $produit){
        //         $nbProduit_LC++;
        //     }
        //     if($nbProduit_LC > 2){
        //         $recettesMinTwo_LC[] = [ 'recette' => $recette,
        //                                  'nbProduits' => $nbProduits_LC
        //         ];
        //     }
        // }
        return $this->render('recette/index.html.twig', [
            'lesRecettes' => $recettesMinTwo_LC,
        ]);
    }

    /**
     * @Route("/recette/creer", name="recette_creer")
     */
    public function creerRecette(EntityManagerInterface $entityManager): Response
    {
        // : Response        type de retour de la méthode creerRecette
        // pour récupérer le EntityManager
        //     on peut ajouter l'argument à la méthode comme ici  creerRecette(EntityManagerInterface $entityManager)
        //     ou on peut récupérer le EntityManager via $this->getDoctrine() comme ci-dessus en commentaire
        //        $entityManager = $this->getDoctrine()->getManager();

        // créer l'objet Recette
        $recette = new Recette();
        $recette->setNom('ratatouille');

        // chercher l'id du produit 'aubergine' et l'ajouter à la collection de produits de la recette
        $produit = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->findOneBy(['libelle' => 'aubergine']);
        $recette->addProduit($produit);

        // chercher l'id du produit 'courgette' et l'ajouter à la collection de produits de la recette
        $produit = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->findOneBy(['libelle' => 'courgette']);
        $recette->addProduit($produit);

        // dire à Doctrine que l'objet sera (éventuellement) persisté
        $entityManager->persist($recette);

        // exécuter les requêtes (indiquées avec persist) ici il s'agit d'ordres INSERT qui seront exécutés
        $entityManager->flush();

        return new Response('Nouvelle recette enregistrée avec 2 produits, son id est : '.$recette->getId());
    }


}
