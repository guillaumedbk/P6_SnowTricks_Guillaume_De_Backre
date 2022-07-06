<?php

namespace App\Controller;

use App\DTO\TrickDTO;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CreateTrickType;
use App\Form\ModifyTrickType;
use App\Repository\ChatRepository;
use App\Repository\ImageRepository;
use App\Repository\TricksRepository;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route(path: 'create/trick/', name: 'app_create_trick')]
    public function createTrick(Request $request, EntityManagerInterface $entityManager): Response
    {
        //RETRIEVE DATA
        $newTrickDTO = new TrickDTO();
        $form = $this->createForm(CreateTrickType::class, $newTrickDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //NEW TRICK
            $trick = new Trick($newTrickDTO->title);
            $trick->setDescription($newTrickDTO->description);
            $trick->setImageUrl($newTrickDTO->imageUrl);

            //RETRIEVE IMAGE(S)
            $images = $form->get('images')->getData();

            //UPLOAD AND ADD ALL IMAGES
            foreach ($images as $image){
                //NEW FILE NAME
                $file = md5(uniqid()) . '.' . $image->guessExtension();
                //COPY IN UPLOAD DIR
                $image->move($this->getParameter('images_directory'), $file);
                //NEW IMAGE
                $newImg = new Image($file, $trick);
                //ADD IMAGE TO THE TRICK
                $trick->addImage($newImg);
            }

            //ADD VIDEO
            $video = new Video($newTrickDTO->videoUrl, $trick);
            $trick->addVideo($video);

            //SAVE IN DB
            $entityManager->persist($trick);
            $entityManager->flush();
        }

        return $this->render('trick/create_trick.html.twig', [
            'createTrickForm' => $form->createView(),
        ]);
    }

    #[Route(path: 'modify/trick/{id}', name: 'app_modify_trick')]
    public function modifyTrick(Request $request, int $id, TricksRepository $tricksRepository, VideoRepository $videoRepository, ImageRepository $imageRepository,  EntityManagerInterface $entityManager): Response
    {
        //VALUES
        $trick = $tricksRepository->find($id);
        $video = $videoRepository->findBy(['trick' => $id]);
        $images = $imageRepository->findBy(['trick' => $id]);

        //RETRIEVE DATA
        $modifiedTrickDTO = new TrickDTO();
        $form = $this->createForm(ModifyTrickType::class, $modifiedTrickDTO, [
            'trick' => $trick
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setTitle($modifiedTrickDTO->title);
            $trick->setDescription($modifiedTrickDTO->description);
            $trick->setImageUrl($modifiedTrickDTO->imageUrl);
            //RETRIEVE IMAGE(S)
            $pictures = $form->get('images')->getData();
            //UPLOAD AND ADD ALL IMAGES
            foreach ($pictures as $image){
                //NEW FILE NAME
                $file = md5(uniqid()) . '.' . $image->guessExtension();
                //COPY IN UPLOAD DIR
                $image->move($this->getParameter('images_directory'), $file);
                //NEW IMAGE
                $newImg = new Image($file, $trick);
                //ADD IMAGE TO THE TRICK
                $trick->addImage($newImg);
            }
            //ADD VIDEO
            $video = new Video($modifiedTrickDTO->videoUrl, $trick);
            $trick->addVideo($video);

            //SAVE IN DB
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage');

        }


        return $this->render('trick/modify_trick.html.twig', [
            'modifyTrickForm' => $form->createView(),
            'trick' => $trick,
            'video' => $video,
            'pictures' => $images
        ]);
    }

    #[Route(path: 'delete/trick/{id}', name: 'app_delete_trick')]
    public function deleteTrick(TricksRepository $tricksRepository, int $id, EntityManagerInterface $manager)
    {
        $trick = $tricksRepository->find($id);

        $manager->remove($trick);
        $manager->flush();
        return $this->redirectToRoute('app_homepage');
    }
}
