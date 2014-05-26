<?php
///**
// * Created by JetBrains PhpStorm.
// * User: almartinez
// * Date: 17/09/13
// * Time: 17:21
// * To change this template use File | Settings | File Templates.
// */
//
//namespace Cloud\UsuarioBundle\Helper;
//
//
//class UsuarioHelper {
//    public function createEncodedPassword(&$usuario, $password){
//
//        $usuario->setSalt(md5(time()));
//        $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
//        $passwordCodificado = $encoder->encodePassword($password, $usuario->getSalt());
//
//        return $passwordCodificado;
//
//    }
//
//}