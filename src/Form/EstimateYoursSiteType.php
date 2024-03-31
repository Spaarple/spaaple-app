<?php

namespace App\Form;

use App\Entity\Estimate;
use App\Enum\CMS;
use App\Enum\Complexity;
use App\Enum\Integration;
use App\Enum\NumberPage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstimateYoursSiteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('integration', EnumType::class, [
                'class' => Integration::class,
                'label' => 'Quelles intégrations souhaitez-vous ? (Plusieurs choix possibles)',
                'choice_label' => 'value',
                'choice_value' => 'name',
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ])
            ->add('cms', EnumType::class, [
                'class' => CMS::class,
                'label' => 'Quel CMS souhaitez-vous utilisez ?',
                'choice_label' => 'value',
                'choice_value' => 'name',
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('page', EnumType::class, [
                'class' => NumberPage::class,
                'label' => 'A combien de pages estimez-vous votre site ?',
                'choice_label' => 'value',
                'choice_value' => 'name',
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('complexity', EnumType::class, [
                'class' => Complexity::class,
                'label' => 'Comment estimez-vous la complexité du site ?',
                'choice_label' => 'value',
                'choice_value' => 'name',
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de votre projet',
                'attr' => [
                    'placeholder' => 'Nous sommes impatients de découvrir les détails de votre projet !',
                ],
                'required' => true,
            ])
            ->add('descriptionPage', TextareaType::class, [
                'label' => 'Listez ici les pages que vous souhaitez voir sur votre site web.',
                'attr' => [
                    'placeholder' => 'Accueil, À propos, Services, Galerie, Contact, etc.',
                ],
                'required' => true,
            ])
            ->add('reference', TextareaType::class, [
                'label' => 'Donnez-nous des références de sites que vous aimez',
                'attr' => [
                    'placeholder' => 'Partagez avec nous les sites web que vous appréciez pour leur design, leur fonctionnalité ou leur contenu.',
                ],
                'required' => true,
            ])
            ->add('contactEmail', EmailType::class, [
                'label' => 'Votre adresse email',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Recevoir votre estimation par email',
                ],
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Estimate::class,
        ]);
    }
}
