<?php
/**
 * Created by JetBrains PhpStorm.
 * User: almartinez
 * Date: 14/11/13
 * Time: 17:27
 * To change this template use File | Settings | File Templates.
 */

namespace Cloud\UsuarioBundle;


class WebUserService {

    private $entityManager;
    private $encoderFactory;
    private $user;

    public function __construct($entityManager, $encoderFactory, $user=null)
    {
        $this->entityManager = $entityManager;
        $this->user = $user;
        $this->encoderFactory = $encoderFactory;
    }

    public function register($user, $plaintextpassword){
        $this->setUser($user);

        $passwordCodificado = $this->createEncodedPassword($plaintextpassword);

        $this->user->setPassword($passwordCodificado);
        $this->user->setAdmin(0);

        $language = $this->entityManager->getRepository('CloudUsuarioBundle:Idiomas')->findOneByididioma(3);
        $this->user->setIdioma($language);

        if (is_null($this->user->getApellido2())) $this->user->setApellido2("");

        $this->entityManager->persist($this->user);
        $result = $this->entityManager->flush();

    }

    private function createEncodedPassword($password){
        $this->user->setSalt(md5(time()));
        $encoder = $this->encoderFactory->getEncoder($this->user);
        $passwordCodificado = $encoder->encodePassword($password, $this->user->getSalt());

        return $passwordCodificado;
    }

    private function setUser($user){
        $this->user = $user;
    }

    public function changePassword($user, $password){
        $this->setUser($user);

        $this->user->setPassword($this->createEncodedPassword($password));
        $this->entityManager->persist($this->user);
        $result = $this->entityManager->flush();
    }

    public function deleteByEmail($email){
        $user = $this->findUserByEmail($email);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function findUserByEmail($email){
        return $this->entityManager->getRepository('CloudUsuarioBundle:Usuarios')->findOneByEmail($email);
    }
}