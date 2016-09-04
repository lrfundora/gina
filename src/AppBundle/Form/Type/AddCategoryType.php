<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCategoryType extends AbstractType
{

    private $choices;

    public function __construct($choiceBusiness)
    {
        $this->choices = $choiceBusiness;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('business', 'choice', [
                'label' => 'Seleccione un negocio',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b'
                ],
                'choices' => $this->choices,
                'empty_value' => 'Selecciona un negocio'
            ])
            ->add('category', 'text', [
                'label' => 'Introduce el nombre de la categoría',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Nombre'
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
            //'data_class' => 'AppBundle\Entity\Category'
            //'data_class' => 'AppBundle\Form\Model\CategoryModel'
        ]);
    }

    public function getName()
    {
        return 'addCategory';
    }
} 