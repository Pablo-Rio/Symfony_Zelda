<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile/videos')]
class ProfileVideosController extends AbstractController
{
    #[Route('/', name: 'app_profile_videos_index', methods: ['GET'])]
    public function index(VideoRepository $videoRepository): Response
    {
        return $this->render('profile_videos/index.html.twig', [
            'videos' => $videoRepository->findBy(['user'=>$this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_profile_videos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VideoRepository $videoRepository): Response
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);
        $video->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {

            $url = $form->get('url')->getData();
            $website = substr($url, 8, strpos($url, '/', 8)-8);

            if (($website == 'clips.twitch.tv' or $website == 'www.twitch.tv') && strpos($url, 'clip') !== false) {
                $url = substr($url, strrpos($url, '/') + 1);
                $url = 'https://clips.twitch.tv/embed?clip='.$url.'&parent=streamernews.example.com&parent=embed.example.com';

            } elseif ($website == 'www.youtube.com' && strpos($url, 'watch') !== false) {
                $tmp = $url;
                $url = substr($url, strpos($url, '=') + 1, strpos($url, '&')- strpos($url, '=') - 1);
                if ($url == '') {
                    $url = substr($tmp, strpos($tmp, '=') + 1);
                }
                $url = 'https://www.youtube.com/embed/'.$url;

            } elseif ($website == 'youtu.be') {
                $url = substr($url, strrpos($url, '/') + 1);
                $url = 'https://www.youtube.com/embed/'.$url;

            } else {
                $this->addFlash('invalid', 'Lien invalide.');
                return $this->redirectToRoute('app_profile_videos_new');
            }

            $video->setUrl($url);
            $videoRepository->save($video, true);

            return $this->redirectToRoute('app_profile_videos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile_videos/new.html.twig', [
            'video' => $video,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_profile_videos_show', methods: ['GET'])]
    public function show(Video $video): Response
    {

        if ($video->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette vidéo.');
        } else {
            return $this->render('profile_videos/show.html.twig', [
                'video' => $video,
            ]);
        }
    }

    #[Route('/{id}', name: 'app_profile_videos_delete', methods: ['POST'])]
    public function delete(Request $request, Video $video, VideoRepository $videoRepository): Response
    {
        if ($video->getUser() === $this->getUser()) {
            if ($this->isCsrfTokenValid('delete'.$video->getId(), $request->request->get('_token'))) {
                $videoRepository->remove($video, true);
            }
        }

        return $this->redirectToRoute('app_profile_videos_index', [], Response::HTTP_SEE_OTHER);
    }
}
