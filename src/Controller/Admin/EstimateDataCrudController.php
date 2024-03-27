<?php

namespace App\Controller\Admin;

use App\Entity\EstimateData;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EstimateDataCrudController extends AbstractCrudController
{
    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return EstimateData::class;
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
            ->setPageTitle('index', 'Gestion des données lié aux Estimation')
            ->setPageTitle('detail', 'Détail des données lié aux Estimation')
            ->setPageTitle('edit', 'Modification des données lié aux Estimation');
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('experiment', 'Notre Expérience'),
            PercentField::new('prepayment', 'Paiement Anticipé Obligatoire'),
            PercentField::new('profit', 'Marge Bénéficiaire'),
        ];
    }

}
