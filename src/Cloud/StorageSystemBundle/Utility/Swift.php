<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 8/10/13
 * Time: 10:00
 * To change this template use File | Settings | File Templates.
 */
namespace Cloud\StorageSystemBundle\Utility;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Process\Exception\InvalidArgumentException;


class Swift
{
    private $authUrl;
    private $tenant;
    private $container;
    private $username;
    private $password;

    private $tokenId;
    private $url;

    private $httpClient;

    function __construct($httpClient, $authUrl,
                         $tenant=null, $username=null, $password=null){
        $this->authUrl = $authUrl; //        $authUrl = 'http://folsom2.tissat.es:5000/v2.0/tokens';
        $this->tenant = $tenant;
        $this->username = $username;
        $this->password = $password;
        $this->httpClient = $httpClient;

        if (!empty($username)) $this->getAuthTokens();
    }


    /*
     * In order to execute swift commands, the first thing you need to do is to call
     * getAuthTokens().
     * */
    public function getAuthTokens(){


        $json_string = json_encode(array(
            'auth' => array(
                'passwordCredentials' => array(
                    'username' => $this->username,
                    'password' => $this->password,
                ),
                'tenantName' => $this->tenant,
            )
        ));

// Configuring curl options
        $options = array(
            CURLOPT_URL => $this->authUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json'),
            CURLOPT_POSTFIELDS => $json_string
        );

        $tokens = $this->httpClient->exec($options);
        $tokensDecode = json_decode($tokens);

        try {
             $this->tokenId = $tokensDecode->{'access'}->{'token'}->{'id'};
             $this->url = $tokensDecode->{'access'}->{'serviceCatalog'}[0]->{'endpoints'}[0]->{'publicURL'};
        } catch(Exception $a){
               throw new InvalidArgumentException("Bad authentication");
        }
    }


    public function getMetadata($fileId=null){
        $options = array(
            CURLOPT_URL => $this->url . "/" . $this->container . "/metadata?file_id=". $fileId .'&list=true',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('stacksync-api:true',
                'X-Auth-Token:' . $this->tokenId),
        );

        $contenido = $this->httpClient->exec($options);

        $contenido = json_decode($contenido, false);
   //     if(!$this->checkErrors($contenido))usort($contenido->contents, array($this, "compareSortOrderByFoldersFirst"));
        return $contenido;
    }

    public function uploadFileToSwift($localFilePath, $fileName, $parent=null)
    {

        $fd = fopen($localFilePath, "r");
        $options = array(
            CURLOPT_URL => $this->url . '/' . $this->container . '/files?file_name=' . $fileName .'&parent='.$parent,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_VERBOSE => true,
            CURLOPT_INFILE => $fd,
            CURLOPT_INFILESIZE => filesize($localFilePath),
            CURLOPT_READFUNCTION, array("_read_cb"),
            CURLOPT_UPLOAD => true,
            CURLOPT_HTTPHEADER => array('stacksync-api:true',
                'X-Auth-Token:' . $this->tokenId),
        );

        $message = $this->httpClient->exec($options);
        fclose($fd);

        return json_decode($message, false);
    }

    public function _read_cb($ch, $fd, $length)
    {
        $data = fread($fd, $length);
        $len = strlen($data);
//        if (isset($this->_user_write_progress_callback_func)) {
//            call_user_func($this->_user_write_progress_callback_func, $len);
//        }
        return $data;
    }


    public function deleteFile($id) {

        $options = array(
            CURLOPT_URL => $this->url . '/' . $this->container . '/files?file_id=' . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => array('stacksync-api:true',
                'X-Auth-Token:' . $this->tokenId),
        );

        $message = $this->httpClient->exec($options);

        return json_decode($message, false);
    }


    public function downloadFileFromSwift($id) {
        $fd = fopen($id, "w+");
        $options = array(
            CURLOPT_URL => $this->url . '/' . $this->container . '/files?file_id=' . $id,
            CURLOPT_FILE => $fd,
            CURLOPT_HTTPHEADER => array('stacksync-api:true',
                'X-Auth-Token:' . $this->tokenId),
        );

        $contents = $this->httpClient->exec($options);
        fclose($fd);

        return $contents;
    }

    public function createContainer($container)
    {
        $options = array(
            CURLOPT_URL => $this->url . '/' . $container,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_HTTPHEADER => array('X-Auth-Token:' . $this->tokenId),
        );

        $message = $this->httpClient->exec($options);

        return $message;
    }

    public function deleteContainer($container)
    {
        $options = array(
            CURLOPT_URL => $this->url . '/' . $container,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array('X-Auth-Token:' . $this->tokenId),
        );

        $message = $this->httpClient->exec($options);

        return $message;
    }

    public function createFolder($folderName, $parent_id=null)
    {
        $options = array(
            CURLOPT_URL => $this->url . '/' . $this->container . '/files?folder_name=' . $folderName . '&parent=' . $parent_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array('X-Auth-Token:' . $this->tokenId,
                'stacksync-api:true')
        );

        $message = $this->httpClient->exec($options);

        return json_decode($message, false);
    }


    private function compareSortOrderByFoldersFirst($a, $b)
    {
        if ($a->is_folder && !$b->is_folder) {
            return -1;
        } else if (!$a->is_folder && $b->is_folder) {
            return 1;
        } else {
            return strcmp($a->filename, $b->filename);
        }
    }

    public function checkErrors($message){
        if (property_exists($message, 'error')){
            return true;
        }
    }




    public function setAuthUrl($authUrl)
    {
        $this->authUrl = $authUrl;
    }

    /**
     * @return mixed
     */
    public function getAuthUrl()
    {
        return $this->authUrl;
    }

    /**
     * @param mixed $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $tenant
     */
    public function setTenant($tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * @return mixed
     */
    public function getTenant()
    {
        return $this->tenant;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $tokenId
     */
    public function setTokenId($tokenId)
    {
        $this->tokenId = $tokenId;
    }

    /**
     * @return mixed
     */
    public function getTokenId()
    {
        return $this->tokenId;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

}
