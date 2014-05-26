<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 24/10/13
 * Time: 12:41
 * To change this template use File | Settings | File Templates.
 */

namespace Cloud\UsuarioBundle\Tests;


use Cloud\StorageSystemBundle\Utility\KernelTestCase;


class WebUserRegistratorTest extends KernelTestCase{

    private $webuser;

    public function setUp()
    {
        parent::setUp();
    }

    public function testAddAuser(){
        $this->webuser = $this->container->get("WebUserService");
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        parent::tearDown();

    }

}
