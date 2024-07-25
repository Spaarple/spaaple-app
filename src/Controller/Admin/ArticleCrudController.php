<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    /**
     * @param Crud $crud
     *
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Gestion des articles')
            ->setPageTitle('detail', 'Détail de l\'article');
    }

    /**
     * @param Actions $actions
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre de l\'article'),
            TextField::new('subtitle', 'Sous-titre de l\'article')->hideOnIndex(),
            TextEditorField::new('content', 'Contenu de l\'article')
                ->setNumOfRows(20)->formatValue(function ($value) {
                return nl2br($value);
            }),
            SlugField::new('slug', 'Slug de l\'article')->setTargetFieldName('title'),
            AssociationField::new('category', 'Catégorie'),
            DateTimeField::new('date', 'Date de publication'),
            BooleanField::new('isPublished', 'Statut Publié'),
            ImageField::new('image', 'Image')
                ->setUploadDir('assets/images/article')
                ->setBasePath('images/article')
                ->setUploadedFileNamePattern('[slug]-[uuid].[extension]'),
        ];
    }
}
