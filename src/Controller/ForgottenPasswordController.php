<?php

namespace App\Controller;

use App\DTO\EmailDTO;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ResetPasswordType;

class ForgottenPasswordController extends AbstractController
{
    #[Route('/forgotten/password', name: 'app_forgotten_password')]
    public function resetPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        //RETRIEVE DATA IN THE DTO
        $email = new EmailDTO();
        $form = $this->createForm(ResetPasswordType::class, $email);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $EmailDataDTO = $form->getData();

            $user = $userRepository->findOneBy(array('email' => $EmailDataDTO->email));
            $session = new Session();
            $session->set('user', $user);

            //SEND URL TO THE USER
            $message =  (new TemplatedEmail())
                ->from(new Address('debackre.guillaume@gmail.com', 'Guillaume - Snowtricks'))
                ->to($EmailDataDTO->email)
                ->subject('Réinitialisation du mot de passe')
                ->htmlTemplate('security/forgotten_password_email.html.twig');

            //SEND EMAIL
            $mailer->send($message);
        }

        return $this->render('security/forgotten_password.html.twig', [
            'ResetPasswordForm' => $form->createView()
        ]);
    }
}