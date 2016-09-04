<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactUsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Nombre: ',
                'label_attr' => [
                    'class' => 'label-form'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nombre'
                ]
            ])
            ->add('email', 'email', [
                'label' => 'Correo electrónico: ',
                'label_attr' => [
                    'class' => 'label-form'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Correo electrónico'
                ]
            ])
            ->add('subject', 'text', [
                'label' => 'Asunto: ',
                'label_attr' => [
                    'class' => 'label-form'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Asunto'
                ]
            ])
            ->add('message', 'textarea', [
                'label' => 'Mensaje: ',
                'label_attr' => [
                    'class' => 'label-form'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => '5',
                    'placeholder' => 'Escriba aquí el texto que deseas enviar...'
                ]
            ])
            ->add('send', 'submit', [
                'label' => 'Enviar',
                'attr' => [
                    'class' => 'btn btn-primary pull-right push-bottom',
                    'data-loading-text' => 'Loading...'
                ]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => 'AppBundle\Entity\Inbox'
//            'data_class' => 'AppBundle\Form\Model\AccountCreateModel'
        ]);
    }

    public function getDefaultOptions(array $options)
    {
        return [
            //'data_class' => 'AppBundle\Entity\Inbox'
//            'data_class' => 'AppBundle\Form\Model\AccountCreateModel'
        ];
    }

    public function getName()
    {
        return 'contactForm';
    }
} 