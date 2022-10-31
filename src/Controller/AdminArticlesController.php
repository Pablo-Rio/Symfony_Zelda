<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\User;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use App\Service\FileUploader;
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
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_articles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FileUploader $fileUploader, ArticlesRepository $articlesRepository): Response
    {
        $article = new Articles(null, null, null);

        $article->setUser($this->getUser());

        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $article->setImageFile($imageFileName);
            }

            $articlesRepository->save($article, true);
            $this->addFlash('success', 'Article créé avec succès !');
            return $this->redirectToRoute('app_admin_articles_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('admin_articles/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_articles_show', methods: ['GET'])]
    public function show(Articles $article): Response
    {
        return $this->render('admin_articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_articles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FileUploader $fileUploader, Articles $article, ArticlesRepository $articlesRepository): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $article->setImageFile($imageFileName);
            }

            $articlesRepository->save($article, true);

            return $this->redirectToRoute('app_admin_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_articles/edit.html.twig', [
            'article' => $article,
            'form' => $form,
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
