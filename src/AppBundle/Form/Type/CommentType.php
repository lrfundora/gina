<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Nombre: ',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control input-lg',
                    'placeholder' => 'Nombre'
                ]
            ])
            ->add('email', 'email', [
                'label' => 'Correo electrónico: ',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control input-lg',
                    'placeholder' => 'Correo electrónico'
                ]
            ])
            ->add('text', 'textarea', [
                'label' => ' ',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control input-lg',
                    'rows'=>5,
                    'placeholder' => 'Escribe aquí tu comentario...'
                ]
            ])
            ->add('send', 'submit', [
                'label' => 'Comentar',
                'attr' => [
                    'class' => 'btn btn-primary m-t-n-xs'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Comment'
        ]);
    }

    public function getDefaultOptions(array $options)
    {
        return [
            'data_class' => 'AppBundle\Entity\Comment'
        ];
    }

    public function getName()
    {
        return 'form';
    }
} 