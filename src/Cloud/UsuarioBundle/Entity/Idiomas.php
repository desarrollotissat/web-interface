<?php

namespace Cloud\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Idiomas
 *
 * @ORM\Table(name="Idiomas")
 * @ORM\Entity
 */
class Idiomas
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idIdioma", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ididioma;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreIdioma", type="string", length=200, nullable=true)
     */
    private $nombreidioma;



    /**
     * Get ididioma
     *
     * @return integer 
     */
    public function getIdidioma()
    {
        return $this->ididioma;
    }

    /**
     * Set nombreidioma
     *
     * @param string $nombreidioma
     * @return Idiomas
     */
    public function setNombreidioma($nombreidioma)
    {
        $this->nombreidioma = $nombreidioma;
    
        return $this;
    }

    /**
     * Get nombreidioma
     *
     * @return string 
     */
    public function getNombreidioma()
    {
        return $this->nombreidioma;
    }
}