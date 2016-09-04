<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAddType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Nombre',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Nombre'
                ]
            ])
            ->add('lastname', 'text', [
                'label' => 'Apellidos',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Apellidos'
                ]
            ])
            ->add('user', 'text', [
                'label' => 'Nombre de usuario',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Nombre de usuario'
                ]
            ])
            ->add('password', 'password', [
                'label' => 'Contraseña',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Contraseña'
                ]
            ])
            ->add('isActive', 'checkbox', [
                'label' => 'Activo',
                'attr' => [
                    'class' => 'i-checks'
                ]
            ])
            ->add('add', 'submit', [
                'label' => 'Agregar',
                'attr' => [
                    'class' => 'btn btn-primary m-t-n-xs pull-right'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User'
            //'data_class' => 'AppBundle\Form\Model\AccountCreateModel'
        ]);
    }

    public function getDefaultOptions(array $options)
    {
        return [
            'data_class' => 'AppBundle\Entity\User'
            //'data_class' => 'AppBundle\Form\Model\AccountCreateModel'
        ];
    }

    public function getName()
    {
        return 'form';
    }
} 