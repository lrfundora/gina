<?php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Business;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NumberToBusinessTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(ObjectManager $manager)
    {
        $this->em = $manager;
    }

    /**
     * Transforms an object (Business) to a string (number).
     *
     * @param Business|null $business
     * @return string
     */
    public function transform($business)
    {
        if ($business === null) {
            return '';
        }
        return $business->getId();
    }

    /**
     * Transforms a string (number) to an object (Business).
     *
     * @param string $businessNumber
     * @return Business|null
     * @throws TransformationFailedException if object (Business) is not found.
     */
    public function reverseTransform($businessNumber)
    {
        if (!$businessNumber) {
            return;
        }
        $business = $this->em->getRepository('AppBundle:Business')->find($businessNumber);
        if (null === $business) {
            return new TransformationFailedException(sprintf('El negocio con id "%s" no existe!', $businessNumber));
        }
        return $business;
    }
}