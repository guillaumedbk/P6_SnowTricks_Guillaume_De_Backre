<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'app_homepage')]
    public function __invoke(Request $request, TricksRepository $tricksRepository, ImageRepository $imageRepository): Response
    {
        $trickLoad = (int)$request->query->get("trickLoad", 10);
        $allTricks = $tricksRepository->findAll();
        $total = count($allTricks);
        $tricks = $tricksRepository->findBy([], [], $trickLoad);
        $user = $this->getUser();

        return $this->render('home/index.html.twig',[
            'total' => $total,
            'trickLoad' => $trickLoad,
            'tricks' => $tricks,
            'user' => $user,
        ]);
    }
}
