<?php

namespace App\Form;

use App\Entity\Estimate;
use App\Enum\CMS;
use App\Enum\Complexity;
use App\Enum\Integration;
use App\Enum\NumberPage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
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
                'label' => 'Nombre de pages du site',
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
            ]);
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
