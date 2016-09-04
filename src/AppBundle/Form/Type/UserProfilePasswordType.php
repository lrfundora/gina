<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class UserProfilePasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', 'password', [
                'label' => 'Introduce la contraseña actual',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Contraseña actual'
                ]
            ])
            ->add('password', 'repeated', [
                'type' => 'password',
                'constraints' => new Regex([
                    'pattern' => '((?=.*\d)(?=.*[a-z])(?=.*[A-Z]))'
                ]),
                'first_options' => [
                    'label' => 'Introduce la nueva contraseña',
                    'label_attr' => [
                        'class' => 'control-label'
                    ],
                    'attr' => [
                        'class' => 'form-control m-b',
                        'placeholder' => 'Nueva contraseña'
                    ]
                ],
                'second_options' => [
                    'label' => 'Repetir contraseña nueva',
                    'label_attr' => [
                        'class' => 'control-label'
                    ],
                    'attr' => [
                        'class' => 'form-control m-b',
                        'placeholder' => 'Repetir contraseña'
                    ]
                ]
            ])
            ->add('save', 'submit', [
                'label' => 'Cambiar',
                'attr' => [
                    'class' => 'btn btn-primary m-t-n-xs pull-right'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => 'AppBundle\Form\Model\ProfilePasswordModel'
        ]);
    }

    public function getName()
    {
        return 'formPass';
    }
} 