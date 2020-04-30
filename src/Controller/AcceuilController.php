<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use DateTime;
use DateTimeZone;

class AcceuilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(CategorieRepository $repository, PaginatorInterface $paginator, Request $request){
        $lesCategories = $paginator->paginate(
            $repository->findAllQuery(),
            $request->query->getint('page', 1), 
            25
        );

        $cleAPI = "a8c5240620e1ffffd5eb22d921d7b626";
        $ville = "metz";
        // Définir l'url
        $urlAPI = "http://api.openweathermap.org/data/2.5/weather?q=" . $ville . "&lang=fr&units=metric&appid=" . $cleAPI;
        // Initialiser une session CURL
        $clientURL = curl_init();
        // Récupérer le contenu de la page
        curl_setopt($clientURL, CURLOPT_RETURNTRANSFER, 1);
        // Transmettre l'URL
        curl_setopt($clientURL, CURLOPT_URL, $urlAPI);
        // Exécutez la requête HTTP
        $reponse = curl_exec($clientURL);
        // Fermer la session
        curl_close($clientURL);
        // Récupérer les données au format JSON
        $donnees = json_decode($reponse);
        $dateJour = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $dateJour = date_format($dateJour, 'd/m/Y à H\hi');

        return $this->render('Acceuil/index.html.twig', [
            'lesCategories' => $lesCategories,
            'donnees' => $donnees,
            'dateJour' => $dateJour,
            'description' => ucfirst($donnees->weather[0]->description),
            'icon' => $donnees->weather[0]->icon,
            'tempsMax' => $donnees->main->temp_max,
            'tempsMin' => $donnees->main->temp_min,
            'humidite' => $donnees->main->humidity,
            'windSpeed' => $donnees->wind->speed,
        ]);
    }


}