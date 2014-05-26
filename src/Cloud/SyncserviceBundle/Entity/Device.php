<?php

namespace Cloud\SyncserviceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="device")
 * @ORM\Entity
 */
class Device
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sequencer_device", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Cloud\SyncserviceBundle\Entity\User1
     *
     * @ORM\ManyToOne(targetEntity="Cloud\SyncserviceBundle\Entity\User1")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;



    /**
     * Set name
     *
     * @param string $name
     * @return Device
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \Cloud\SyncserviceBundle\Entity\User1 $user
     * @return Device
     */
    public function setUser(\Cloud\SyncserviceBundle\Entity\User1 $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Cloud\SyncserviceBundle\Entity\User1 
     */
    public function getUser()
    {
        return $this->user;
    }
}