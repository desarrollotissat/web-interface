<?php

namespace Cloud\SyncserviceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObjectVersionChunk
 *
 * @ORM\Table(name="object_version_chunk")
 * @ORM\Entity
 */
class ObjectVersionChunk
{
    /**
     * @var string
     *
     * @ORM\Column(name="client_chunk_name", type="string", length=40)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $clientChunkName;

    /**
     * @var integer
     *
     * @ORM\Column(name="chunk_order", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $chunkOrder;

    /**
     * @var \Cloud\SyncserviceBundle\Entity\ObjectVersion
     *
     * @ORM\OneToOne(targetEntity="Cloud\SyncserviceBundle\Entity\ObjectVersion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="object_version_id", referencedColumnName="id", unique=true)
     * })
     */
    private $objectVersion;



    /**
     * Set clientChunkName
     *
     * @param string $clientChunkName
     * @return ObjectVersionChunk
     */
    public function setClientChunkName($clientChunkName)
    {
        $this->clientChunkName = $clientChunkName;
    
        return $this;
    }

    /**
     * Get clientChunkName
     *
     * @return string 
     */
    public function getClientChunkName()
    {
        return $this->clientChunkName;
    }

    /**
     * Set chunkOrder
     *
     * @param integer $chunkOrder
     * @return ObjectVersionChunk
     */
    public function setChunkOrder($chunkOrder)
    {
        $this->chunkOrder = $chunkOrder;
    
        return $this;
    }

    /**
     * Get chunkOrder
     *
     * @return integer 
     */
    public function getChunkOrder()
    {
        return $this->chunkOrder;
    }

    /**
     * Set objectVersion
     *
     * @param \Cloud\SyncserviceBundle\Entity\ObjectVersion $objectVersion
     * @return ObjectVersionChunk
     */
    public function setObjectVersion(\Cloud\SyncserviceBundle\Entity\ObjectVersion $objectVersion = null)
    {
        $this->objectVersion = $objectVersion;
    
        return $this;
    }

    /**
     * Get objectVersion
     *
     * @return \Cloud\SyncserviceBundle\Entity\ObjectVersion 
     */
    public function getObjectVersion()
    {
        return $this->objectVersion;
    }
}