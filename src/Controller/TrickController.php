<?php

namespace App\Controller;

use App\DTO\TrickDTO;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CreateTrickType;
use App\Form\ModifyTrickType;
use App\Manager\ImageFileManager;
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

        return $this->render('trick/trick.html.twig',[
            'trick' => $trick,
            'publishAt' => $trick->getPublishAt()->format(date("d-m-Y H:i:s")),
            'chats' => $chats,
            'id' => $id,
        ]);
    }

    #[Route(path: 'create/trick/', name: 'app_create_trick')]
    public function createTrick(Request $request, EntityManagerInterface $entityManager, ImageFileManager $imageFileManager): Response
    {
        //RETRIEVE DATA
        $newTrickDTO = new TrickDTO();
        $form = $this->createForm(CreateTrickType::class, $newTrickDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //NEW TRICK
            $trick = new Trick($newTrickDTO->title);
            $trick->setDescription($newTrickDTO->description);

            //RETRIEVE IMAGE(S)
            $images = $newTrickDTO->images->file;

            //UPLOAD MANAGER
            if($images){
                $imageFileManager->uploadFile($images, $trick);
                //ADD MAIN IMAGE
                $trick->setMainImageWithFirstImage();
            }

            //ADD VIDEO
            foreach ($newTrickDTO->videoUrl as $item){
                if($item->url){
                    $video = new Video($item->url, $trick);
                    $trick->addVideo($video);
                }
            }

            //SAVE IN DB
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('trick/create_trick.html.twig', [
            'createTrickForm' => $form->createView(),
        ]);
    }

    #[Route(path: 'modify/trick/{id}', name: 'app_modify_trick')]
    public function modifyTrick(Request $request, int $id, TricksRepository $tricksRepository, VideoRepository $videoRepository, ImageRepository $imageRepository,  EntityManagerInterface $entityManager, ImageFileManager $imageFileManager): Response
    {
        //VALUES
        $trick = $tricksRepository->find($id);

        //RETRIEVE DATA
        $modifiedTrickDTO = new TrickDTO();
        $form = $this->createForm(ModifyTrickType::class, $modifiedTrickDTO, [
            'trick' => $trick
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setTitle($modifiedTrickDTO->title);
            $trick->setDescription($modifiedTrickDTO->description);

            //RETRIEVE IMAGE(S)
            if($modifiedTrickDTO->images){
                $images = $modifiedTrickDTO->images->file;

                //UPLOAD MANAGER
                if($images){
                    $imageFileManager->uploadFile($images, $trick);
                    //ADD MAIN IMAGE
                    $trick->setMainImageWithFirstImage();
                }
            }

            //ADD VIDEO
            foreach ($modifiedTrickDTO->videoUrl as $item){
                if($item->url){
                    $video = new Video($item->url, $trick);
                    $trick->addVideo($video);
                }
            }

            //SAVE IN DB
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage');

        }


        return $this->render('trick/modify_trick.html.twig', [
            'modifyTrickForm' => $form->createView(),
            'trick' => $trick,
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
