<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 24/10/13
 * Time: 12:41
 * To change this template use File | Settings | File Templates.
 */

namespace Cloud\UsuarioBundle\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsuarioRepositoryTest extends WebTestCase {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

    public function testListAllUsers(){

        $users = $this->em->getRepository('CloudUsuarioBundle:Usuarios')
            ->findAllOrderedByName();

        $this->assertNotNull($users);
    }

    public function testListAllUsersDQL(){

        $query = $this->em->getRepository('CloudUsuarioBundle:Usuarios')
            ->findAllUsuariosDQL();

        $users = $query->getResult();

        $this->assertNotNull($users);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }

}
