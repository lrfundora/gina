<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class appConfig
 * @ORM\Table(name="appConfig")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\appConfigRepository")
 */
class appConfig
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
    protected $system;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $infoTitle;

    /**
     * @ORM\Column(type="string", length=5000)
     */
    protected $infoText;

    /**
     * @ORM\ManyToMany(targetEntity="File", orphanRemoval=true)
     * @ORM\JoinTable(
     *  name="info_images",
     *  joinColumns={@ORM\JoinColumn(name="appconfig_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id")}
     * )
     */
    protected $infoImages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $slogan;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $sloganOffer;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    protected $publicity;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $owner;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $showCarousel;

    /**
     * @ORM\ManyToMany(targetEntity="File", orphanRemoval=true)
     * @ORM\JoinTable(
     *  name="carousel_images",
     *  joinColumns={@ORM\JoinColumn(name="appconfig_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id")}
     * )
     */
    protected $carouselImages;

    /**
     * @ORM\OneToOne(targetEntity="File", orphanRemoval=true)
     * @ORM\JoinColumn(name="logoTop", referencedColumnName="id")
     */
    protected $logoTop;

    /**
     * @ORM\OneToOne(targetEntity="File", orphanRemoval=true)
     * @ORM\JoinColumn(name="logoDown", referencedColumnName="id")
     */
    protected $logoDown;

    /**
     * @ORM\OneToOne(targetEntity="File", orphanRemoval=true)
     * @ORM\JoinColumn(name="imgParallax", referencedColumnName="id")
     */
    protected $imgParallax;

    /**
     * @ORM\OneToOne(targetEntity="File", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="imgOffer", referencedColumnName="id")
     */
    protected $imgOffer;

    /**
     * @ORM\OneToMany(targetEntity="Business", mappedBy="config", cascade={"remove"}, indexBy="name")
     * @ORM\OrderBy({"name"="ASC"})
     */
    protected $business;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->infoImages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carouselImages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->business = new \Doctrine\Common\Collections\ArrayCollection();
        $this->system = 'system';
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
     * Set system
     *
     * @param string $system
     * @return appConfig
     */
    public function setSystem($system)
    {
        $this->system = $system;

        return $this;
    }

    /**
     * Get system
     *
     * @return string
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return appConfig
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
     * Set infoTitle
     *
     * @param string $infoTitle
     * @return appConfig
     */
    public function setInfoTitle($infoTitle)
    {
        $this->infoTitle = $infoTitle;

        return $this;
    }

    /**
     * Get infoTitle
     *
     * @return string
     */
    public function getInfoTitle()
    {
        return $this->infoTitle;
    }

    /**
     * Set infoText
     *
     * @param string $infoText
     * @return appConfig
     */
    public function setInfoText($infoText)
    {
        $this->infoText = $infoText;

        return $this;
    }

    /**
     * Get infoText
     *
     * @return string
     */
    public function getInfoText()
    {
        return $this->infoText;
    }

    /**
     * Set slogan
     *
     * @param string $slogan
     * @return appConfig
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
     * Set sloganOffer
     *
     * @param string $sloganOffer
     * @return appConfig
     */
    public function setSloganOffer($sloganOffer)
    {
        $this->sloganOffer = $sloganOffer;

        return $this;
    }

    /**
     * Get sloganOffer
     *
     * @return string
     */
    public function getSloganOffer()
    {
        return $this->sloganOffer;
    }

    /**
     * Set publicity
     *
     * @param string $publicity
     * @return appConfig
     */
    public function setPublicity($publicity)
    {
        $this->publicity = $publicity;

        return $this;
    }

    /**
     * Get publicity
     *
     * @return string
     */
    public function getPublicity()
    {
        return $this->publicity;
    }

    /**
     * Set emailSupport
     *
     * @param string $emailSupport
     * @return appConfig
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get emailSupport
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return appConfig
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
     * @return appConfig
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
     * Set owner
     *
     * @param string $owner
     * @return appConfig
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set showCarousel
     *
     * @param boolean $showCarousel
     * @return appConfig
     */
    public function setShowCarousel($showCarousel)
    {
        $this->showCarousel = $showCarousel;

        return $this;
    }

    /**
     * Get showCarousel
     *
     * @return boolean
     */
    public function getShowCarousel()
    {
        return $this->showCarousel;
    }

    /**
     * Add infoImages
     *
     * @param \AppBundle\Entity\File $infoImages
     * @return appConfig
     */
    public function addInfoImage(\AppBundle\Entity\File $infoImages)
    {
        $this->infoImages[] = $infoImages;

        return $this;
    }

    /**
     * Remove infoImages
     *
     * @param \AppBundle\Entity\File $infoImages
     */
    public function removeInfoImage(\AppBundle\Entity\File $infoImages)
    {
        $this->infoImages->removeElement($infoImages);
    }

    /**
     * Get infoImages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInfoImages()
    {
        return $this->infoImages;
    }

    /**
     * Add carouselImages
     *
     * @param \AppBundle\Entity\File $carouselImages
     * @return appConfig
     */
    public function addCarouselImage(\AppBundle\Entity\File $carouselImages)
    {
        $this->carouselImages[] = $carouselImages;

        return $this;
    }

    /**
     * Remove carouselImages
     *
     * @param \AppBundle\Entity\File $carouselImages
     */
    public function removeCarouselImage(\AppBundle\Entity\File $carouselImages)
    {
        $this->carouselImages->removeElement($carouselImages);
    }

    /**
     * Get carouselImages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarouselImages()
    {
        return $this->carouselImages;
    }

    /**
     * Set logoTop
     *
     * @param \AppBundle\Entity\File $logoTop
     * @return appConfig
     */
    public function setLogoTop(\AppBundle\Entity\File $logoTop = null)
    {
        $this->logoTop = $logoTop;

        return $this;
    }

    /**
     * Get logoTop
     *
     * @return \AppBundle\Entity\File
     */
    public function getLogoTop()
    {
        return $this->logoTop;
    }

    /**
     * Set logoDown
     *
     * @param \AppBundle\Entity\File $logoDown
     * @return appConfig
     */
    public function setLogoDown(\AppBundle\Entity\File $logoDown = null)
    {
        $this->logoDown = $logoDown;

        return $this;
    }

    /**
     * Get logoDown
     *
     * @return \AppBundle\Entity\File
     */
    public function getLogoDown()
    {
        return $this->logoDown;
    }

    /**
     * Set imgParallax
     *
     * @param \AppBundle\Entity\File $imgParallax
     * @return appConfig
     */
    public function setImgParallax(\AppBundle\Entity\File $imgParallax = null)
    {
        $this->imgParallax = $imgParallax;

        return $this;
    }

    /**
     * Get imgParallax
     *
     * @return \AppBundle\Entity\File
     */
    public function getImgParallax()
    {
        return $this->imgParallax;
    }

    /**
     * Set imgOffer
     *
     * @param \AppBundle\Entity\File $imgOffer
     * @return appConfig
     */
    public function setImgOffer(\AppBundle\Entity\File $imgOffer = null)
    {
        $this->imgOffer = $imgOffer;

        return $this;
    }

    /**
     * Get imgOffer
     *
     * @return \AppBundle\Entity\File
     */
    public function getImgOffer()
    {
        return $this->imgOffer;
    }

    /**
     * Add business
     *
     * @param \AppBundle\Entity\Business $business
     * @return appConfig
     */
    public function addBusiness(\AppBundle\Entity\Business $business)
    {
        $this->business[] = $business;

        return $this;
    }

    /**
     * Remove business
     *
     * @param \AppBundle\Entity\Business $business
     */
    public function removeBusiness(\AppBundle\Entity\Business $business)
    {
        $this->business->removeElement($business);
    }

    /**
     * Get business
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBusiness()
    {
        return $this->business;
    }
}
