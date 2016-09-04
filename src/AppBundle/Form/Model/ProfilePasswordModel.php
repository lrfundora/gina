<?php
namespace AppBundle\Form\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class ProfilePasswordModel
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $oldPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *  pattern="((?=.*\d)(?=.*[a-z])(?=.*[A-Z]))",
     *  message="La contraseña es muy débil. Debe tener al menos 5 caracteres compuestos por mayúscula, minúscula, números y opcionalmente signos o símbolos."
     * )
     */
    protected $password;

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set oldPassword
     * @param string $oldPassword
     */
    public function setOldPassword($oldpassword)
    {
        $this->oldpassword = $oldpassword;

        return $this;
    }

    /**
     * Get oldPassword
     * @return string
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * Get password
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

} 