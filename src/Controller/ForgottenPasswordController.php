<?php

namespace App\Controller;

use App\DTO\EmailDTO;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ResetPasswordType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForgottenPasswordController extends AbstractController
{
    #[Route('/forgotten/password/', name: 'app_forgotten_password')]
    public function resetPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        //REDIRECT IF USER ALREADY CONNECTED
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }
        //RETRIEVE DATA IN THE DTO
        $emailDTO = new EmailDTO();
        $form = $this->createForm(ResetPasswordType::class, $emailDTO);
        $form->handleRequest($request);
        //CHECK IF USER EXIST
        $alert = null;
        $user = $userRepository->findOneBy(array('email' => $emailDTO->email));

        if ($form->isSubmitted() && $form->isValid()) {
            //RENDER TEMPLATE WITH THE ERROR IF NOT EXIST
            if (!$user){
                $alert = 'Identifiant inconnu, veuillez vous créer un compte:';
                return $this->render('security/forgotten_password.html.twig', [
                    'ResetPasswordForm' => $form->createView(),
                    'alert' => $alert
                ]);
            }
            else{
                //CREATE TOKEN AND SAVE IT
                $token = md5(uniqid());
                $user->setToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                //ABSOLUTE URL WITH TOKEN
                $url = $this->generateUrl('app_update_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

                //SEND URL TO THE USER
                $message =  (new TemplatedEmail())
                    ->from(new Address('debackre.guillaume@gmail.com', 'Guillaume - Snowtricks'))
                    ->to($emailDTO->email)
                    ->subject('Réinitialisation du mot de passe')
                    ->htmlTemplate('security/forgotten_password_email.html.twig')
                    ->textTemplate('security/forgotten_password_email.text.twig')
                    ->context([
                        'url' => $url
                    ]);

                //SEND EMAIL
                $mailer->send($message);
            }
        }

        return $this->render('security/forgotten_password.html.twig', [
            'ResetPasswordForm' => $form->createView(),
            'alert' => $alert
        ]);
    }
}
