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
     * @param ParameterBagInterface $parameterBag
     * @param MailerInterface $mailer
     */
    public function __construct(
        private readonly AlertServiceInterface $alertService,
        private readonly ParameterBagInterface $parameterBag,
        private readonly MailerInterface $mailer,
    )
    {
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserClient|null $userClient
     * @return Response
     * @throws TransportExceptionInterface
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

            $data = $request->request->all();
            $email = $data['estimate_yours_site']['contactEmail'];

            $userClient ? $estimate->setUserClient($userClient) : $estimate->setUserClient(null);
            $estimate->setResult(1000);

            $entityManager->persist($estimate);
            $entityManager->flush();

            if ($email) {
                $this->sendMailToEstimate($request, $estimate);
            }

            $this->alertService->success('Estimation enregistrée avec succès !');

            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('estimate/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $estimate
     * @return void
     * @throws TransportExceptionInterface
     */
    private function sendMailToEstimate(Request $request, $estimate): void
    {
        $data = $request->request->all();

        $users = [
            $data['estimate_yours_site']['contactEmail'],
            $this->parameterBag->get('mail.support'),
        ];

        foreach ($users as $user) {
            $emailContact = (new TemplatedEmail())
                ->from(new Address($this->parameterBag->get('mail.support'), 'Spaarple'))
                ->to($user)
                ->subject('Votre Estimation')
                ->htmlTemplate('estimate/email/email.html.twig')
                ->context([
                    'estimate' => $estimate,
                ]);

            $this->mailer->send($emailContact);
        }
    }
}
