<?php

namespace Cloud\AdminBundle\Controller;

use Cloud\UsuarioBundle\Entity\Usuarios;
use Cloud\UsuarioBundle\Form\UsuarioRegistroNoCaptchaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CloudAdminBundle:Default:index.html.twig', array('name' => $name));
    }

    /**
     * Metodo para crear el formulario de registro
     */
    public function newAction(){

        $peticion = $this->getRequest();

        $usuario = new Usuarios();
        $formulario = $this->createForm(new UsuarioRegistroNoCaptchaType(), $usuario);

        if ($peticion->getMethod() == 'POST') {

            if(!$this->crearUsuarioNuevo($formulario, $peticion, $usuario)){
                return $this->render('CloudAdminBundle:Default:register.html.twig',array('formulario' => $formulario->createView()));
            }

            return $this->render('CloudCloudspacesBundle::messageCompletePath.html.twig',array('message' => 'User created', 'path' => $this->generateUrl('cloud_admin_listado')));

        }else{
            return $this->render('CloudAdminBundle:Default:register.html.twig',array('formulario' => $formulario->createView()));
        }

    }

    public function crearUsuarioNuevo($formulario,$peticion,$usuario) {

        $formulario->bind($peticion);

        if ($formulario->isValid()) {

            $passwordNew = $formulario->getData()->getPassword();
            $passwordCodificado = $this->createEncodedPassword($usuario, $passwordNew);

            $usuario->setPassword($passwordCodificado);
            $usuario->setAdmin(0);

            $em = $this->getDoctrine()->getManager();
            $language = $em->getRepository('CloudUsuarioBundle:Idiomas')->findOneByididioma(3);
            $usuario->setIdioma($language);

            $em->persist($usuario);
            $resultado = $em->flush();

            $stacksync_em = $this->get('doctrine')->getManager('stacksync');
           
            $storageuser = $this->get("StorageUserService");

            $storageuser->add($usuario->getEmail(), $passwordNew, 100);

            return true;

        }else{
            return false;
        }
    }


}
