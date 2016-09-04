<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\DataTransformer\NumberToAnimationTransformer;

class BusinessAddType extends AbstractType
{
    private $em;
    private $animations;

    public function __construct(ObjectManager $manager)
    {
        $this->em = $manager;
        $query = $this->em->getRepository('AppBundle:Animation')->findAll();
        foreach ($query as $animation) {
            $this->animations[$animation->getId()] = $animation->getName();
        }
    }

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
            ->add('imgPublicity', new FileType(), [
                'label' => 'Imagen de publicidad',
                'required' => false,
                'data_class' => 'AppBundle\Entity\File'
            ])
            ->add('imgPublicityAnimation', 'choice', [
                'label' => 'Animación',
                'label_attr' => [
                    'class' => 'control-label'
                ],
                'attr' => [
                    'class' => 'form-control m-b'
                ],
                'choices' => $this->animations
            ])
            ->add('imgThumb', new FileType(), [
                'label' => 'Imagen de presentación',
                'required' => false,
                'data_class' => 'AppBundle\Entity\File'
            ])
            ->add('schedules', 'collection', [
                'type' => new ScheduleType(),
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('add', 'submit', [
                'label' => 'Agregar',
                'attr' => [
                    'class' => 'btn btn-primary m-t-n-xs pull-right'
                ]

            ]);

        $builder->get('imgPublicityAnimation')
            ->addModelTransformer(new NumberToAnimationTransformer($this->em));
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