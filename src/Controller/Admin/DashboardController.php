<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Users\UserCrudController;
use App\Entity\BulkContact;
use App\Entity\Contact;
use App\Entity\Estimate;
use App\Entity\EstimateData;
use App\Entity\User\AbstractUser;
use App\Entity\User\UserAdministrator;
use App\Entity\User\UserClient;
use App\Repository\ContactRepository;
use App\Repository\EstimateRepository;
use App\Repository\User\AbstractUserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR')]
class DashboardController extends AbstractDashboardController
{
    /**
     * @param AbstractUserRepository $userRepository
     * @param EstimateRepository $estimateRepository
     * @param ContactRepository $contactRepository
     */
    public function __construct(
        private readonly AbstractUserRepository $userRepository,
        private readonly EstimateRepository $estimateRepository,
        private readonly ContactRepository $contactRepository,
    )
    {}

    /**
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect(
            $adminUrlGenerator->setController(UserCrudController::class)->generateUrl()
        );
    }

    /**
     * @return Dashboard
     */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Spaarple')
            ->setTranslationDomain('admin')
            ->setFaviconPath('build/images/icons.svg')
            ->disableUrlSignatures();
    }

    /**
     * @return iterable
     */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::subMenu('Utilisateurs', 'fas fa-users')->setSubItems([
            MenuItem::linkToCrud('Tous', null, AbstractUser::class),
            MenuItem::linkToCrud('Administrateurs', null, UserAdministrator::class),
            MenuItem::linkToCrud('Clients', null, UserClient::class),
        ])->setBadge($this->userRepository->count([]), 'primary');

        yield MenuItem::subMenu('Estimations', 'fa-solid fa-coins')->setSubItems([
            MenuItem::linkToCrud('Données des estimations', null, EstimateData::class),
            MenuItem::linkToCrud('Estimations Clients', null, Estimate::class),
        ])->setBadge($this->estimateRepository->count([]), 'primary');

        yield MenuItem::linkToCrud('Contacts', 'fa fa-envelope', Contact::class)
            ->setBadge($this->contactRepository->count([]), 'primary');

        yield MenuItem::linkToCrud(
            'Envoyer des messages',
            'fa fa-envelope-open-text',
            BulkContact::class
        );

        yield MenuItem::section('Paramètres du compte');
        yield MenuItem::linkToRoute('Mon profil', 'fa fa-address-card', 'app_profile_index');
        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out');
    }

    /**
     * @param UserInterface $user
     * @return UserMenu
     */
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName(sprintf('%s %s | %s',
                $user->getFirstName(),
                $user->getLastName(),
                $user->getEmail()
            ))
            ->addMenuItems([
                MenuItem::linkToRoute('Mon profil', 'fa fa-address-card', 'app_profile_index')
            ]);
    }
}
