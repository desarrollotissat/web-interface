<?php

namespace Cloud\StorageSystemBundle\Tests\Utility;

use Cloud\CloudspacesBundle\Utility\HTTPClient;
use Cloud\CloudspacesBundle\Utility\Swift;


class SwiftTest extends \PHPUnit_Framework_TestCase
{

    protected static $swift;

    protected $container;


    public static function setUpBeforeClass(){
        $authUrl = 'http://folsom2.tissat.es:5000/v2.0/tokens';
        self::$swift = new Swift(new HTTPClient(),$authUrl, 'almartinez@tissat.es', 'almartinez@tissat.es', 'almartinez');
    }


    public function setUp(){
        $this->container ='TEST';
        self::$swift->setContainer($this->container);
    }

    public function tearDown(){
        $this->container ='TEST';
        self::$swift->deleteContainer($this->container);
        $message = self::$swift->deleteContainer($this->container);
        self::$swift->setContainer(null);
    }

    public function testCreateContainer(){
        $message = self::$swift->createContainer($this->container);
        $this->assertContains('Accepted', $message);
    }

    public function testDeleteContainer(){
        $message = self::$swift->createContainer($this->container);

        $message = self::$swift->deleteContainer($this->container);
        $this->assertNotContains('Not Found', $message);
        $this->assertNotContains('error', $message);
    }

    public function testListContainerMetadata(){
        $message = self::$swift->createContainer($this->container);

        $message = self::$swift->getMetadata();
        $this->assertTrue(isset($message->contents));
    }

    public static function tearDownAfterClass(){
        self::$swift = NULL;
    }
}
