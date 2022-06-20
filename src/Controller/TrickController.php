<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends AbstractController
{
    public function __invoke(TricksRepository $tricksRepository, int $id): Response
    {
        $trick = $tricksRepository->find($id);

        return $this->render('trick/trick.html.twig',[
            'trick' => $trick,
        ]);
    }

}