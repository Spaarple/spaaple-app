<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Entity\User\UserAdministrator;
use App\Enum\Role;
use App\Helper\GeneratePasswordHelper;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR')]
class UserAdminCrudController extends AbstractCrudController
{

    /**
     * @param GeneratePasswordHelper $generatePasswordHelper
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        private readonly GeneratePasswordHelper $generatePasswordHelper,
        private readonly UserPasswordHasherInterface $passwordHasher,

    ) {
    }

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return UserAdministrator::class;
    }

    /**
     * @param Crud $crud
     *
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setEntityLabelInSingular('Administrateur')
            ->setEntityLabelInPlural('Administrateurs');
    }

    /**
     * @param Actions $actions
     *
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    /**
     * @param string $pageName
     *
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email', 'Email'),
            TextField::new('lastName', 'Nom'),
            TextField::new('firstName', 'Prénom'),
            ChoiceField::new('roles', 'Roles')
                ->setValue(Role::ROLE_ADMINISTRATOR->name)
                ->allowMultipleChoices()
                ->setChoices([
                    ucfirst(Role::ROLE_ADMINISTRATOR->value) => Role::ROLE_ADMINISTRATOR->name,
                ])->onlyOnDetail(),
            BooleanField::new('isBlocked', 'Bloquer l\'utilisateur'),
            BooleanField::new('isVerified', 'Vérifier l\'utilisateur'),
            DateTimeField::new('createdAt')->setLabel('Date de création')->onlyOnIndex(),
        ];
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param mixed $entityInstance
     * @return void
     * @throws TransportExceptionInterface
     */
    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        /** @var UserAdministrator $entityInstance */
        if (!$entityInstance instanceof UserAdministrator) {
            return;
        }

        $generatePassword = $this->generatePasswordHelper->generatePassword(10);

        $entityInstance->setPassword($this->passwordHasher->hashPassword($entityInstance, $generatePassword));

        parent::persistEntity($entityManager, $entityInstance);

        $this->generatePasswordHelper->sendMailAdmin($entityInstance, $generatePassword);
    }
}
