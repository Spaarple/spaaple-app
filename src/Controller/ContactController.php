<?php

namespace App\Controller;

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

#[Route('/contact', name: 'app_contact')]
class ContactController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param AlertServiceInterface $alertService
     * @param MailerInterface $mailer
     * @param ParameterBagInterface $parameterBag
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('/', name: '_index')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        AlertServiceInterface $alertService,
        MailerInterface $mailer,
        ParameterBagInterface $parameterBag
    ): Response {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $entityManager->persist($contact);
            $entityManager->flush();

            $emailContact = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to(new Address($parameterBag->get('mail.support'), 'Spaarple'))
                ->subject(sprintf('Demande de contact de %s', $contact->getWho()))
                ->textTemplate('contact/email.txt.twig')
                ->htmlTemplate('contact/email.html.twig')
                ->context([
                    'who' => $contact->getWho(),
                    'message' => $contact->getMessage(),
                    'contactEmail' => $contact->getEmail(),
                ]);

            $mailer->send($emailContact);

            $alertService->success('Message envoyé avec succès !');

            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
