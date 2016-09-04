<?php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Animation;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StringToDateTransformer implements DataTransformerInterface
{

    /**
     * Transforms an object (\DateTime) to a string.
     *
     * @param \DateTime|null $date
     * @return string
     */
    public function transform($date)
    {
        if ($date === null) {
            return '';
        }
        return $date->format('d') . '/' . $date->format('m') . '/' . $date->format('Y');
    }

    /**
     * Transforms a string to an object (\DateTime).
     *
     * @param string $dateString
     * @return \DateTime|null
     * @throws TransformationFailedException if object (\DateTime) is not found.
     */
    public function reverseTransform($dateString)
    {
        if (!$dateString) {
            return;
        }

        return date_create_from_format('d/m/Y', $dateString);
    }
}