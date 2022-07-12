<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'app_homepage')]
    public function __invoke(TricksRepository $tricksRepository, ImageRepository $imageRepository): Response
    {
        $tricks = $tricksRepository->findAll();
        $user = $this->getUser();

        return $this->render('home/index.html.twig',[
            'tricks' => $tricks,
            'user' => $user,
        ]);
    }
}
