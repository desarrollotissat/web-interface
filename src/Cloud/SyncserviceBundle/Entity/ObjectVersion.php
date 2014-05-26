<?php

namespace Cloud\SyncserviceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObjectVersion
 *
 * @ORM\Table(name="object_version")
 * @ORM\Entity
 */
class ObjectVersion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="version", type="integer", nullable=false)
     */
    private $version;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_checksum", type="bigint", nullable=false)
     */
    private $clientChecksum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="client_mtime", type="datetime", nullable=true)
     */
    private $clientMtime;

    /**
     * @var string
     *
     * @ORM\Column(name="client_status", type="string", length=10, nullable=false)
     */
    private $clientStatus;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_file_size", type="bigint", nullable=false)
     */
    private $clientFileSize;

    /**
     * @var string
     *
     * @ORM\Column(name="client_name", type="string", length=45, nullable=false)
     */
    private $clientName;

    /**
     * @var string
     *
     * @ORM\Column(name="client_path", type="string", length=100, nullable=false)
     */
    private $clientPath;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sequencer_object_version", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Cloud\SyncserviceBundle\Entity\Device
     *
     * @ORM\ManyToOne(targetEntity="Cloud\SyncserviceBundle\Entity\Device")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="device_id", referencedColumnName="id")
     * })
     */
    private $device;

    /**
     * @var \Cloud\SyncserviceBundle\Entity\Object
     *
     * @ORM\ManyToOne(targetEntity="Cloud\SyncserviceBundle\Entity\Object")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="object_id", referencedColumnName="id")
     * })
     */
    private $object;



    /**
     * Set version
     *
     * @param integer $version
     * @return ObjectVersion
     */
    public function setVersion($version)
    {
        $this->version = $version;
    
        return $this;
    }

    /**
     * Get version
     *
     * @return integer 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     * @return ObjectVersion
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
    
        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime 
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set clientChecksum
     *
     * @param integer $clientChecksum
     * @return ObjectVersion
     */
    public function setClientChecksum($clientChecksum)
    {
        $this->clientChecksum = $clientChecksum;
    
        return $this;
    }

    /**
     * Get clientChecksum
     *
     * @return integer 
     */
    public function getClientChecksum()
    {
        return $this->clientChecksum;
    }

    /**
     * Set clientMtime
     *
     * @param \DateTime $clientMtime
     * @return ObjectVersion
     */
    public function setClientMtime($clientMtime)
    {
        $this->clientMtime = $clientMtime;
    
        return $this;
    }

    /**
     * Get clientMtime
     *
     * @return \DateTime 
     */
    public function getClientMtime()
    {
        return $this->clientMtime;
    }

    /**
     * Set clientStatus
     *
     * @param string $clientStatus
     * @return ObjectVersion
     */
    public function setClientStatus($clientStatus)
    {
        $this->clientStatus = $clientStatus;
    
        return $this;
    }

    /**
     * Get clientStatus
     *
     * @return string 
     */
    public function getClientStatus()
    {
        return $this->clientStatus;
    }

    /**
     * Set clientFileSize
     *
     * @param integer $clientFileSize
     * @return ObjectVersion
     */
    public function setClientFileSize($clientFileSize)
    {
        $this->clientFileSize = $clientFileSize;
    
        return $this;
    }

    /**
     * Get clientFileSize
     *
     * @return integer 
     */
    public function getClientFileSize()
    {
        return $this->clientFileSize;
    }

    /**
     * Set clientName
     *
     * @param string $clientName
     * @return ObjectVersion
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;
    
        return $this;
    }

    /**
     * Get clientName
     *
     * @return string 
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Set clientPath
     *
     * @param string $clientPath
     * @return ObjectVersion
     */
    public function setClientPath($clientPath)
    {
        $this->clientPath = $clientPath;
    
        return $this;
    }

    /**
     * Get clientPath
     *
     * @return string 
     */
    public function getClientPath()
    {
        return $this->clientPath;
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
     * Set device
     *
     * @param \Cloud\SyncserviceBundle\Entity\Device $device
     * @return ObjectVersion
     */
    public function setDevice(\Cloud\SyncserviceBundle\Entity\Device $device = null)
    {
        $this->device = $device;
    
        return $this;
    }

    /**
     * Get device
     *
     * @return \Cloud\SyncserviceBundle\Entity\Device 
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set object
     *
     * @param \Cloud\SyncserviceBundle\Entity\Object $object
     * @return ObjectVersion
     */
    public function setObject(\Cloud\SyncserviceBundle\Entity\Object $object = null)
    {
        $this->object = $object;
    
        return $this;
    }

    /**
     * Get object
     *
     * @return \Cloud\SyncserviceBundle\Entity\Object 
     */
    public function getObject()
    {
        return $this->object;
    }
}