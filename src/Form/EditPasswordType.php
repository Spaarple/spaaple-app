<?php

namespace App\Form;

use App\Form\Model\ChangePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditPasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'required' => true,
                'label' => 'Votre ancien mot de passe',
                'attr' => [
                    'placeholder'=> 'Ancien mot de passe',
                    'class' => 'form-control'
                ],
            ])
            ->add('newPassword', RepeatedType::class, [
                'required' => true,
                'type' => PasswordType::class,
                'invalid_message' => 'Mot de passe incorect',
                'options' => [
                    'attr' => [
                        'class' => 'password-field form-control',
                    ],
                ],
                'first_options'  => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => [
                        'class' => 'password-field form-control',
                        'placeholder'=> 'Votre nouveau mot de passe',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder'=> 'Confirmez votre mot de passe',
                        'class' => 'password-field form-control',
                    ],
                ],
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChangePassword::class,
        ]);
    }
}