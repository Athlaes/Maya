<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface; 
use App\Repository\ClientRepository;
use App\Entity\Client;
use App\Entity\ClientRecherche;
use App\Form\ClientType;
use App\Form\ClientRechercheType;
use Knp\Component\Pager\PaginatorInterface;

class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="clients")
     * @Route("/client/demanderModif/{id<\d+>}", name="clientDemanderModif")
     */
    public function index( $id = null, ClientRepository $repository, Request $request, SessionInterface $session, PaginatorInterface $paginator)
    {
        $clientRecherche = new ClientRecherche();
        $formRecherche = $this->createForm(ClientRechercheType::class, $clientRecherche);
        $formRecherche->handleRequest($request);

        if ($formRecherche->isSubmitted() && $formRecherche->isValid()) {
            $clientRecherche = $formRecherche->getData();
            // cherche les produits correspondant aux critères, triés par libellé
            // requête construite dynamiquement alors il est plus simple d'utiliser le querybuilder
            // $lesProduits =$repository->findAllByCriteria($produitRecherche);
            $session->set('ClientsCriteres', $clientRecherche);
            $lesClients= $paginator->paginate(
                $repository->findAllByCriteria($clientRecherche),
                $request->query->getint('page',1),
                5
            ); 
        } else {
            if ($session->has('ClientsCriteres')){
                $clientRecherche = $session->get("ClientsCriteres");
                // $lesProduits = $repository->findAllByCriteria($produitRecherche);
                $formRecherche = $this->createForm(ClientRechercheType::class, $clientRecherche);
                $formRecherche->setData($clientRecherche);
                $lesClients= $paginator->paginate(
                    $repository->findAllByCriteria($clientRecherche),
                    $request->query->getint('page',1),
                    5
                );    
            }
            else{
                $c = new ClientRecherche();
                $lesClients = $paginator->paginate(
                    $repository->findAllByCriteria($c), 
                    $request->query->getint('page', 1), 
                    5
                );
            }
        }

        $client = new Client();
        $formCreation = $this->createForm(ClientType::class, $client);
        $formModificationView = null;

        if ($id != null) {
            // sécurité supplémentaire, on vérifie le token
            if ($this->isCsrfTokenValid('action-item'.$id, $request->get('_token'))) {
                $clientModif = $repository->find($id);   // le client à modifier
                $formModificationView = $this->createForm(ClientType::class, $clientModif)->createView();
            }
        }
        return $this->render('client/index.html.twig', [
            'formCreation' => $formCreation->createView(),
            'lesClients' => $lesClients,
            'formModification' => $formModificationView,
            'idClientModif' => $id,
            'formRecherche' => $formRecherche->createView(),
        ]);
    }
    
    /**
     * @Route("/client/ajouter", name="clientAjouter")
     */
    public function clientAjouter(Client $client = null, Request $request, EntityManagerInterface $entityManager, ClientRepository $repository){
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // c'est le cas du retour du formulaire
            //         l'objet $categorie a été automatiquement "hydraté" par Doctrine
            dump($client);
            // dire à Doctrine que l'objet sera (éventuellement) persisté
            $entityManager->persist($client);
            // exécuter les requêtes (indiquées avec persist) ici il s'agit de l'ordre INSERT qui sera exécuté
            $entityManager->flush();
            // ajouter un message flash de succès pour informer l'utilisateur
            $this->addFlash(
                'success',
                'Le client ' . $client->getNom() . ' a été ajoutée.'
            );
            // rediriger vers l'affichage des catégories qui comprend le formulaire pour l"ajout d'une nouvelle catégorie
            return $this->redirectToRoute('clients');

        } else {
            return $this->redirectToRoute('clients');
        }

    }

    /**
     * @Route("/client/Modifier/{id<\d+>}", name="clientModifier")
     */
    public function Modifier(Client $client = null, $id = null, Request $request, EntityManagerInterface $entityManager, ClientRepository $repository){
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // va effectuer la requête d'UPDATE en base de données
            // pas besoin de "persister" l'entité car l'objet a déjà été retrouvé à partir de Doctrine ORM.
            $entityManager->flush();
            $this->addFlash(
                'success',
                'La client '.$client->getNom().' a été modifiée.'
            );
            // rediriger vers l'affichage des catégories qui comprend le formulaire pour l"ajout d'une nouvelle catégorie
            return $this->redirectToRoute('clients');

        } else {
            return $this->redirectToRoute('clients');
        }
    }

    /**
     * @Route("/client/supprimer/{id<\d+>}", name="clientSupprimer")
     */
    public function Supprimer(Client $client = null, $id = null, Request $request, EntityManagerInterface $entityManager){
        // vérifier le token
        if ($this->isCsrfTokenValid('action-item'.$client->getId(), $request->get('_token'))) {
            // supprimer le client
            $entityManager->remove($client);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le client du nom' . $client->getNom() . ' a été supprimée.'
            );
        }
        return $this->redirectToRoute('clients');
    }
}
