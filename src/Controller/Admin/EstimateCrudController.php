<?php

namespace App\Controller\Admin;

use App\Entity\Estimate;
use App\Enum\CMS;
use App\Enum\Integration;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
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
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('userClient', 'Client'),
            ChoiceField::new('integration', 'Intégration')
                ->allowMultipleChoices()
                ->setChoices(Integration::asArrayInverted()),
            ChoiceField::new('cms', 'CMS')
                ->setChoices(CMS::asArrayInverted()),
            NumberField::new('page', 'Nombre de page'),
            TextField::new('complexity', 'Complexité'),
            NumberField::new('result', 'Résultat'),
        ];
    }

}
