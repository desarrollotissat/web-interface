<?php

namespace Cloud\StorageSystemBundle\Utility;



use Symfony\Component\Intl\Exception\InvalidArgumentException;


/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 6/11/13
 * Time: 13:09
 * To change this template use File | Settings | File Templates.
 */

class Identity{
    protected $tokenId; //ADMIN TOKEN  = '012345SECRET99TOKEN012345'
    protected $url; // 'http://ip:35357/v2.0'
    protected $client; //httpclient

    function __construct(HTTPClient $client, $url, $tokenId) {
        $this->client = $client;
        $this->url = $url;
        $this->tokenId = $tokenId;
    }

    public function deleteUser($user_id){
        $options = array(
            CURLOPT_URL => $this->url . '/users/' . $user_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "DELETE",
//            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => array('X-Auth-Token:' . $this->tokenId),
        );

        $message = $this->client->exec($options);

        return json_decode($message, false);
    }

    public function addUser($email, $password, $tenantId){

        $json_string = json_encode(array(
            'user' => array(
                'name' => $email,
                'email' => $email,
                'enabled' => true,
                'password' => $password,
                'tenantId' => $tenantId, //o TenandId
            )
        ));

        $options = array(
            CURLOPT_URL => $this->url . '/users',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
//            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => array('X-Auth-Token:' . $this->tokenId,
                                        'Content-type: application/json'),
            CURLOPT_POSTFIELDS => $json_string
        );

        $message = $this->client->exec($options);

        return json_decode($message, false);
    }


    public function addTenant($name){

        $json_string = json_encode(array(
            'tenant' => array(
                'name' => $name,
                'description' => $name,
                'enabled' => true,
            )
        ));

        $options = array(
            CURLOPT_URL => $this->url . '/tenants',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
//            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => array('X-Auth-Token:' . $this->tokenId,
                'Content-type: application/json'),
            CURLOPT_POSTFIELDS => $json_string
        );

        $message = $this->client->exec($options);

        return json_decode($message, false);
    }

    public function deleteTenant($tenant_id){
        $options = array(
            CURLOPT_URL => $this->url . '/tenants/' . $tenant_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "DELETE",
//            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => array('X-Auth-Token:' . $this->tokenId),
        );

        $message = $this->client->exec($options);

        return json_decode($message, false);
    }

    public function listRoles(){
        $options = array(
            CURLOPT_URL => $this->url . '/OS-KSADM/roles',
            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => array('X-Auth-Token:' . $this->tokenId),
        );

        $message = $this->client->exec($options);

        return json_decode($message, false);
    }

    public function findRoleByName($name){
        $roles = $this->listRoles();
        foreach($roles->roles as $role){
            if (strcmp($role->name, $name) == 0 ) return $role;
        }
    }

    public function addRoletoUser($role_id, $tenant_id, $user_id){
        $options = array(
            CURLOPT_URL => $this->url . '/tenants/' . $tenant_id .'/users/' . $user_id .'/roles/OS-KSADM/' .$role_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "PUT",
//            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => array('X-Auth-Token:' . $this->tokenId),
        );

        $message = $this->client->exec($options);

        return json_decode($message, false);
    }


    public function listUsers(){
        $options = array(
            CURLOPT_URL => $this->url . '/users',
            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => array('X-Auth-Token:' . $this->tokenId),
        );

        $message = $this->client->exec($options);

        return json_decode($message, false);
    }

    public function findUserByName($name){
        $users = $this->listUsers();
        foreach($users->users as $user){
            if (strcmp($user->name, $name) == 0 ) return $user;
        }
        throw new InvalidArgumentException("Name does not exist");
    }

    public function listTenants(){
        $options = array(
            CURLOPT_URL => $this->url . '/tenants',
            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => array('X-Auth-Token:' . $this->tokenId),
        );

        $message = $this->client->exec($options);

        return json_decode($message, false, 512);
    }

    public function findTenantByName($name){
        $tenants = $this->listTenants();
        foreach($tenants->tenants as $tenant){
            if (strcmp($tenant->name, $name) == 0 ) return $tenant;
        }
        throw new InvalidArgumentException("Name does not exist");
    }

    public function deleteTenantByName($name){
        $tenant = $this->findTenantByName($name);
        if (empty($tenant)) throw new InvalidArgumentException("Name does not exist");

        $response = $this->deleteTenant($tenant->id);
    }

    public function deleteUserByName($name){
        $user = $this->findUserByName($name);
        if (empty($user)) throw new InvalidArgumentException("Name does not exist");

        $response = $this->deleteUser($user->id);
    }

    public function updatePassword($user_id, $password){

        $json_string = json_encode(array(
            'user' => array(
                'id' => $user_id,
                'password' => $password,

            )
        ));

        $options = array(
            CURLOPT_URL => $this->url . '/users/' . $user_id . '/OS-KSADM/password',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "PUT",
//            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => array('X-Auth-Token:' . $this->tokenId,
                'Content-type: application/json'),
            CURLOPT_POSTFIELDS => $json_string
        );

        $message = $this->client->exec($options);

        return json_decode($message, false);
    }

}
