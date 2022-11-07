<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'articles' => $articlesRepository->createQueryBuilder('a')
                ->orderBy('a.id', 'DESC')
                ->setMaxResults(6)
                ->getQuery()
                ->getResult()
        ]);
    }
}
