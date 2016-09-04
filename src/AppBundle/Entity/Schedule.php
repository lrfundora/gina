<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Schedule
 * @ORM\Table(name="schedule")
 * @ORM\Entity
 */
class Schedule
{

    private $arrDays;

    public function __construct()
    {
        $this->arrDays = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isAllHours;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $timeStart;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $timeEnd;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isAllDays;

    /**
     * @ORM\Column(type="integer", length=2, nullable=true)
     */
    protected $dayStart;

    /**
     * @ORM\Column(type="integer", length=2, nullable=true)
     */
    protected $dayEnd;

    /**
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="schedules")
     */
    protected $business;

    /**
     * Get Schedule
     *
     * @return string
     */
    public function getInfo()
    {
        $this->arrDays = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        if ($this->getIsAllHours()) {
            $time = '24 Horas';
        } else {
            $time = $this->timeStart . ' a ' . $this->timeEnd;
        }

        if ($this->getIsAllDays()) {
            $day = ' todos los días.';
        } else {
            if (null === $this->dayEnd && null !== $this->dayStart) {
                $day = ' los ' . $this->arrDays[$this->getDayStart()].'.';
            } elseif (null !== $this->dayEnd && null !== $this->dayStart) {
                $day = ' de ' . $this->arrDays[$this->getDayStart()] . ' a ' . $this->arrDays[$this->getDayEnd()] . '.';
            } else {
                $day = '';
            }

        }
        return $time . $day;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isAllHours
     *
     * @param boolean $isAllHours
     * @return Schedule
     */
    public function setIsAllHours($isAllHours)
    {
        $this->isAllHours = $isAllHours;

        return $this;
    }

    /**
     * Get isAllHours
     *
     * @return boolean
     */
    public function getIsAllHours()
    {
        return $this->isAllHours;
    }

    /**
     * Set timeStart
     *
     * @param \DateTime $timeStart
     * @return Schedule
     */
    public function setTimeStart($timeStart)
    {
        $this->timeStart = $timeStart;

        return $this;
    }

    /**
     * Get timeStart
     *
     * @return \DateTime
     */
    public function getTimeStart()
    {
        return $this->timeStart;
    }

    /**
     * Set timeEnd
     *
     * @param \DateTime $timeEnd
     * @return Schedule
     */
    public function setTimeEnd($timeEnd)
    {
        $this->timeEnd = $timeEnd;

        return $this;
    }

    /**
     * Get timeEnd
     *
     * @return \DateTime
     */
    public function getTimeEnd()
    {
        return $this->timeEnd;
    }

    /**
     * Set isAllDays
     *
     * @param boolean $isAllDays
     * @return Schedule
     */
    public function setIsAllDays($isAllDays)
    {
        $this->isAllDays = $isAllDays;

        return $this;
    }

    /**
     * Get isAllDays
     *
     * @return boolean
     */
    public function getIsAllDays()
    {
        return $this->isAllDays;
    }

    /**
     * Set dayStart
     *
     * @param string $dayStart
     * @return Schedule
     */
    public function setDayStart($dayStart)
    {
        $this->dayStart = $dayStart;

        return $this;
    }

    /**
     * Get dayStart
     *
     * @return string
     */
    public function getDayStart()
    {
        return $this->dayStart;
    }

    /**
     * Set dayEnd
     *
     * @param string $dayEnd
     * @return Schedule
     */
    public function setDayEnd($dayEnd)
    {
        $this->dayEnd = $dayEnd;

        return $this;
    }

    /**
     * Get dayEnd
     *
     * @return string
     */
    public function getDayEnd()
    {
        return $this->dayEnd;
    }

    /**
     * Set business
     *
     * @param \AppBundle\Entity\Business $business
     * @return Schedule
     */
    public function setBusiness(\AppBundle\Entity\Business $business = null)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * Get business
     *
     * @return \AppBundle\Entity\Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

}
