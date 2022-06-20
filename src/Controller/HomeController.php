<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Twig\Environment;

class HomeController extends AbstractController
{
    public function __invoke(TricksRepository $tricksRepository): Response
    {
        $tricks = $tricksRepository->findAll();
        $user = $this->getUser();

        return $this->render('home/index.html.twig',[
            'tricks' => $tricks,
            'user' => $user
        ]);
    }
}
