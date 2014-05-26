<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 20/11/13
 * Time: 16:40
 * To change this template use File | Settings | File Templates.
 */

namespace Cloud\StorageSystemBundle\Tests;


use Cloud\StorageSystemBundle\Utility\KernelTestCase;
use Cloud\UsuarioBundle\Entity\Usuarios;
use DateTime;


class RegisterUserTest extends KernelTestCase {

    protected $container;
    protected $webUserService;
    protected $storageUserService;
    protected $testEmail;

    public function setUp(){

        $config = array('environment' => 'test', 'debug' => true);
        self::$kernel = self::createKernel($config);
        self::$kernel->boot();

        $this->container = self::$kernel->getContainer();

        $this->webUserService = $this->container->get("WebUserService");
        $this->storageUserService = $this->container->get("StorageUserService");
        $this->testEmail = 'user1@tissat.es';
    }

    public function tearDown(){
        if (self::$kernel === null){
            return;
        }
        self::$kernel->shutdown();
    }


//    public function testRegisterUser(){
//        $storageuser = $this->storageUserService;
//        $webUserService =  $this->webUserService;
//
//        for ($i = 1; $i <= 150; $i++) {
//            echo $i;
//
//            $email = "user" . $i . '@tissat.es';
//            $user = $this->createWebUser($email);
//
//            $webUserService->register($user, $email);
//            $storageuser->add($email, $email, 100);
////            $storageuser->deleteUserByName($email);
////            $webuserservice->deleteByEmail($email);
//
//        }
//
//
//    }

//       public function testFindWebUserByEmail(){
//           $email = $this->testEmail;
//           $webUserService =  $this->webUserService;
//
//           $user = $webUserService->findUserByEmail($email);
//           $this->assertEquals($email, $user->getEmail());
//       }
//
//       public function testDeleteUser(){
//           $email = $this->testEmail;
//           $webUserService =  $this->webUserService;
//           $this->createWebUser();
//           $user = $webUserService->deleteByEmail($email);
//       }
//
        private function createWebUser($email, $surname1, $surname2, $name){
            $user = new Usuarios();

            $user->setEmail($email);
            $user->setApellido1($surname1);
            $user->setApellido2($surname2);
            $user->setNombre($name);
            $user->setFechanacimiento(new DateTime());

            return $user;
        }

        public function testAddUsersCSv(){
          $storageuser = $this->storageUserService;
          $webUserService =  $this->webUserService;

            if (($handle = fopen("tissat.csv", "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    $user = $this->createWebUser($data[0], $data[1], $data[2], $data[3]);//
                    $webUserService->register($user, $user->getEmail());
                    $storageuser->add($user->getEmail(), $user->getEmail(), 100);
                }
                fclose($handle);
            }
        }

}
