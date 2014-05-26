<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 7/11/13
 * Time: 17:15
 * To change this template use File | Settings | File Templates.
 */

namespace Cloud\SyncserviceBundle\Tests\Controller;


use Cloud\SyncserviceBundle\Entity\User1;
use Cloud\SyncserviceBundle\Entity\Workspace;


use Cloud\SyncserviceBundle\Entity\WorkspaceUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Test extends WebTestCase {
    /*
     * TESTING STACKSYNC-SYNCSERVICE TABLES
     * */
    private $em;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager('stacksync')
        ;
    }
    public function tearDown(){
        parent::tearDown();
        $this->em->close();
//        $this->em = null;
    }
    public function testAddUser(){
        $user = new User1();
        $user->setName("bobo@bobo.com");
        $user->setEmail("bobo@bobo.com");
        $user->setCloudId("AUTH_LOLOLO");
        $user->setQuotaLimit(100);
        $user->setQuotaUsed(0);


        $this->em->persist($user);
//        $this->em->flush();

        $workspace = new Workspace();
        $workspace->setClientWorkspaceName($user->getCloudId());
        $workspace->setLatestRevision(0);
        $workspace->setOwner($user);
        $this->em->persist($workspace);
        $this->em->flush();

        $workspace_user = new WorkspaceUser();
        $workspace_user->setClientWorkspacePath("/");
        $workspace_user->setUserId($user->getId());
        $workspace_user->setWorkspaceId($workspace->getId());
        $this->em->persist($workspace_user);
        $this->em->flush();
    }
}

