<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 8/11/13
 * Time: 15:53
 * To change this template use File | Settings | File Templates.
 */

namespace Cloud\StorageSystemBundle\Utility;


use Cloud\SyncserviceBundle\Entity\SyncService;

class StorageUserService {

    private $keystone; //class Identity
    private $syncservice;
    private $swift;

    public function __construct(Identity $identity, SyncService $syncservice,Swift $swift ) {
        $this->keystone = $identity;
        $this->syncservice = $syncservice;
        $this->swift = $swift;
    }

    public function add($email, $password, $quota){
       //REGISTER IN KEYSTONE
        $tenant_json=$this->keystone->addTenant($email);
        $tenant_id = $tenant_json->tenant->id;
        $keystone_user_json = $this->keystone->addUser($email, $password, $tenant_id);
        $keystone_user_id = $keystone_user_json->user->id;

        //Assign role to tenant:user
        $role = $this->keystone->findRoleByName("admin");
        $response_role = $this->keystone->addRoletoUser($role->id, $tenant_id, $keystone_user_id);

        //Assign container to tenant
        $container = "stacksync";
        $this->swift->setUsername($email);
        $this->swift->setTenant($email);
        $this->swift->setPassword($password);
        $this->swift->setContainer($container);
        $this->swift->getAuthTokens();
        $this->swift->createContainer($container);

        //REGISTER IN SYNCSERVICE
        $syncservice_user_id = $this->syncservice->addUser($email, 'AUTH_'. $tenant_id, $quota);

        return array($keystone_user_id, $tenant_id, $syncservice_user_id);
    }

    public function delete($keystone_user_id, $tenant_id, $syncservice_user_id){
        $this->keystone->deleteUser($keystone_user_id);
        $this->keystone->deleteTenant($tenant_id);

        $this->syncservice->deleteUser($syncservice_user_id);
    }

    public function deleteUserByName($name){
        $this->keystone->deleteUserByName($name);
        $this->keystone->deleteTenantByName($name);
        $this->syncservice->deleteUserByName($name);
    }

    public function updatePassword($name, $password){
        $user = $this->keystone->findUserByName($name);
        $this->keystone->updatePassword($user->id, $password);
    }

    public function updateQuota($name, $quota){
        $this->syncservice->changeQuotaLimitByName($name, $quota);
    }

    public function getQuotasPerUser(){
        return $this->syncservice->getQuotas();
    }

}