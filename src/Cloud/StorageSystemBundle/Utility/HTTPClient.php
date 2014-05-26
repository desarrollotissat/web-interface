<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 6/11/13
 * Time: 13:54
 * To change this template use File | Settings | File Templates.
 */
namespace Cloud\StorageSystemBundle\Utility;

class HTTPClient{

    function __construct() {
    }

    public function exec($options){
        set_time_limit(0);
        $ch = curl_init();
        curl_setopt_array($ch, $options);

        $contents = curl_exec($ch);
        $error = curl_error($ch);
        echo $error;
        curl_close($ch);

        return $contents;
    }

}