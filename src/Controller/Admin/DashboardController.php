<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Users\UserCrudController;
use App\Entity\Estimate;
use App\Entity\EstimateData;
use App\Entity\User\AbstractUser;
use App\Entity\User\UserAdministrator;
use App\Entity\User\UserClient;
use App\Repository\User\AbstractUserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR')]
class DashboardController extends AbstractDashboardController
{
    /**
     * @param AbstractUserRepository $userRepository
     */
    public function __construct(private readonly AbstractUserRepository $userRepository)
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
            $adminUrlGenerator->setController(UserCrudController::class)
                ->generateUrl()
        );
    }

    /**
     * @return Dashboard
     */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CarsFleet')
            ->setTranslationDomain('admin')
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
        yield MenuItem::subMenu('Estimations', 'fas fa-users')->setSubItems([
            MenuItem::linkToCrud('Données des estimations', null, EstimateData::class),
            MenuItem::linkToCrud('Estimations Clients', null, Estimate::class),
        ]);


        yield MenuItem::section('Paramètres du compte');
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
            ));
    }
}
