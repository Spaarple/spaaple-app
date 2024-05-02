<?php

namespace App\Controller;

use App\Entity\User\AbstractUser;
use App\Form\EditPasswordType;
use App\Form\EditProfileType;
use App\Form\Model\ChangePassword;
use App\Repository\EstimateRepository;
use App\Service\AlertServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
            $user->setUpdatedAt(new  DateTime());

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->alertService->success('Vos informations ont été mises à jour.');

            return $this->redirectToRoute('app_profile_index');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/change-password', name: '_change_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $changePasswordModel = new ChangePassword();
        $form = $this->createForm(EditPasswordType::class, $changePasswordModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var AbstractUser $user */
            $user = $this->getUser();
            $user->setUpdatedAt(new  DateTime());

            $password = $form->get('newPassword')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $password));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->alertService->success('Votre mot de passe a été mis à jour.');

            return $this->redirectToRoute('app_profile_index');
        }

        return $this->render('profile/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/your-estimate', name: '_your_estimate')]
    public function yourEstimate(EstimateRepository $estimateRepository, #[CurrentUser] AbstractUser $user): Response
    {
        $estimates = $estimateRepository->findBy(['user' => $user]);

        return $this->render('profile/your_estimate.html.twig', [
            'estimates' => $estimates,
        ]);
    }
}
