<?php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Business;
use AppBundle\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NumberToCategoryTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(ObjectManager $manager)
    {
        $this->em = $manager;
    }

    /**
     * Transforms an object (Category) to a string (number).
     *
     * @param Category|null $category
     * @return string
     */
    public function transform($category)
    {
        if ($category === null) {
            return '';
        }
        return $category->getId();
    }

    /**
     * Transforms a string (number) to an object (Category).
     *
     * @param string $categoryNumber
     * @return Category|null
     * @throws TransformationFailedException if object (Category) is not found.
     */
    public function reverseTransform($categoryNumber)
    {
        if (!$categoryNumber) {
            return;
        }
        $category = $this->em->getRepository('AppBundle:Category')->find($categoryNumber);
        if (null === $category) {
            return new TransformationFailedException(sprintf('El categoría con id "%s" no existe!', $categoryNumber));
        }
        return $category;
    }
}