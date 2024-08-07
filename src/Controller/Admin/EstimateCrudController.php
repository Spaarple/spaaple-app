<?php

namespace App\Controller\Admin;

use App\Entity\Estimate;
use App\Entity\User\AbstractUser;
use App\Enum\CMS;
use App\Enum\Complexity;
use App\Enum\NumberPage;
use App\Form\Admin\Field\EnumField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EstimateCrudController extends AbstractCrudController
{
    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Estimate::class;
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
            ->setPageTitle('index', 'Gestion des Estimation')
            ->setPageTitle('detail', 'Détail de l\'estimation')
            ->setPageTitle('edit', 'Modification de l\'estimation');
    }

    /**
     * @param Actions $actions
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT);
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user', 'Client')
                ->formatValue(function ($entity) {
                    if($entity instanceof Estimate && $entity->getUser() instanceof AbstractUser) {
                        return sprintf(
                            '%s %s',
                            $entity->getUser()->getFirstname(),
                            $entity->getUser()->getLastname()
                        );
                    }
                    return null;
                }),
            EmailField::new('email', 'Email'),
            TextareaField::new('description')->onlyOnDetail(),
            EnumField::setEnumClass(CMS::class)::new('cms', 'CMS'),
            EnumField::setEnumClass(Complexity::class)::new('complexity', 'Complexité'),
            CollectionField::new('integration', 'Intégration')->onlyOnIndex(),
            CollectionField::new('integration', 'Intégration')
                ->setTemplatePath('admin/field/integration.html.twig')
                ->onlyOnDetail(),
            EnumField::setEnumClass(NumberPage::class)::new('page', 'Nombre de page'),
            TextareaField::new('descriptionPage')->onlyOnDetail(),
            TextareaField::new('reference')->onlyOnDetail(),
            ArrayField::new('result', 'Résultat en (€)'),
            DateTimeField::new('createdAt')->setLabel('Date de création')->onlyOnIndex(),
        ];
    }

}
