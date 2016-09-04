<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Business;
use AppBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\DataTransformer\StringToDateTransformer;

class ProductAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Nombre del producto',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Nombre'
                ]
            ])
            ->add('description', 'textarea', [
                'label' => 'Descripción',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Escribe una descripción para este producto...',
                    'rows' => 3
                ]
            ])
            ->add('price', 'text', [
                'label' => 'Precio',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => '0.00'
                ]
            ])
            ->add('isOffer', 'checkbox', [
                'label' => 'En Oferta',
                'attr' => [
//                    'class' => 'i-checks'
                ],
                'required' => false
            ])
            ->add('discount', 'text', [
                'label' => 'Descuento',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => '0'
                ],
                'required' => false
            ])
            ->add('publicated', 'text', [
                'label' => 'Fecha de publicación',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
//                    'placeholder' => '10/11/2015',
//                    'readonly' => true
                ],
                'required' => false
            ])
            ->add('expired', 'text', [
                'label' => 'Fecha de terminación',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
//                    'placeholder' => '15/11/2015',
//                    'readonly' => true
                ],
                'required' => false
            ])
            ->add('conditions', 'textarea', [
                'label' => 'Condiciones',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Escribe aquí las condiciones de la oferta...',
                    'rows' => 3
                ],
                'required' => false
            ])
            ->add('photos', 'collection', [
                'type' => new FileType(),
                'allow_add' => true,
                'by_reference' => false,
            ]);
        $builder->get('publicated')->addModelTransformer(new StringToDateTransformer());
        $builder->get('expired')->addModelTransformer(new StringToDateTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Product'
        ]);
    }

    public function getDefaultOptions(array $options)
    {
        return [
            'data_class' => 'AppBundle\Entity\Product'
        ];
    }

    public function getName()
    {
        return 'formProduct';
    }
} 