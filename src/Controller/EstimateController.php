<?php

namespace App\Controller;

use App\Entity\User\UserClient;
use App\Form\EstimateYoursSiteType;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

            $this->alertService->success('Estimation enregistrée avec succès !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('estimate/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
