<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\DataTransformer\NumberToAnimationTransformer;

class AnimationType extends AbstractType
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
            ->add('imgPublicityAnimation', 'choice', [
                'label' => ' ',
                'label_attr' => [
                    'class' => 'control-label hidden'
                ],
                'attr' => [
                    'class' => 'form-control m-b'
                ],
                'choices' => $this->animations
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
        return 'formAnimation';
    }
} 