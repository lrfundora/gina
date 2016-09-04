<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInfoType extends AbstractType
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
                'label' => 'Usuario del sistema',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Nombre de usuario'
                ]
            ])
            ->add('isActive', 'checkbox', [
                'label' => 'Activo',
                'attr' => [
                    'class' => 'i-checks'
                ]
            ])
            ->add('save', 'submit', [
                'label' => 'Actualizar',
                'attr' => [
                    'class' => 'btn btn-success m-t-n-xs pull-right'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User'
        ]);
    }

    public function getDefaultOptions(array $options)
    {
        return [
            'data_class' => 'AppBundle\Entity\User'
        ];
    }

    public function getName()
    {
        return 'formInfo';
    }
} 