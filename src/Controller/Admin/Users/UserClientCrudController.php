<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Entity\User\UserClient;
use App\Enum\Role;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ArrayFilter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMINISTRATOR')]
class UserClientCrudController extends AbstractCrudController
{
    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return UserClient::class;
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
            ->setEntityLabelInSingular('Client')
            ->setEntityLabelInPlural('Clients');
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
     * @param Filters $filters
     *
     * @return Filters
     */
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ArrayFilter::new('roles')->setChoices(Role::asArrayInverted()));
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
}
