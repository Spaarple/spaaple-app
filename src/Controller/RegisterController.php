<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User\AbstractUser;
use App\Entity\User\UserClient;
use App\Form\RegisterType;
use App\Repository\User\AbstractUserRepository;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/register', name: 'app_register')]
class RegisterController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param AlertServiceInterface $alertService
     * @param MailerInterface $mailer
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AlertServiceInterface $alertService,
        private readonly MailerInterface $mailer,
        private readonly ParameterBagInterface $parameterBag,
    ) {
    }

    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('/', name: '_index')]
    public function registerByRole(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new UserClient();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->alertService->success('Vous êtes bien inscrit ! Vérifier votre boîte mail.');

            $this->sendConfirmationEmail($user);

            return $this->redirectToRoute('app_login');
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param AbstractUser $user
     * @return void
     * @throws TransportExceptionInterface
     */
    private function sendConfirmationEmail(AbstractUser $user): void
    {
         $emailRegisterVerified = (new TemplatedEmail())
            ->from(new Address($this->parameterBag->get('mail.support'), 'Spaarple'))
            ->to($user->getEmail())
            ->subject('Confirmer votre compte')
             ->textTemplate('register/email/email.txt.twig')
             ->htmlTemplate('register/email/email.html.twig')
             ->context([
                'user' => $user,
            ]);

       $this->mailer->send($emailRegisterVerified);
    }

    /**
     * @param Request $request
     * @param AbstractUserRepository $abstractUserRepository
     * @return Response
     */
    #[Route('/confirm_account/{id}', name: '_confirm_account')]
    public function confirmAccount(Request $request, AbstractUserRepository $abstractUserRepository): Response
    {
        $client = $abstractUserRepository->find(['id' => $request->get('id')]);

        if ($client === null) {
            $this->alertService->error('Utilisateur non trouvé');

            return $this->redirectToRoute('app_register');
        }

        $client?->setIsVerified(true);
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $this->alertService->success('Confirmé ! Connectez-vous.');

        return $this->render('register/confirm_account.html.twig');
    }
}
