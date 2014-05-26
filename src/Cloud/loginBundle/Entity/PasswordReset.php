<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 17/09/13
 * Time: 11:17
 * To change this template use File | Settings | File Templates.
 */

namespace Cloud\loginBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="passwordreset")
 */
class PasswordReset
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", unique=true, length=255, nullable=false)
     */
    protected $token;

    /**
     * @ORM\OneToOne(targetEntity="Cloud\UsuarioBundle\Entity\Usuarios")
     */
    protected $usuario;


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
     * Set token
     *
     * @param string $token
     * @return PasswordReset
     */
    public function setToken($token)
    {
        $this->token = $token;
    
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set usuario
     *
     * @param \Cloud\loginBundle\Entity\Usuarios $usuario
     * @return PasswordReset
     */
    public function setUsuario(\Cloud\UsuarioBundle\Entity\Usuarios $usuario = null)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Cloud\loginBundle\Entity\Usuarios 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}