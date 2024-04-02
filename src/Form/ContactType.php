<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$options['user_connected']) {
            $builder
                ->add('email', EmailType::class, [
                    'label' => 'Adresse email',
                    'attr' => [
                        'placeholder' => 'Adresse email',
                    ],
                    'required' => true,
                ])
                ->add('who', TextType::class, [
                    'label' => 'Nom Prénom, Société ou Association',
                    'attr' => [
                        'placeholder' => 'Nom, Prénom, Société ou Association',
                    ],
                    'required' => true,
                ])
                ->add('message', TextareaType::class, [
                    'label' => 'Message',
                    'attr' => [
                        'placeholder' => 'Votre message',
                        'rows' => '5',
                    ],
                    'required' => true,
                ]);
        }
        $builder
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Votre message',
                    'rows' => '7',
                ],
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
            'data_class' => Contact::class,
            'user_connected' => false,
        ]);
    }
}
