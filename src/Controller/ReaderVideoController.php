<?php

namespace App\Controller;

use App\Entity\Video;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/video')]
class ReaderVideoController extends AbstractController
{
    #[Route('/', name: 'app_reader_video_index', methods: ['GET'])]
    public function index(VideoRepository $videoRepository): Response
    {
        return $this->render('reader_video/index.html.twig', [
            'videos' => $videoRepository->createQueryBuilder('v')
                ->orderBy('v.id', 'DESC')
                ->getQuery()
                ->getResult()
        ]);
    }


    #[Route('/{id}', name: 'app_reader_video_show', methods: ['GET'])]
    public function show(Video $video): Response
    {
        if ($video->getUser() === $this->getUser()) {
            return $this->render('profile_videos/show.html.twig', [
                'video' => $video,
            ]);
        }

        return $this->render('reader_video/show.html.twig', [
            'video' => $video,
        ]);
    }
}


