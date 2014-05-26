<?php

namespace Cloud\StorageSystemBundle\Tests\Utility;


use Cloud\StorageSystemBundle\Utility\HTTPClient;
use Cloud\StorageSystemBundle\Utility\Swift;

class SwiftObjectTest extends \PHPUnit_Framework_TestCase
{

    protected static $swift;

    protected $file_id;
    protected $folder_id;


    public static function setUpBeforeClass(){
        $authUrl = 'http://folsom2.tissat.es:5000/v2.0/tokens';
        self::$swift = new Swift(new HTTPClient(),$authUrl, 'almartinez@tissat.es', 'almartinez@tissat.es', 'almartinez');

        $container ='TEST';
        self::$swift->createContainer($container);
        self::$swift->setContainer($container);
    }

    public function testgetAuthTokens(){
        $this->assertNotNull(self::$swift->getTokenId());
        $this->assertNotNull(self::$swift->getUrl());
    }

    public function testListMetadata(){
        $message = self::$swift->getMetadata();
        $this->assertTrue(isset($message->contents));
    }

    public function getSampleFile(){
        $fileName = 'es.png';
        $filePath = "../web/upload/es.png";
        return array($fileName, $filePath);
    }

    private function getSampleFolder(){
        return $folderName = 'a';
    }

    public function uploadFile(){
        list($fileName, $filePath) = $this->getSampleFile();
        $message = self::$swift->uploadFileToSwift($filePath, $fileName);
        $this->file_id = $message->file_id;

        return array($fileName, $message);
    }

    public function testAddFile(){
        list($fileName, $message) = $this->uploadFile();
        $this->assertEquals($fileName, $message->filename);
        $this->assertObjectHasAttribute('file_id', $message);
    }

    public function testDownloadFile(){//downloads in app/
        list($file, $message) = $this->uploadFile();

        $contents = self::$swift->downloadFileFromSwift($message->file_id);
        $this->assertNotNull($contents);
        $this->assertTrue(file_exists($message->file_id));
        unlink($message->file_id);
    }

    public function testDeleteFile(){
        list($file, $message) = $this->uploadFile();
        $message = self::$swift->deleteFile($message->file_id);
        $this->assertObjectHasAttribute('file_id', $message);
    }

    private function getFolder(){
        $folderName = $this->getFolder();
        $message = self::$swift->createFolder('a');
        $this->file_id = $message->file_id;
        return array($folderName, $message);
    }

    public function testAddFolder(){
        list($folderName, $message) = $this->getFolder();
        $this->assertEquals($message->filename, $folderName );
        $this->assertObjectHasAttribute('file_id', $message);
    }

    public function testDeleteFolder(){
        list($folderName, $message) = $this->getFolder();
        $message = self::$swift->deleteFile($message->file_id);
        $this->assertEquals($message->filename, $folderName );
        $this->assertObjectHasAttribute('file_id', $message);
    }

    public function tearDown(){
        //Gracefully fails when file_id or folder_id is not present
        $message = self::$swift->deleteFile($this->file_id);
        $message = self::$swift->deleteFile($this->folder_id);
    }

    public static function tearDownAfterClass()
    {
        $container ='TEST';
        self::$swift->deleteContainer($container);
        self::$swift->setContainer(null);

        self::$swift = NULL;
    }

}
