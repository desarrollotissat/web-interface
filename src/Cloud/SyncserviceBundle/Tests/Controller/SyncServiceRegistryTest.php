<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 8/11/13
 * Time: 11:40
 * To change this template use File | Settings | File Templates.
 */

namespace Cloud\SyncserviceBundle\Tests\Controller;

use Cloud\StorageSystemBundle\Utility\KernelTestCase;

class SyncServiceUserTest extends KernelTestCase {
    /*Only test web-system to database interaction, you still need to register with the
    keystone service(IDENTITY class) */

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown(){
        parent::tearDown();
    }

    public function testaddSyncServiceUser(){
//        $syncServiceRegister = new SyncServiceRegistry($this->em);
        $syncService = $this->container->get('SyncService');
        $user_id = $syncService->addUser("bobo@bobo.com", "AUTH_idiid", 100);
        $this->assertNotNull($user_id);

        $syncService->deleteUser($user_id);

    }
}
