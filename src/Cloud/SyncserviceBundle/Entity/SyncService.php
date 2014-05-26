<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 8/11/13
 * Time: 11:22
 * To change this template use File | Settings | File Templates.
 */

namespace Cloud\SyncserviceBundle\Entity;

/*
* This class creates data on the three Syncservice tables
 *
 * THIS IS A WRAPPER CLASS OF USER1, this object register a new User, setting the correct options.
 * Configures sacksync user
 */



class SyncService
{
    protected $em;
    private $user;
    private $workspace;
    private $workspace_user;

    public function __construct($em) {
        $this->em = $em;
    }

    public function addUser($email, $user_cloud_id, $quota_limit){
        $this->user = new User1();
        $this->user->setName($email);
        $this->user->setEmail($email);
        $this->user->setCloudId($user_cloud_id);
        $this->user->setQuotaLimit($quota_limit); //100
        $this->user->setQuotaUsed(0);

        $this->em->persist($this->user);

        $this->workspace = new Workspace();
        $this->workspace->setClientWorkspaceName($this->user->getCloudId() .'/');
        $this->workspace->setLatestRevision(0);
        $this->workspace->setOwner($this->user);
        $this->em->persist($this->workspace);
        $this->em->flush();

        $this->workspace_user = new WorkspaceUser();
        $this->workspace_user->setClientWorkspacePath("/");
        $this->workspace_user->setUserId($this->user->getId());
        $this->workspace_user->setWorkspaceId($this->workspace->getId());
        $this->em->persist($this->workspace_user);
        $this->em->flush();

        return $this->user->getId();

    }

    public function deleteUser($user_id){
        $user_stacksync = $this->em->getRepository('CloudSyncserviceBundle:User1')->find($user_id);
        $this->em->remove($user_stacksync);
        $this->em->flush();
    }

    public function deleteUserByName($name){
        $user_stacksync = $this->em->getRepository('CloudSyncserviceBundle:User1')->findOneByName($name);
        $this->em->remove($user_stacksync);
        $this->em->flush();
    }

    public function changeQuotaLimitByName($name, $quota_limit){
        $user_stacksync = $this->em->getRepository('CloudSyncserviceBundle:User1')->findOneByName($name);
        $user_stacksync->setQuotaLimit($quota_limit);
        $this->em->persist($user_stacksync);
        $this->em->flush();
    }

    public function getQuotas(){
        // array["email@email.com"] = {"used"=> 20, "limit" => 30}
        $stacksync_users = $this->em->getRepository('CloudSyncserviceBundle:User1')->findAll();
        $quotas = array();
        foreach($stacksync_users as $user){
            $quotas[$user->getName()] = array(
                "used" => $user->getQuotaUsed(),
                "limit" => $user->getQuotaLimit());
        }

        return $quotas;
    }


}