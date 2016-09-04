<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Business
 * @ORM\Table(name="business")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\BusinessRepository")
 */
class Business
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @ORM\OneToOne(targetEntity="File", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="imgThumb",referencedColumnName="id")
     */
    protected $imgThumb;

    /**
     * @ORM\OneToOne(targetEntity="File", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="imgPublicity",referencedColumnName="id")
     */
    protected $imgPublicity;

    /**
     * @ORM\ManyToOne(targetEntity="Animation", inversedBy="business")
     */
    protected $imgPublicityAnimation;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $slogan;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="appConfig", inversedBy="business")
     */
    protected $config;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="business", indexBy="name", cascade={"remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"name"="ASC"})
     */
    protected $categories;

    /**
     * @ORM\OneToMany(targetEntity="Schedule", mappedBy="business", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $schedules;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->schedules = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Business
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Business
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Business
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Business
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set slogan
     *
     * @param string $slogan
     * @return Business
     */
    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;

        return $this;
    }

    /**
     * Get slogan
     *
     * @return string 
     */
    public function getSlogan()
    {
        return $this->slogan;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Business
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Business
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imgThumb
     *
     * @param \AppBundle\Entity\File $imgThumb
     * @return Business
     */
    public function setImgThumb(\AppBundle\Entity\File $imgThumb = null)
    {
        $this->imgThumb = $imgThumb;

        return $this;
    }

    /**
     * Get imgThumb
     *
     * @return \AppBundle\Entity\File 
     */
    public function getImgThumb()
    {
        return $this->imgThumb;
    }

    /**
     * Set imgPublicity
     *
     * @param \AppBundle\Entity\File $imgPublicity
     * @return Business
     */
    public function setImgPublicity(\AppBundle\Entity\File $imgPublicity = null)
    {
        $this->imgPublicity = $imgPublicity;

        return $this;
    }

    /**
     * Get imgPublicity
     *
     * @return \AppBundle\Entity\File 
     */
    public function getImgPublicity()
    {
        return $this->imgPublicity;
    }

    /**
     * Set imgPublicityAnimation
     *
     * @param \AppBundle\Entity\Animation $imgPublicityAnimation
     * @return Business
     */
    public function setImgPublicityAnimation(\AppBundle\Entity\Animation $imgPublicityAnimation = null)
    {
        $this->imgPublicityAnimation = $imgPublicityAnimation;

        return $this;
    }

    /**
     * Get imgPublicityAnimation
     *
     * @return \AppBundle\Entity\Animation 
     */
    public function getImgPublicityAnimation()
    {
        return $this->imgPublicityAnimation;
    }

    /**
     * Set config
     *
     * @param \AppBundle\Entity\appConfig $config
     * @return Business
     */
    public function setConfig(\AppBundle\Entity\appConfig $config = null)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get config
     *
     * @return \AppBundle\Entity\appConfig 
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Add categories
     *
     * @param \AppBundle\Entity\Category $categories
     * @return Business
     */
    public function addCategory(\AppBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \AppBundle\Entity\Category $categories
     */
    public function removeCategory(\AppBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add schedules
     *
     * @param \AppBundle\Entity\Schedule $schedules
     * @return Business
     */
    public function addSchedule(\AppBundle\Entity\Schedule $schedules)
    {
        $schedules->setBusiness($this);
        $this->schedules[] = $schedules;

        return $this;
    }

    /**
     * Remove schedules
     *
     * @param \AppBundle\Entity\Schedule $schedules
     */
    public function removeSchedule(\AppBundle\Entity\Schedule $schedules)
    {
        $this->schedules->removeElement($schedules);
    }

    /**
     * Get schedules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSchedules()
    {
        return $this->schedules;
    }
}
