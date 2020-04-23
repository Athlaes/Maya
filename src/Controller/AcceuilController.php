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

class AcceuilController extends AbstractController
{
    /**
     * @Route("/", name="acceuil")
     */
    public function index(CategorieRepository $repository, PaginatorInterface $paginator, Request $request){
        $lesCategories = $paginator->paginate(
            $repository->findAllQuery(),
            $request->query->getint('page', 1), 
            25
        );

        return $this->render('Acceuil/index.html.twig', [
            'lesCategories' => $lesCategories,
        ]);
    }
}