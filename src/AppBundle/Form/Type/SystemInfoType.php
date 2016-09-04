<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemInfoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Nombre',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nombre'
                ]
            ])
            ->add('slogan', 'text', [
                'label' => 'Slogan',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Slogan'
                ]
            ])
            ->add('publicity', 'textarea', [
                'label' => 'Publicidad',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Publicidad'
                ]
            ])
            ->add('infoTitle', 'text', [
                'label' => 'Título de la información',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Título de la información'
                ]
            ])
            ->add('infoText', 'textarea', [
                'label' => 'Texto de la información',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'Escribe aquí el texto de información'
                ]
            ])
            ->add('email', 'text', [
                'label' => 'Correo electrónico',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Correo electrónico'
                ]
            ])
            ->add('address', 'textarea', [
                'label' => 'Dirección',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 2,
                    'placeholder' => 'Dirección'
                ]
            ])
            ->add('phone', 'text', [
                'label' => 'Teléfono',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Teléfono'
                ]
            ])
            ->add('owner', 'textarea', [
                'label' => 'Propietarios',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 2,
                    'placeholder' => 'Escribe aquí el nombre de todos los propietarios del negocio...'
                ]
            ])
//            ->add('infoImages', 'collection', [
//                'type' => new FileType(),
//                'data_class' => 'AppBundle\Entity\File',
//                'allow_add' => true
//            ])
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
            'data_class' => 'AppBundle\Entity\appConfig'
        ]);
    }

    public function getDefaultOptions(array $options)
    {
        return [
            'data_class' => 'AppBundle\Entity\appConfig'
        ];
    }

    public function getName()
    {
        return 'formSystem';
    }
} 