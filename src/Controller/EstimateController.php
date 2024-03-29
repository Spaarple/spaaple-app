<?php

namespace App\Controller;

use App\Entity\User\UserClient;
use App\Form\EstimateYoursSiteType;
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

#[Route('/estimate', name: 'app_estimate')]
class EstimateController extends AbstractController
{
    /**
     * @param AlertServiceInterface $alertService
     */
    public function __construct(private readonly AlertServiceInterface $alertService)
    {
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserClient|null $userClient
     * @return Response
     */
    #[Route('/', name: '_index')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        #[CurrentUser] ?UserClient $userClient
    ): Response {
        $form = $this->createForm(EstimateYoursSiteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $estimate = $form->getData();

            $userClient ? $estimate->setUserClient($userClient) : $estimate->setUserClient(null);
            $estimate->setResult(1000);

            $entityManager->persist($estimate);
            $entityManager->flush();

            $request->getSession()->set('estimate', $estimate);

            $this->alertService->success('Estimation enregistrée avec succès !');

            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('estimate/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param AlertServiceInterface $alertService
     * @param MailerInterface $mailer
     * @param ParameterBagInterface $parameterBag
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('/contact-estimate', name: '_contact_estimate')]
    public function testContactEstimate(
        Request $request,
        AlertServiceInterface $alertService,
        MailerInterface $mailer,
        ParameterBagInterface $parameterBag
    ): Response {

        $estimate = $request->getSession()->get('estimate');
dd($estimate);
        $users = [
            'user' => $request->get('email'),
            'spaarple' => $parameterBag->get('mail.support'),
        ];


        foreach ($users as $user) {
            $emailContact = (new TemplatedEmail())
                ->from(new Address($parameterBag->get('mail.support'), 'Spaarple'))
                ->to($user)
                ->subject('Votre Estimation')
                ->htmlTemplate('contact/email.html.twig')
                ->context([
                    'estimate' => '',
                ]);

            $mailer->send($emailContact);
        }

        $alertService->success('Estimation envoyé !');

        return $this->redirectToRoute('app_home_index');
    }

}
