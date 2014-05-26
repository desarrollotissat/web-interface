<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 6/11/13
 * Time: 15:36
 * To change this template use File | Settings | File Templates.
 */

namespace Cloud\StorageSystemBundle\Tests\Utility;


use Cloud\StorageSystemBundle\Utility\KernelTestCase;
use stdClass;

class IdentityTest extends KernelTestCase {

    protected $user;
    protected $tenant;
    protected $identity;

    public function setUp(){
        parent::setUp();
        $this->user = new stdClass;
        $this->user->name = "bobo";
        $this->user->email = "bobo@bobo.com";
        $this->user->password = "bobopass";
        $this->user->enabled = true;
        $this->user->tenantId = '69e934eeb3b94b078033956e98bf9eaf'; //tenantName: tusers


        $this->tenant = new stdClass;
        $this->tenant->name = "tenantbobo";
        $this->tenant->description = $this->tenant->name;
        $this->tenant->enabled = true;

//        $this->identity = new Identity();
        $this->identity = $this->container->get('keystone_identity');
//
    }

    public function tearDown(){
        $this->user = null;
        $this->identity = null;
    }


//    public function testDeleteUser(){
//        $response = $this->identity->deleteUser("");
//        $this->assertNull($response);
//    }

//    public function testcreateUser(){
//        $response=$this->identity->addUser($this->user->email, $this->user->password, $this->user->tenantId );
//        $this->assertEquals($this->user->email, $response->user->email);
//
//        $response = $this->identity->deleteUser($response->user->id);
//        $this->assertNull($response);
//    }
////
//    public function testcreateTenant(){
//        $response=$this->identity->addTenant($this->tenant->name);
//        $this->assertEquals($this->tenant->name, $response->tenant->name);
//
//        $response = $this->identity->deleteTenant($response->tenant->id);
//        $this->assertNull($response);
//    }

//    public function testListRoles(){
//        $response = $this->identity->listRoles();
//        $this->assertNotNull($response);
//    }
//
    public function testListAdminRole(){
        $response = $this->identity->findRoleByName("admin");
        $this->assertNotNull($response->id);
    }

//    public function testAddRoletoUser(){
//        $response = $this->identity->findUserByName("demo");
//        $user_id = $response->id;
//        $tenant_id = 'e4544948e8974399963c7f53f182db89';
//        $role_id = '8fcb4ae3fbf74394a54fc2b59801c9bf';
//        $response = $this->identity->addRoletoUser($role_id, $tenant_id, $user_id);
//        $this->assertEquals($response->role->id, $role_id);
//        //ADD LINE --REMOVE ROLE --OR assertion WILL FAIL
//    }

/*    public function testListUsers(){
        $response = $this->identity->listUsers();
        $this->assertNotNull($response);
    }
*/
//    public function testListAdminUser(){
//        $response = $this->identity->findUserByName("admin");
//        $this->assertNotNull($response->id);
//    }
//
//    public function testListTenants(){
//        $response = $this->identity->listTenants();
//        $this->assertNotNull($response);
//    }
//
//    public function testListAdminTenants(){
//        $response = $this->identity->findTenantByName("admin");
//        $this->assertNotNull($response->id);
//    }

//    public function testUpdateUser(){
//        $response = $this->identity->findUserByName("pepe@pepe.com");
//        $this->assertNotNull($response->id);
//        $response = $this->identity->updatePassword($response->id, 'pepe');
//    }



}
