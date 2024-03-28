<?php

namespace App\Controller;

use App\Entity\User\AbstractUser;
use App\Form\EditProfileType;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile', name: 'app_profile')]
class ProfileController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param AlertServiceInterface $alertService
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AlertServiceInterface $alertService
    )
    {}

    /**
     * @param Request $request
     * @param AbstractUser $user
     *
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/', name: '_index')]
    public function editProfile(Request $request, #[CurrentUser] AbstractUser $user): Response
    {
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->alertService->success('Vos informations ont été mises à jour.');

            return $this->redirectToRoute('app_profile_index');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
