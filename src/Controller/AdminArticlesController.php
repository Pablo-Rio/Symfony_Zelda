<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/articles')]
class AdminArticlesController extends AbstractController
{
    #[Route('/', name: 'app_admin_articles_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('admin_articles/index.html.twig', [
            'articles' => $articlesRepository->createQueryBuilder('a')
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->getResult()
        ]);
    }

    #[Route('/{id}', name: 'app_admin_articles_show', methods: ['GET'])]
    public function show(Articles $article): Response
    {
        return $this->render('admin_articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_articles_delete', methods: ['POST'])]
    public function delete(Request $request, Articles $article, ArticlesRepository $articlesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articlesRepository->remove($article, true);
        }

        return $this->redirectToRoute('app_admin_articles_index', [], Response::HTTP_SEE_OTHER);
    }
}

/*
                    &.@.
                    &%(.
                    &&(.
                    &&%.
                    &((.
               *#.*@/%#@. @.
             .@  ( @ &.&,,  @.
                   /.,**
         @@@@@@@@@@@ # @@@@@@@@@@@*
       .@@@@.    ..% # @.*@@@%@@,
       @@,       ..@...@@@@ @@@   ,@@@@@@@@@@@@ (@@@@@%     (@@@@@@@@#(.     @@@@@%
                 ..@&.@@&./@@,    .(@@@@.  .@@@  @@@@@      .&@@@&. @@@@(    %@@@@@,.
                 ..@#@@@.@@&      .&@@@@   (.#@  @@@@@      .&@@@@   @@@@(   @@@@@@@.
                 ..@@@@@@@ ..*    .&@@@@&@@@.    @@@@@      .&@@@@  .&@@@@..@@@*@@@@#
               . @@@/&@@,,@@@@@*  .(@@@@ .@@..(  *@@@@    ,*.%@@@@  .#@@@@.#@@( (@@@@
               %@& @@@%@  .#%#  //,%@@@@   . @@  @@@@@   (@ .&@@@@   @@@@(%@@&%%%@@@@@.
             .@@ .@@@ .@    *@    .&@@@@   .@@(  @@@@@ .#@@*.&@@@@. @@@@#*@@%    @@@@@#
           .@@@ @@@/#.@(   ,@(    @@@@@@@@@@@@@ /@@@@@@@@@@**@@@@@@@@&( &@@@%    #@@@@@
         .#@@/@@@ .@..(@  (.   @@@&,                                                       ,
        .@@@@@@@@@@@...@@@@@@@@@@@   @ &@@@ @@ % @ @@@@ @ @@*@ @ @   @ ( @,,@ @ @@@ @@&@%@@,
                   #/..&                    @@ @ @@@ @@ @ @@@  @     @ % @ ,@.@@@ @ @@&@* @,
                 ..@...@
                 ..@ ..@
                   *(.*,
                    &.(.
                     *
*/
