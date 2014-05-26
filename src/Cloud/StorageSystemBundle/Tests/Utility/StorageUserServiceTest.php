<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 8/11/13
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

namespace Cloud\StorageSystemBundle\Tests\Utility;



use Cloud\StorageSystemBundle\Controller\Identity;
use Cloud\StorageSystemBundle\Controller\StorageUserService;
use Cloud\StorageSystemBundle\Utility\KernelTestCase;

use stdClass;


class StorageUserServiceTest extends KernelTestCase {


    private $user;
    private $storageuser;



    public function setUp()
    {
        parent::setUp();
        $this->user = new stdClass;
        $this->user->email = "bobo@bobo.com";
        $this->user->password = "bobopass";
        $this->user->quota = 100;

        $this->storageuser = $this->container->get('StorageUser');
    }


    public function tearDown()
    {
        parent::tearDown();
    }

    public function testaddUserInStorageSysteam(){
        $email = $this->user->email;
        $password = $this->user->password;
        $quota = $this->user->quota;

        $storageuser = $this->storageuser;
        list($keystone_user_id, $tenant_id, $syncservice_user_id) = $storageuser->add($email, $password, $quota);


        $this->assertNotNull($keystone_user_id);
        $this->assertNotNull($tenant_id);
        $this->assertNotNull($syncservice_user_id);

        $storageuser->delete($keystone_user_id, $tenant_id, $syncservice_user_id);
    }

   public function testDeleteUserInStorageSysteam(){
        $storageuser = $this->storageuser;
       $email = $this->user->email;
       $password = $this->user->password;
       $quota = $this->user->quota;
       list($keystone_user_id, $tenant_id, $syncservice_user_id) = $storageuser->add($email, $password, $quota);
       $this->assertNotNull($syncservice_user_id);
       $this->assertNotNull($keystone_user_id);

       $storageuser->deleteUserByName($this->user->email);
    }

}
