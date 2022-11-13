<?php

namespace App\Controller;

use App\Entity\Video;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/videos')]
class AdminVideosController extends AbstractController
{
    #[Route('/', name: 'app_admin_videos_index', methods: ['GET'])]
    public function index(VideoRepository $videoRepository): Response
    {
        return $this->render('admin_videos/index.html.twig', [
            'videos' => $videoRepository->createQueryBuilder('v')
                ->orderBy('v.id', 'DESC')
                ->getQuery()
                ->getResult()
        ]);
    }

    #[Route('/{id}', name: 'app_admin_videos_show', methods: ['GET'])]
    public function show(Video $video): Response
    {
        return $this->render('admin_videos/show.html.twig', [
            'video' => $video,
        ]);
    }


    #[Route('/{id}', name: 'app_admin_videos_delete', methods: ['POST'])]
    public function delete(Request $request, Video $video, VideoRepository $videoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$video->getId(), $request->request->get('_token'))) {
            $videoRepository->remove($video, true);
        }

        return $this->redirectToRoute('app_admin_videos_index', [], Response::HTTP_SEE_OTHER);
    }
}
