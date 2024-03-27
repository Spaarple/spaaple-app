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
            ->add('integration', ChoiceType::class, [
                'choices' => Integration::asArrayInverted(),
                'required' => true,
                'expanded' => true,
                'multiple' => true,
                'label' => false,
            ])
            ->add('cms',EnumType::class, [
                'class' => CMS::class,
                'label' => 'Quel CMS souhaitez-vous utilisez ?',
                'choice_label' => 'value',
                'choice_value' => 'name',
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('page', EnumType::class, [
                'class' => NumberPage::class,
                'label' => 'Nombre de pages du site',
                'choice_label' => 'value',
                'choice_value' => 'name',
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('complexity', EnumType::class, [
                'class' => Complexity::class,
                'label' => 'Complexité',
                'choice_label' => 'value',
                'choice_value' => 'name',
                'expanded' => false,
                'multiple' => false,
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
