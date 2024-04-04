<?php

namespace App\Controller;

use App\Entity\Estimate;
use App\Entity\User\UserClient;
use App\Enum\CMS;
use App\Enum\Complexity;
use App\Enum\NumberPage;
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
            $estimate->setResult($this->getResultEstimate($estimate));

            if (!$userClient) {
                $estimate->setUserClient(null);
                $this->sendMailToEstimate($request, $estimate);
            } else {
                $estimate->setUserClient($userClient);
            }

            $entityManager->persist($estimate);
            $entityManager->flush();

            $this->alertService->success('Estimation enregistrée avec succès !');

            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('estimate/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Estimate $estimate
     * @return void
     * @throws TransportExceptionInterface
     */
    private function sendMailToEstimate(Request $request, Estimate $estimate): void
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
                ->textTemplate('estimate/email/email.txt.twig')
                ->htmlTemplate('estimate/email/email.html.twig')
                ->context([
                    'estimate' => $estimate,
                ]);

            $this->mailer->send($emailContact);
        }
    }

    /**
     * @param $estimateData
     * @return array
     */
    private function getResultEstimate($estimateData): array
    {
        $cmsEstimations = [
            CMS::SHOPIFY->name => [300, 500],
            CMS::WORDPRESS->name => [500, 1000],
            CMS::AUCUN->name => [1000, 3000]
        ];

        $pagesEstimations = [
            NumberPage::SMALL->name => [200, 400],
            NumberPage::MEDIUM->name => [400, 800],
            NumberPage::BIGGEST->name => [1000, 2000]
        ];

        $complexityEstimations = [
            Complexity::SIMPLE->name => [500, 1000],
            Complexity::MIDDLE->name => [1000, 2000],
            Complexity::HARDCORE->name => [3000, 5000]
        ];

        $integrationUnitPrice = [100, 200];
        $cms = $estimateData->getCMS()->name;
        $page = $estimateData->getPage()->name;
        $complexity = $estimateData->getComplexity()->name;

        $coutIntegrations = [
            $integrationUnitPrice[0] * count($estimateData->getIntegration()),
            $integrationUnitPrice[1] * count($estimateData->getIntegration())
        ];

        $smallPrice = (
            $cmsEstimations[$cms][0] +
            $pagesEstimations[$page][0] +
            $complexityEstimations[$complexity][0] +
            $coutIntegrations[0]);

        $bigPrice = (
            $cmsEstimations[$cms][1] +
            $pagesEstimations[$page][1] +
            $complexityEstimations[$complexity][1] +
            $coutIntegrations[1]);

        return [$smallPrice, $bigPrice];
    }
}

