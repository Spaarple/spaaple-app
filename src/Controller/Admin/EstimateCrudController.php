<?php

namespace App\Controller\Admin;

use App\Entity\Estimate;
use App\Entity\User\UserClient;
use App\Enum\CMS;
use App\Enum\Complexity;
use App\Enum\Integration;
use App\Enum\NumberPage;
use App\Form\Admin\Field\EnumField;
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
            AssociationField::new('userClient', 'Client')
                ->formatValue(function ($value, $entity) {
                    if($entity instanceof Estimate && $entity->getUserClient() instanceof UserClient) {
                        return sprintf(
                            '%s %s',
                            $entity->getUserClient()->getFirstname(),
                            $entity->getUserClient()->getLastname()
                        );
                    }
                    return null;
                }),
            ChoiceField::new('integration', 'Intégration')
                ->allowMultipleChoices()
                ->setChoices(Integration::asArrayInverted()),
            EnumField::setEnumClass(CMS::class)::new('cms', 'CMS'),
            EnumField::setEnumClass(NumberPage::class)::new('page', 'Nombre de page'),
            EnumField::setEnumClass(Complexity::class)::new('complexity', 'Complexité'),
            NumberField::new('result', 'Résultat'),
        ];
    }

}
