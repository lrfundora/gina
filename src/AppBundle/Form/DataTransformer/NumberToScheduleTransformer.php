<?php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Schedule;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NumberToScheduleTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(ObjectManager $manager)
    {
        $this->em = $manager;
    }

    /**
     * Transforms an object (Schedule) to a string (number).
     *
     * @param Schedule|null $schedule
     * @return string
     */
    public function transform($schedule)
    {
        if ($schedule === null) {
            return '';
        }
        return $schedule->getId();
    }

    /**
     * Transforms a string (number) to an object (Schedule).
     *
     * @param string $scheduleNumber
     * @return Schedule|null
     * @throws TransformationFailedException if object (Schedule) is not found.
     */
    public function reverseTransform($scheduleNumber)
    {
        if (!$scheduleNumber) {
            return;
        }
        $schedule = $this->em->getRepository('AppBundle:Schedule')->find($scheduleNumber);
        if (null === $schedule) {
            return new TransformationFailedException(sprintf('La animación con id "%s" no existe!', $scheduleNumber));
        }
        return $schedule;
    }
}