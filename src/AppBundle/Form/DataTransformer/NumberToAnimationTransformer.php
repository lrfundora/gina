<?php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Animation;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NumberToAnimationTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(ObjectManager $manager)
    {
        $this->em = $manager;
    }

    /**
     * Transforms an object (Animation) to a string (number).
     *
     * @param Animation|null $animation
     * @return string
     */
    public function transform($animation)
    {
        if ($animation === null) {
            return '';
        }
        return $animation->getId();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param string $animationNumber
     * @return Animation|null
     * @throws TransformationFailedException if object (Animation) is not found.
     */
    public function reverseTransform($animationNumber)
    {
        if (!$animationNumber) {
            return;
        }
        $animation = $this->em->getRepository('AppBundle:Animation')->find($animationNumber);
        if (null === $animation) {
            return new TransformationFailedException(sprintf('La animación con id "%s" no existe!', $animationNumber));
        }
        return $animation;
    }
}