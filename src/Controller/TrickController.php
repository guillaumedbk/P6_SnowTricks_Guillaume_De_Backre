<?php

namespace App\Controller;

use App\DTO\ChatDTO;
use App\DTO\TrickDTO;
use App\Entity\Chat;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\ChatType;
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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    #[Route(path: '/trick/{slug}', name: 'app_trick')]
    public function __invoke(Request $request, TricksRepository $tricksRepository, string $slug, ChatRepository $chatRepository, UserRepository $userRepository, VideoRepository $videoRepository, ImageRepository $imageRepository, EntityManagerInterface $entityManager): Response
    {
        //TRICK
        $trick = $tricksRepository->findOneBy(['slug' => $slug]);

        //Number of comment per pages
        $limit = 2;
        $page = (int)$request->query->get("page", 0);
        $offset = (($page * $limit)-$page);
        $chats = $chatRepository->findBy(['trick' => $trick], ['publishAt' => 'desc'], $limit, $offset);
        $allChats = $chatRepository->findBy(['trick' => $trick]);
        //Number of chats
        $numberOfChats = count($allChats);

        //RETRIEVE DATA FOR CHAT
        $newChatDTO = new ChatDTO();
        $form = $this->createForm(ChatType::class, $newChatDTO);
        $form->handleRequest($request);

        //USER
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            //NEW CHAT
            $chat = new Chat($newChatDTO->content, $trick, $user);
            //SAVE IN DB
            $entityManager->persist($chat);
            $entityManager->flush();
            return $this->redirectToRoute('app_trick', ['slug' => $trick->getSlug()]);
        }
        return $this->render('trick/trick.html.twig',[
            'trick' => $trick,
            'chats' => $chats,
            'total' => $numberOfChats,
            'limit' => $limit,
            'page' => $page,
            'publishAt' => $trick->getPublishAt()->format(date("d-m-Y H:i:s")),
            'lastModified' => $trick->getLastModified(),
            'id' => $trick->getId(),
            'createChatForm' => $form->createView()
        ]);
    }

    #[Route(path: 'create/trick/', name: 'app_create_trick')]
    public function createTrick(Request $request, EntityManagerInterface $entityManager, ImageFileManager $imageFileManager): Response
    {
        //REDIRECT IF USER IS NOT CONNECTED
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
        //RETRIEVE DATA
        $newTrickDTO = new TrickDTO();
        $form = $this->createForm(CreateTrickType::class, $newTrickDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //NEW TRICK
            $trick = new Trick($newTrickDTO->title);
            $trick->setDescription($newTrickDTO->description);
            $trick->setSlug();

            //RETRIEVE IMAGE(S)
            if($newTrickDTO->images !== null){
                $images = $newTrickDTO->images->file;
                //UPLOAD MANAGER
                if($images){
                    $imageFileManager->uploadFile($images, $trick);
                    //ADD MAIN IMAGE
                    $trick->setMainImageWithFirstImage();
                }
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
            try{
                $entityManager->flush();
            }catch(\Exception $exception){
                $this->addFlash('error','Probl??me lors de l\'enregistrement en base de donn??es !');
                return $this->render('trick/create_trick.html.twig', [
                    'createTrickForm' => $form->createView(),
                ]);
            }

            $this->addFlash('success','Trick cr???? avec succ??s !');

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('trick/create_trick.html.twig', [
            'createTrickForm' => $form->createView(),
        ]);
    }

    #[Route(path: 'modify/trick/{slug}', name: 'app_modify_trick')]
    public function modifyTrick(Request $request, string $slug, TricksRepository $tricksRepository, EntityManagerInterface $entityManager, ImageFileManager $imageFileManager): Response
    {
        //REDIRECT IF USER IS NOT CONNECTED
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
        //VALUES
        $trick = $tricksRepository->findOneBy(['slug' => $slug]);
        if ($trick === null) {
            throw new NotFoundHttpException("Not found", null);
        }
        //RETRIEVE DATA
        $modifiedTrickDTO = new TrickDTO();
        $form = $this->createForm(ModifyTrickType::class, $modifiedTrickDTO, [
            'trick' => $trick
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setTitle($modifiedTrickDTO->title);
            $trick->setDescription($modifiedTrickDTO->description);
            $trick->setLastModified(new \DateTime('now', new \DateTimeZone('Europe/Paris')));

            //RETRIEVE IMAGE(S)
            if($modifiedTrickDTO->images){
                $images = $modifiedTrickDTO->images->file;

                //UPLOAD MANAGER
                if($images){
                    $imageFileManager->uploadFile($images, $trick);
                    //ADD MAIN IMAGE
                    $trick->setMainImageWithFirstImage();
                }
                $deletedImages = $modifiedTrickDTO->images->deletedImages;
                $deletedElement = explode(';', $deletedImages);

                foreach ($trick->getImages() as $image){
                    if(in_array((string)$image->getId(), $deletedElement)){
                        $trick->removeImage($image);
                    }
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

            $this->addFlash('success','Trick modifi?? avec succ??s !');

            return $this->redirectToRoute('app_homepage');

        }

        return $this->render('trick/modify_trick.html.twig', [
            'modifyTrickForm' => $form->createView(),
            'trick' => $trick
        ]);
    }

    #[Route(path: 'delete/trick/{slug}', name: 'app_delete_trick')]
    public function deleteTrick(TricksRepository $tricksRepository, string $slug, EntityManagerInterface $manager)
    {
        //REDIRECT IF USER IS NOT CONNECTED
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $trick = $tricksRepository->findOneBy(['slug' => $slug]);
        if ($trick === null) {
            throw new NotFoundHttpException("Not found", null);
        }

        $manager->remove($trick);
        $manager->flush();

        $this->addFlash('success','Trick supprim?? avec succ??s !');

        return $this->redirectToRoute('app_homepage');
    }
}
