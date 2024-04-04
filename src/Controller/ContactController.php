<?php

namespace App\Controller;

use App\Entity\User\UserClient;
use App\Form\ContactType;
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
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/contact', name: 'app_contact')]
class ContactController extends AbstractController
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
    )
    {
    }

    /**
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('/', name: '_index')]
    public function index(Request $request,): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $emailContact = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to(new Address($this->parameterBag->get('mail.support'), 'Spaarple'))
                ->subject(sprintf('Demande de contact de %s', $contact->getWho()))
                ->textTemplate('contact/email.txt.twig')
                ->htmlTemplate('contact/email.html.twig')
                ->context([
                    'who' => $contact->getWho(),
                    'message' => $contact->getMessage(),
                    'contactEmail' => $contact->getEmail(),
                ]);

            $this->mailer->send($emailContact);

            $this->alertService->success('Message envoyé avec succès !');

            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param UserClient $userClient
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('/profile', name: '_profile_index')]
    public function profileContact(Request $request, #[CurrentUser] UserClient $userClient,): Response
    {
        $form = $this->createForm(ContactType::class, null, [
            'user_connected' => $userClient !== null,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $contact->setEmail($userClient->getEmail());
            $contact->setWho(sprintf('%s %s', $userClient->getFirstName(), $userClient->getLastName()));

            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $emailContact = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to(new Address($this->parameterBag->get('mail.support'), 'Spaarple'))
                ->subject(sprintf('Demande de contact de %s', $contact->getWho()))
                ->textTemplate('contact/email.txt.twig')
                ->htmlTemplate('contact/email.html.twig')
                ->context([
                    'who' => $contact->getWho(),
                    'message' => $contact->getMessage(),
                    'contactEmail' => $contact->getEmail(),
                ]);

            $this->mailer->send($emailContact);

            $this->alertService->success('Message envoyé avec succès !');

            return $this->redirectToRoute('app_profile_index');
        }

        return $this->render('profile/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
