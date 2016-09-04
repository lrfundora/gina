<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File as Image;

/**
 * Class File
 * @ORM\Table()
 * @ORM\Entity
 * @Vich\Uploadable
 */
class File
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Image $file
     * @Vich\UploadableField(mapping="images", fileNameProperty="name")
     */
    protected $file;

    /**
     * @ORM\Column(type="string")
     * @var string $name
     */
    protected $name;

    /**
     * @var \DateTime $updated
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $updated;


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
     * @param Image|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFile(Image $file = null)
    {
        $this->file = $file;

        if ($file) {
            $this->updated = new \DateTime('now');
        }
    }

    /**
     * @return Image
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return File
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
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return 'bundles/app/images/'.$this->name;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return File
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
