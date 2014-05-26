<?php

namespace Cloud\SyncserviceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User1
 *
 * @ORM\Table(name="user1")
 * @ORM\Entity
 */
class User1
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="cloud_id", type="string", length=100, nullable=false)
     */
    private $cloudId;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="quota_limit", type="integer", nullable=false)
     */
    private $quotaLimit;

    /**
     * @var integer
     *
     * @ORM\Column(name="quota_used", type="integer", nullable=false)
     */
    private $quotaUsed;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sequencer_user", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Cloud\SyncserviceBundle\Entity\Workspace", mappedBy="user")
     */
    private $workspace;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->workspace = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Set name
     *
     * @param string $name
     * @return User1
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
     * Set cloudId
     *
     * @param string $cloudId
     * @return User1
     */
    public function setCloudId($cloudId)
    {
        $this->cloudId = $cloudId;
    
        return $this;
    }

    /**
     * Get cloudId
     *
     * @return string 
     */
    public function getCloudId()
    {
        return $this->cloudId;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User1
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
     * Set quotaLimit
     *
     * @param integer $quotaLimit
     * @return User1
     */
    public function setQuotaLimit($quotaLimit)
    {
        $this->quotaLimit = $quotaLimit;
    
        return $this;
    }

    /**
     * Get quotaLimit
     *
     * @return integer 
     */
    public function getQuotaLimit()
    {
        return $this->quotaLimit;
    }

    /**
     * Set quotaUsed
     *
     * @param integer $quotaUsed
     * @return User1
     */
    public function setQuotaUsed($quotaUsed)
    {
        $this->quotaUsed = $quotaUsed;
    
        return $this;
    }

    /**
     * Get quotaUsed
     *
     * @return integer 
     */
    public function getQuotaUsed()
    {
        return $this->quotaUsed;
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
     * Add workspace
     *
     * @param \Cloud\SyncserviceBundle\Entity\Workspace $workspace
     * @return User1
     */
    public function addWorkspace(\Cloud\SyncserviceBundle\Entity\Workspace $workspace)
    {
        $this->workspace[] = $workspace;
    
        return $this;
    }

    /**
     * Remove workspace
     *
     * @param \Cloud\SyncserviceBundle\Entity\Workspace $workspace
     */
    public function removeWorkspace(\Cloud\SyncserviceBundle\Entity\Workspace $workspace)
    {
        $this->workspace->removeElement($workspace);
    }

    /**
     * Get workspace
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }
}