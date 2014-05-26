<?php

namespace Cloud\SyncserviceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Workspace
 *
 * @ORM\Table(name="workspace_user")
 * @ORM\Entity
 */
class WorkspaceUser
{
    /**
     * @var string
     *
     * @ORM\Column(name="client_workspace_path", type="string", length=100, nullable=true)
     */
    private $client_workspace_path;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="bigint", name="workspace_id")
     */
    private $workspace_id;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="bigint", name="user_id")
     */
    private $user_id;

    /**
     * Set client_workspace_path
     *
     * @param string $clientWorkspacePath
     * @return WorkspaceUser
     */
    public function setClientWorkspacePath($clientWorkspacePath)
    {
        $this->client_workspace_path = $clientWorkspacePath;
    
        return $this;
    }

    /**
     * Get client_workspace_path
     *
     * @return string 
     */
    public function getClientWorkspacePath()
    {
        return $this->client_workspace_path;
    }

    /**
     * Set workspace_id
     *
     * @param integer $workspaceId
     * @return WorkspaceUser
     */
    public function setWorkspaceId($workspaceId)
    {
        $this->workspace_id = $workspaceId;
    
        return $this;
    }

    /**
     * Get workspace_id
     *
     * @return integer 
     */
    public function getWorkspaceId()
    {
        return $this->workspace_id;
    }

    /**
     * Set user_id
     *
     * @param integer $userId
     * @return WorkspaceUser
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;
    
        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}