<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemOfferType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sloganOffer', 'text', [
                'label' => 'Slogan de oferta',
                'label_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Slogan de oferta'
                ]
            ])
            ->add('imgOffer', new FileType(),[
                'label'=>'Imagen',
                'required'=>false,
                'data_class'=>'AppBundle\Entity\File'
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
        return 'formOffer';
    }
} 