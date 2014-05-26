<?php

namespace Cloud\UsuarioBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Usuarios
 *
 * @ORM\Table(name="Usuarios")
 * @ORM\Entity(repositoryClass="Cloud\UsuarioBundle\Entity\UsuarioRepository")
 */
class Usuarios implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Nombre", type="string", length=100, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="Apellido1", type="string", length=100, nullable=false)
     */
    private $apellido1;

    /**
     * @var string
     *
     * @ORM\Column(name="Apellido2", type="string", length=100, nullable=false)
     */
    private $apellido2;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=255, unique=true, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="Contrasenya", type="string", length=255, nullable=false)
     */
    private $contrasenya;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaNacimiento", type="date", nullable=false)
     */
    private $fechanacimiento;

    /**
     * @var integer
     *
     * @ORM\Column(name="Movil", type="integer", nullable=true)
     */
    private $movil;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;

    /**
     * @var integer
     *
     * @ORM\Column(name="Admin", type="integer", nullable=false)
     */
    private $admin;

    /**
     * @var string
     *
     * @ORM\Column(name="Recordatorio", type="string", length=200, nullable=true)
     */
    private $recordatorio;

    /**
     * @var string
     *
     * @ORM\Column(name="Imagen", type="string", length=250, nullable=true)
     */
    private $imagen;

    /**
     * @var \Perfiles
     *
     * @ORM\ManyToOne(targetEntity="Perfiles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Perfil_id", referencedColumnName="id")
     * })
     */
    private $perfil;

    /**
     * @var \Idiomas
     *
     * @ORM\ManyToOne(targetEntity="Idiomas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Idioma", referencedColumnName="idIdioma")
     * })
     */
    private $idioma = 3;

    /**
     * @var \Estados
     *
     * @ORM\ManyToOne(targetEntity="Estados")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Estado_id", referencedColumnName="id")
     * })
     */
    private $estado;

    /**
     * @var \Grupos
     *
     * @ORM\ManyToOne(targetEntity="Grupos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Grupo_id", referencedColumnName="id")
     * })
     */
    private $grupo;

	/**
     * @var \DateTime
     *
     * @ORM\Column(name="Ultimo_acceso", type="datetime", nullable=true)
     */
    private $UltimoAcceso;


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
     * Set nombre
     *
     * @param string $nombre
     * @return Usuarios
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido1
     *
     * @param string $apellido1
     * @return Usuarios
     */
    public function setApellido1($apellido1)
    {
        $this->apellido1 = $apellido1;
    
        return $this;
    }

    /**
     * Get apellido1
     *
     * @return string 
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * Set apellido2
     *
     * @param string $apellido2
     * @return Usuarios
     */
    public function setApellido2($apellido2)
    {
        $this->apellido2 = $apellido2;
    
        return $this;
    }

    /**
     * Get apellido2
     *
     * @return string 
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuarios
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
     * Set contrasenya
     *
     * @param string $contrasenya
     * @return Usuarios
     */
    public function setContrasenya($contrasenya)
    {
        $this->contrasenya = $contrasenya;
    
        return $this;
    }

    /**
     * Get contrasenya
     *
     * @return string 
     */
    public function getContrasenya()
    {
        return $this->contrasenya;
    }

    /**
     * Set fechanacimiento
     *
     * @param \DateTime $fechanacimiento
     * @return Usuarios
     */
    public function setFechanacimiento($fechanacimiento)
    {
        $this->fechanacimiento = $fechanacimiento;
    
        return $this;
    }

    /**
     * Get fechanacimiento
     *
     * @return \DateTime 
     */
    public function getFechanacimiento()
    {
        return $this->fechanacimiento;
    }

    /**
     * Set UltimoAcceso
     *
     * @param \DateTime $UltimoAcceso
     * @return Usuarios
     */
    public function setUltimoacceso($UltimoAcceso)
    {
        $this->UltimoAcceso = $UltimoAcceso;
    
        return $this;
    }

    /**
     * Get UltimoAcceso
     *
     * @return string 
     */
    public function getUltimoacceso()
    {
    	if($this->UltimoAcceso != ''){
    		return $this->UltimoAcceso->format('d-m-Y H:i:s');	
    	}else{
    		return '';
    	}
        
    }

    /**
     * Set movil
     *
     * @param integer $movil
     * @return Usuarios
     */
    public function setMovil($movil)
    {
        $this->movil = $movil;
    
        return $this;
    }

    /**
     * Get movil
     *
     * @return integer 
     */
    public function getMovil()
    {
        return $this->movil;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuarios
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set admin
     *
     * @param integer $admin
     * @return Usuarios
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    
        return $this;
    }

    /**
     * Get admin
     *
     * @return integer 
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set recordatorio
     *
     * @param string $recordatorio
     * @return Usuarios
     */
    public function setRecordatorio($recordatorio)
    {
        $this->recordatorio = $recordatorio;
    
        return $this;
    }

    /**
     * Get recordatorio
     *
     * @return string 
     */
    public function getRecordatorio()
    {
        return $this->recordatorio;
    }

    /**
     * Set imagen
     *
     * @param string $imagen
     * @return Usuarios
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    
        return $this;
    }

    /**
     * Get imagen
     *
     * @return string 
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set perfil
     *
     * @param \Cloud\UsuarioBundle\Entity\Perfiles $perfil
     * @return Usuarios
     */
    public function setPerfil(\Cloud\UsuarioBundle\Entity\Perfiles $perfil = null)
    {
        $this->perfil = $perfil;
    
        return $this;
    }

    /**
     * Get perfil
     *
     * @return \Cloud\UsuarioBundle\Entity\Perfiles 
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Set idioma
     *
     * @param \Cloud\UsuarioBundle\Entity\Idiomas $idioma
     * @return Usuarios
     */
    public function setIdioma(\Cloud\UsuarioBundle\Entity\Idiomas $idioma = null)
    {
        $this->idioma = $idioma;
    
        return $this;
    }

    /**
     * Get idioma
     *
     * @return \Cloud\UsuarioBundle\Entity\Idiomas 
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * Set estado
     *
     * @param \Cloud\UsuarioBundle\Entity\Estados $estado
     * @return Usuarios
     */
    public function setEstado(\Cloud\UsuarioBundle\Entity\Estados $estado = null)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return \Cloud\UsuarioBundle\Entity\Estados 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set grupo
     *
     * @param \Cloud\UsuarioBundle\Entity\Grupos $grupo
     * @return Usuarios
     */
    public function setGrupo(\Cloud\UsuarioBundle\Entity\Grupos $grupo = null)
    {
        $this->grupo = $grupo;
    
        return $this;
    }

    /**
     * Get grupo
     *
     * @return \Cloud\UsuarioBundle\Entity\Grupos 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }
	
	function equals(\Symfony\Component\Security\Core\User\UserInterface $usuario)
	{
		return $this->getEmail() == $usuario->getEmail();
	}
	function eraseCredentials()
	{
	}
	
	function getRoles()
	{
        if (empty($this->admin)) return array('ROLE_USER');
        return array('ROLE_ADMIN', 'ROLE_USER');
	}
	
	function getUsername()
	{
		return $this->getEmail();
	}
	
	/**
     * Get Password
     *
     * @return string 
     */
    public function getPassword()
    {        	
        return $this->contrasenya;
    }
	
	
	/**
     * Set Password
     *
     * @param string $contrase�a
     * @return Usuarios
     */
    public function setPassword($contrasenya)
    {
        $this->contrasenya = $contrasenya;
    
        return $this;
    }	
	
	public function __sleep()
    {
        return array('id');
    }


}