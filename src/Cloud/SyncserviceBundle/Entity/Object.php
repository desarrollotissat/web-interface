<?php

namespace Cloud\SyncserviceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Object
 *
 * @ORM\Table(name="object")
 * @ORM\Entity
 */
class Object
{
    /**
     * @var string
     *
     * @ORM\Column(name="root_id", type="string", length=45, nullable=false)
     */
    private $rootId;

    /**
     * @var integer
     *
     * @ORM\Column(name="latest_version", type="bigint", nullable=false)
     */
    private $latestVersion;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_file_id", type="bigint", nullable=false)
     */
    private $clientFileId;

    /**
     * @var string
     *
     * @ORM\Column(name="client_file_name", type="string", length=100, nullable=false)
     */
    private $clientFileName;

    /**
     * @var string
     *
     * @ORM\Column(name="client_file_mimetype", type="string", length=45, nullable=false)
     */
    private $clientFileMimetype;

    /**
     * @var boolean
     *
     * @ORM\Column(name="client_folder", type="boolean", nullable=false)
     */
    private $clientFolder;

    /**
     * @var string
     *
     * @ORM\Column(name="client_parent_root_id", type="string", length=45, nullable=true)
     */
    private $clientParentRootId;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_parent_file_id", type="bigint", nullable=true)
     */
    private $clientParentFileId;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_parent_file_version", type="bigint", nullable=true)
     */
    private $clientParentFileVersion;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="sequencer_object", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Cloud\SyncserviceBundle\Entity\Object
     *
     * @ORM\ManyToOne(targetEntity="Cloud\SyncserviceBundle\Entity\Object")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var \Cloud\SyncserviceBundle\Entity\Workspace
     *
     * @ORM\ManyToOne(targetEntity="Cloud\SyncserviceBundle\Entity\Workspace")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="workspace_id", referencedColumnName="id")
     * })
     */
    private $workspace;



    /**
     * Set rootId
     *
     * @param string $rootId
     * @return Object
     */
    public function setRootId($rootId)
    {
        $this->rootId = $rootId;
    
        return $this;
    }

    /**
     * Get rootId
     *
     * @return string 
     */
    public function getRootId()
    {
        return $this->rootId;
    }

    /**
     * Set latestVersion
     *
     * @param integer $latestVersion
     * @return Object
     */
    public function setLatestVersion($latestVersion)
    {
        $this->latestVersion = $latestVersion;
    
        return $this;
    }

    /**
     * Get latestVersion
     *
     * @return integer 
     */
    public function getLatestVersion()
    {
        return $this->latestVersion;
    }

    /**
     * Set clientFileId
     *
     * @param integer $clientFileId
     * @return Object
     */
    public function setClientFileId($clientFileId)
    {
        $this->clientFileId = $clientFileId;
    
        return $this;
    }

    /**
     * Get clientFileId
     *
     * @return integer 
     */
    public function getClientFileId()
    {
        return $this->clientFileId;
    }

    /**
     * Set clientFileName
     *
     * @param string $clientFileName
     * @return Object
     */
    public function setClientFileName($clientFileName)
    {
        $this->clientFileName = $clientFileName;
    
        return $this;
    }

    /**
     * Get clientFileName
     *
     * @return string 
     */
    public function getClientFileName()
    {
        return $this->clientFileName;
    }

    /**
     * Set clientFileMimetype
     *
     * @param string $clientFileMimetype
     * @return Object
     */
    public function setClientFileMimetype($clientFileMimetype)
    {
        $this->clientFileMimetype = $clientFileMimetype;
    
        return $this;
    }

    /**
     * Get clientFileMimetype
     *
     * @return string 
     */
    public function getClientFileMimetype()
    {
        return $this->clientFileMimetype;
    }

    /**
     * Set clientFolder
     *
     * @param boolean $clientFolder
     * @return Object
     */
    public function setClientFolder($clientFolder)
    {
        $this->clientFolder = $clientFolder;
    
        return $this;
    }

    /**
     * Get clientFolder
     *
     * @return boolean 
     */
    public function getClientFolder()
    {
        return $this->clientFolder;
    }

    /**
     * Set clientParentRootId
     *
     * @param string $clientParentRootId
     * @return Object
     */
    public function setClientParentRootId($clientParentRootId)
    {
        $this->clientParentRootId = $clientParentRootId;
    
        return $this;
    }

    /**
     * Get clientParentRootId
     *
     * @return string 
     */
    public function getClientParentRootId()
    {
        return $this->clientParentRootId;
    }

    /**
     * Set clientParentFileId
     *
     * @param integer $clientParentFileId
     * @return Object
     */
    public function setClientParentFileId($clientParentFileId)
    {
        $this->clientParentFileId = $clientParentFileId;
    
        return $this;
    }

    /**
     * Get clientParentFileId
     *
     * @return integer 
     */
    public function getClientParentFileId()
    {
        return $this->clientParentFileId;
    }

    /**
     * Set clientParentFileVersion
     *
     * @param integer $clientParentFileVersion
     * @return Object
     */
    public function setClientParentFileVersion($clientParentFileVersion)
    {
        $this->clientParentFileVersion = $clientParentFileVersion;
    
        return $this;
    }

    /**
     * Get clientParentFileVersion
     *
     * @return integer 
     */
    public function getClientParentFileVersion()
    {
        return $this->clientParentFileVersion;
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
     * Set parent
     *
     * @param \Cloud\SyncserviceBundle\Entity\Object $parent
     * @return Object
     */
    public function setParent(\Cloud\SyncserviceBundle\Entity\Object $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Cloud\SyncserviceBundle\Entity\Object 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set workspace
     *
     * @param \Cloud\SyncserviceBundle\Entity\Workspace $workspace
     * @return Object
     */
    public function setWorkspace(\Cloud\SyncserviceBundle\Entity\Workspace $workspace = null)
    {
        $this->workspace = $workspace;
    
        return $this;
    }

    /**
     * Get workspace
     *
     * @return \Cloud\SyncserviceBundle\Entity\Workspace 
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }
}