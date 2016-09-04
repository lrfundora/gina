<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BusinessEditType extends AbstractType
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
            ->add('slogan', 'text', [
                'label' => 'Slogan',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Slogan'
                ]
            ])
            ->add('phone', 'text', [
                'label' => 'Teléfono',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Teléfono'
                ]
            ])
            ->add('email', 'email', [
                'label' => 'Correo electrónico',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Correo electrónico'
                ]
            ])
            ->add('address', 'textarea', [
                'label' => 'Dirección',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'rows' => 2,
                    'placeholder' => 'Dirección'
                ]
            ])
            ->add('description', 'textarea', [
                'label' => 'Descripción',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'rows' => 5,
                    'placeholder' => 'Breve descripción del negocio...'
                ]
            ])
            ->add('schedules', 'collection', [
                'type' => new ScheduleType(),
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true
            ])
            ->add('update', 'submit', [
                'label' => 'Actualizar',
                'attr' => [
                    'class' => 'btn btn-success m-t-n-xs pull-right'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Business'
        ]);
    }

    public function getDefaultOptions(array $options)
    {
        return [
            'data_class' => 'AppBundle\Entity\Business'
        ];
    }

    public function getName()
    {
        return 'form';
    }
} 