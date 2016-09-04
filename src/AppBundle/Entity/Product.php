<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Product
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ProductRepository")
 */
class Product
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
     * @ORM\ManyToMany(targetEntity="File", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinTable(
     *  name="product_images",
     *  joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id")}
     * )
     */
    protected $photos;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    protected $description;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $price;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isOffer;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $publicated;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $expired;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $discount;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    protected $conditions;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\OrderBy({"name"="ASC"})
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="product", cascade={"remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"date"="ASC"})
     */
    protected $comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isOffer = false;
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
     * @return Product
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
     * @return Product
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
     * Set description
     *
     * @param string $description
     * @return Product
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
     * Set price
     *
     * @param string $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set isOffer
     *
     * @param boolean $isOffer
     * @return Product
     */
    public function setIsOffer($isOffer)
    {
        $this->isOffer = $isOffer;

        return $this;
    }

    /**
     * Get isOffer
     *
     * @return boolean
     */
    public function getIsOffer()
    {
        return $this->isOffer;
    }

    /**
     * Set publicated
     *
     * @param string $publicated
     * @return Product
     */
    public function setPublicated($publicated)
    {
        $this->publicated = $publicated;

        return $this;
    }

    /**
     * Get publicated
     *
     * @return string
     */
    public function getPublicated()
    {
        return $this->publicated;
    }

    /**
     * Set expired
     *
     * @param string $expired
     * @return Product
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;

        return $this;
    }

    /**
     * Get expired
     *
     * @return string
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * Set discount
     *
     * @param string $discount
     * @return Product
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount / 100;

        return $this;
    }

    /**
     * Get discount
     *
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount * 100;
    }

    /**
     * Set conditions
     *
     * @param string $conditions
     * @return Product
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;

        return $this;
    }

    /**
     * Get conditions
     *
     * @return string
     */
    public function getConditions()
    {
//        if ($this->conditions === null) {
//            return 'Sin condiciones';
//        }

        return $this->conditions;
    }

    /**
     * Add photos
     *
     * @param \AppBundle\Entity\File $photos
     * @return Product
     */
    public function addPhoto(\AppBundle\Entity\File $photos)
    {

        $this->photos[] = $photos;

        return $this;
    }

    /**
     * Remove photos
     *
     * @param \AppBundle\Entity\File $photos
     */
    public function removePhoto(\AppBundle\Entity\File $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     * @return Product
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add comments
     *
     * @param \AppBundle\Entity\Comment $comments
     * @return Product
     */
    public function addComment(\AppBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \AppBundle\Entity\Comment $comments
     */
    public function removeComment(\AppBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
