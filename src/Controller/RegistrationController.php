<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        //RETRIEVE DATA IN THE DTO
        $user = new UserDTO();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userDataDTO = $form->getData();
            //NEW USER
            $newUser = new User($userDataDTO->firstname, $userDataDTO->name, $userDataDTO->email, $userDataDTO->password);
            //SET HASHED PASSWORD
            $newUser->setPassword(
                $userPasswordHasher->hashPassword(
                    $newUser,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($newUser);
            $entityManager->flush();

            //SEND URL TO THE USER TO CONFIRM EMAIL
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $newUser,
                (new TemplatedEmail())
                    ->from(new Address('debackre.guillaume@gmail.com', 'Guillaume - Snowtricks'))
                    ->to($newUser->getEmail())
                    ->subject('Veuillez confirmer votre adresse mail')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                    ->textTemplate('registration/confirmation_email.text.twig')
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator)//: Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        //VALIDATE EMAIL CONFIRMATION LINK, , sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $userAuthenticator->authenticateUser(
            $user,
            $authenticator,
            $request
        );

        return $this->redirectToRoute('home');
    }
}
