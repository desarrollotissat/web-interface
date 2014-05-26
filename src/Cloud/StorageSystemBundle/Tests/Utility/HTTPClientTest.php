<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 6/11/13
 * Time: 15:20
 * To change this template use File | Settings | File Templates.
 */

namespace Cloud\StorageSystemBundle\Tests\Utility;


class HTTPClientTest extends \PHPUnit_Framework_TestCase {

    public function testCreateHTTPRequest(){
        $client = new HTTPClient();
        $this->assertNotNull($client);
    }
}
