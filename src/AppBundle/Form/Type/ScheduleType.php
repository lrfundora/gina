<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduleType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isAllHours', 'checkbox', [
                'label' => '24 Horas',
                'attr' => [
                    'class' => 'i-checks'
                ],
                'required' => false
            ])
            ->add('timeStart', 'text', [
                'label' => 'Horario de apertura',
                'label_attr' => [
                    'class' => 'hidden'
                ],
                'attr' => [
                    'class' => 'input-sm form-control',
                    'placeholder' => 'Horario de apertura'
                ],
                'required' => false
            ])
            ->add('timeEnd', 'text', [
                'label' => 'Horario de cierre',
                'label_attr' => [
                    'class' => 'hidden'
                ],
                'attr' => [
                    'class' => 'input-sm form-control',
                    'placeholder' => 'Horario de cierre'
                ],
                'required' => false
            ])
            ->add('isAllDays', 'checkbox', [
                'label' => 'Todos los días',
                'attr' => [
                    'class' => 'i-checks'
                ],
                'required' => false
            ])
            ->add('dayStart', 'choice', [
                'label' => 'Día de apertura',
                'label_attr' => [
                    'class' => 'hidden'
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    '1' => 'Lunes',
                    '2' => 'Martes',
                    '3' => 'Miércoles',
                    '4' => 'Jueves',
                    '5' => 'Viernes',
                    '6' => 'Sábado',
                    '0' => 'Domingo'
                ],
                'empty_value' => 'Seleccionar',
                'required' => false
            ])
            ->add('dayEnd', 'choice', [
                'label' => 'Día de cierre',
                'label_attr' => [
                    'class' => 'hidden'
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    '1' => 'Lunes',
                    '2' => 'Martes',
                    '3' => 'Miércoles',
                    '4' => 'Jueves',
                    '5' => 'Viernes',
                    '6' => 'Sábado',
                    '0' => 'Domingo'
                ],
                'empty_value' => 'Seleccionar',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Schedule'
        ]);
    }

    public function getDefaultOptions(array $options)
    {
        return [
            'data_class' => 'AppBundle\Entity\Schedule'
        ];
    }

    public function getName()
    {
        return 'formSch';
    }
} 