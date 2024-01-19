<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        // Si l'utilisateur est déjà connecté, on le redirige vers la page account
        if ($this->getUser()) {
            return $this->redirectToRoute('app_account');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setUsername($form->get('username')->getData());
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            if ($form->get('status')->getData() === 'Entreprise') {
                $user->setRoles(['ROLE_PRO']);
            }

            //$user->setCreatedAt(new \DateTimeImmutable('now'));

            $entityManager->persist($user);
            $entityManager->flush();

            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->dismissible(true)
                ->addSuccess('Votre compte a bien été créé. Un mail de confirmation vous a été envoyé afin de valider votre compte.');

            // generate a signed url and email it to the user
            // Pour l'envoi de mail, utiliser mailtrap.io et configurer le .env. Puis aller dans le dossier config/packages/messenger.yaml et commenter la ligne dans le routing            #Symfony\Component\Mailer\Messenger\SendEmailMessage: async

            /*    $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('romyklk2210@gmail.com', 'SYM JOB BOARD'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('emails/confirmation_email.html.twig')
            );  */
            // do anything else you need here, like send an email

            /*             $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');

            $mailer->send($email); 
        */

            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address($user->getEmail(), $user->getUsername()))
                    ->to('romyklk2210@gmail.com')
                    ->subject('Mail de confirmation de compte')
                    ->htmlTemplate('emails/confirmation_email.html.twig')
                    // Passage des variables à la vue twig pour le mail
                    ->context([
                        'user' => $user->getUsername(),
                    ])
            );


            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->dismissible(true)
            ->addSuccess('Votre email a bien été vérifié.');

        return $this->redirectToRoute('app_account');
    }
}
