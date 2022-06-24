<?php

namespace App\Controller;

use App\Form\UpdatePasswordType;
use App\Repository\UserRepository;
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
    #[Route('/update/password', name: 'app_update_password')]
    public function updatePassword(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, MailerInterface $mailer): Response
    {
        $form = $this->createForm(UpdatePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //RETRIEVE NEW PASSWORD
            $passwordDTO = $form->getData();

            //GET USER
            $session = new Session();
            $user = $session->get('user');

            //SET HASHED PASSWORD
            $user = $userRepository->find($user->getId());
            $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $passwordDTO->password
            ));

            //SEND SUCCESS MESSAGE
            $message =  (new TemplatedEmail())
                ->from(new Address('debackre.guillaume@gmail.com', 'Guillaume - Snowtricks'))
                ->to($user->getEmail())
                ->subject('Mot de passe mis à jour avec succès')
                ->htmlTemplate('security/success_update_password.html.twig');

            //SEND EMAIL
            $mailer->send($message);

            return $this->redirectToRoute('home');

        }

        return $this->render('security/update_password.html.twig', [
            'UpdatePasswordForm' => $form->createView()
        ]);
    }
}