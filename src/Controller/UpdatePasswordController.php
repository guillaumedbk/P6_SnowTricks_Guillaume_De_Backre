<?php

namespace App\Controller;

use App\DTO\PasswordDTO;
use App\Form\UpdatePasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UpdatePasswordController extends AbstractController
{
    #[Route('/update/password/{token}', name: 'app_update_password')]
    public function updatePassword(Request $request, string $token, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        //REDIRECT IF USER ALREADY CONNECTED
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }
        //RETRIEVE DATA
        $passwordDTO = new PasswordDTO();
        $form = $this->createForm(UpdatePasswordType::class, $passwordDTO);
        $form->handleRequest($request);
        //RETRIEVE USER
        $user = $userRepository->findOneBy(array('token' => $token));
        $alert = null;

        if ($form->isSubmitted() && $form->isValid()) {
            //CHECK IF USER WITH HIS TOKEN EXIST
            if (!$user) {
                $alert = 'Une erreur est survenue lors de l\'identification';
                return $this->render('security/forgotten_password.html.twig', [
                    'ResetPasswordForm' => $form->createView(),
                    'alert' => $alert
                ]);
            }
            else{
                //SET HASHED PASSWORD
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $passwordDTO->password
                    ));
                //DESTROY TOKEN
                $user->setToken(null);
                //SAVE
                $entityManager->persist($user);
                $entityManager->flush();


                //SEND SUCCESS MESSAGE
                $message =  (new TemplatedEmail())
                    ->from(new Address('debackre.guillaume@gmail.com', 'Guillaume - Snowtricks'))
                    ->to($user->getEmail())
                    ->subject('Mot de passe mis à jour avec succès')
                    ->htmlTemplate('security/success_update_password.html.twig')
                    ->textTemplate('security/success_update_password.text.twig');

                //SEND EMAIL
                $mailer->send($message);

            }
            return $this->redirectToRoute('app_homepage');

        }

        return $this->render('security/update_password.html.twig', [
            'UpdatePasswordForm' => $form->createView(),
            'alert' => $alert
        ]);
    }
}
