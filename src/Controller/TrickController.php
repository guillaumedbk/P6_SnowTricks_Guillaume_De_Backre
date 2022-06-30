<?php

namespace App\Controller;

use App\Repository\ChatRepository;
use App\Repository\ImageRepository;
use App\Repository\TricksRepository;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    #[Route(path: '/trick/{id}', name: 'app_trick')]
    public function __invoke(TricksRepository $tricksRepository, int $id, ChatRepository $chatRepository, UserRepository $userRepository, VideoRepository $videoRepository, ImageRepository $imageRepository): Response
    {
        $trick = $tricksRepository->find($id);
        $chats = $chatRepository->findBy(
            ['trick' => $id],
            ['publishAt' => 'DESC']
        );
        $users = $userRepository->findAll();
        $videos = $videoRepository->findBy(['trick' => $id]);
        $images = $imageRepository->findBy(['trick' => $id]);

        return $this->render('trick/trick.html.twig',[
            'trick' => $trick,
            'publishAt' => $trick->getPublishAt()->format(date("d-m-Y H:i:s")),
            'chats' => $chats,
            'users' => $users,
            'id' => $id,
            'videos' => $videos,
            'pictures' => $images
        ]);
    }
}
