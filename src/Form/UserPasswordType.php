<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'label' => 'Mot de Passe',
                    'label_attr' => [
                        'class' => 'form-label mt-3'
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'label' => 'Confirmer le mot de passe',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ],
                'invalid_message' => 'Le mot de passe ne correspond pas !',
            ])

            ->add('newPassword', PasswordType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Nouveau Mot de passe',
                'label_attr' => ['class' => 'form-label mt-3'],
                'constraints' => [new Assert\NotBlank()]
            ])

            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary mt-3'],
                'label' => 'Sauvegarder'
            ])
            ;
    }
}
