<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditCategoryType extends AbstractType
{

    private $choices;
    private $idSelect;
    private $value;

    public function __construct($choiceBusiness, $idSelect, $value)
    {
        $this->choices = $choiceBusiness;
        $this->idSelect = $idSelect;
        $this->value = $value;
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
                'preferred_choices' => [$this->idSelect]
            ])
            ->add('name', 'text', [
                'label' => 'Nombre de la categoría',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b',
                    'placeholder' => 'Nombre',
                    'value' => $this->value
                ]
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
            //'data_class' => 'AppBundle\Entity\Category'
        ]);
    }

    public function getName()
    {
        return 'editCategory';
    }
} 