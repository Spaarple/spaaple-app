<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User\UserAdministrator;
use App\Form\RegisterType;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/register', name: 'app_register')]
class RegisterController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param AlertServiceInterface $alertService
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AlertServiceInterface $alertService
    ) {
    }

    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @return Response
     */
    #[Route('/', name: '_index')]
    public function registerByRole(Request $request, UserPasswordHasherInterface $userPasswordHasher,): Response
    {
        $user = new UserAdministrator();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->alertService->success('Vous êtes bien inscrit ! Vous pouvez maintenant vous connecter.');

            return $this->redirectToRoute('app_login');
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
