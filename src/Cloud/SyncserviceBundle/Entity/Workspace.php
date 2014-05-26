<?php

namespace Cloud\SyncserviceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Workspace
 *
 * @ORM\Table(name="workspace")
 * @ORM\Entity
 */
class Workspace
{
    /**
     * @var string
     *
     * @ORM\Column(name="client_workspace_name", type="string", length=45, nullable=true)
     */
    private $clientWorkspaceName;

    /**
     * @var string
     *
     * @ORM\Column(name="latest_revision", type="string", length=45, nullable=false)
     */
    private $latestRevision;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sequencer_workspace", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Cloud\SyncserviceBundle\Entity\User1
     *
     * @ORM\ManyToOne(targetEntity="Cloud\SyncserviceBundle\Entity\User1")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     * })
     */
    private $owner;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Cloud\SyncserviceBundle\Entity\User1", inversedBy="workspace")
     * @ORM\JoinTable(name="workspace_user",
     *   joinColumns={
     *     @ORM\JoinColumn(name="workspace_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   }
     * )
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Set clientWorkspaceName
     *
     * @param string $clientWorkspaceName
     * @return Workspace
     */
    public function setClientWorkspaceName($clientWorkspaceName)
    {
        $this->clientWorkspaceName = $clientWorkspaceName;
    
        return $this;
    }

    /**
     * Get clientWorkspaceName
     *
     * @return string 
     */
    public function getClientWorkspaceName()
    {
        return $this->clientWorkspaceName;
    }

    /**
     * Set latestRevision
     *
     * @param string $latestRevision
     * @return Workspace
     */
    public function setLatestRevision($latestRevision)
    {
        $this->latestRevision = $latestRevision;
    
        return $this;
    }

    /**
     * Get latestRevision
     *
     * @return string 
     */
    public function getLatestRevision()
    {
        return $this->latestRevision;
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
     * Set owner
     *
     * @param \Cloud\SyncserviceBundle\Entity\User1 $owner
     * @return Workspace
     */
    public function setOwner(\Cloud\SyncserviceBundle\Entity\User1 $owner = null)
    {
        $this->owner = $owner;
    
        return $this;
    }

    /**
     * Get owner
     *
     * @return \Cloud\SyncserviceBundle\Entity\User1 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add user
     *
     * @param \Cloud\SyncserviceBundle\Entity\User1 $user
     * @return Workspace
     */
    public function addUser(\Cloud\SyncserviceBundle\Entity\User1 $user)
    {
        $this->user[] = $user;
    
        return $this;
    }

    /**
     * Remove user
     *
     * @param \Cloud\SyncserviceBundle\Entity\User1 $user
     */
    public function removeUser(\Cloud\SyncserviceBundle\Entity\User1 $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }
}