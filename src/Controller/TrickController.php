<?php

namespace App\Controller;

use App\Repository\ChatRepository;
use App\Repository\TricksRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends AbstractController
{
    public function __invoke(TricksRepository $tricksRepository, int $id, ChatRepository $chatRepository, UserRepository $userRepository): Response
    {
        $trick = $tricksRepository->find($id);
        $chats = $chatRepository->findBy(
            ['trick' => $id]
        );
        $users = $userRepository->findAll();

        return $this->render('trick/trick.html.twig',[
            'trick' => $trick,
            'chats' => $chats,
            'users' => $users,
            'id' => $id
        ]);
    }

}