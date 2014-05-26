<?php

namespace Cloud\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cloud\UsuarioBundle\Entity\Usuarios;
use Cloud\UsuarioBundle\Form\UsuarioType;
use Cloud\UsuarioBundle\Form\UsuarioRegistroType;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CloudUsuarioBundle:Default:index.html.twig', array('name' => $name));
    }
	
//	public function listadoAction(){
//        $form = $this->selectUsersForm();
//        $vista = $form->createView();
//        $quotas = $this->getQuotas();
//
//        return $this->render('CloudUsuarioBundle:Default:index.html.twig',array('quotas' => $quotas, 'form' => $vista,'path' => 'admin/listado'));
//	}
	
	public function detalleAction($id){		
		
		$em = $this->getDoctrine()->getManager();
		
		$usuario = $em->getRepository('CloudUsuarioBundle:Usuarios')->find($id);		
		
		$formulario = $this->createForm(new UsuarioType($em), $usuario,array('idioma' => $usuario->getIdioma()->getIdidioma()));
		
		return $this->render('CloudCloudspacesBundle:Default:mobile_edit.html.twig',array('user_id' => $id,'formulario' => $formulario->createView()));
		
	}
	
	public function editAction($id){
        $peticion = $this->getRequest();
        $security = $this->get('security.context');
        $current_id = $security->getToken()->getUser()->getId();
        $different_user = ($current_id != $id);


        if ( $different_user and (false === $security->isGranted('ROLE_ADMIN')) ) {
            throw new AccessDeniedException();
        }
		$peticion = $this->getRequest();


			
		if ($peticion->getMethod() == 'POST') {
			
			$em = $this->getDoctrine()->getManager();
			
			$usuarios = $em->getRepository('CloudUsuarioBundle:Usuarios')->findAll();
		
			$usuario = $em->getRepository('CloudUsuarioBundle:Usuarios')->find($id);
		
			$formulario = $this->createForm(new UsuarioType($em), $usuario,array('idioma' => $usuario->getIdioma()->getIdidioma()));
			
			$passwordOld = $formulario->getData()->getPassword();
							
			$formulario->handleRequest($peticion);
			
			if ($formulario->isValid()) {
					
				$passwordNew = $formulario->getData()->getPassword();
				
				//Comprobamos que la contrasenya nueva es distinta a la vieja y es distinta
				// de null para poder codificarla y guardarla.
				//En caso de no se ser asi, se guardara la contrasenya anterior
				if($passwordNew != $passwordOld && $passwordNew != ''){

					$passwordCodificado = $this->createEncodedPassword($usuario, $passwordNew);
					$usuario->setPassword($passwordCodificado);

                    $this->updateKeystonePass($usuario, $passwordNew);

                }else{
					$usuario->setPassword($passwordOld);
//
				}
								
				$em->persist($usuario);			
				
				$em->flush();
				
				$response = $this->render('CloudCloudspacesBundle::message.html.twig',array('message' => 'This operation was successful' , 'path' => ''));
                if (!empty($passwordNew) && !$different_user){
                    $cookie = new Cookie('pwd', $passwordNew, 0, '/', null, false, false);
                    $response->headers->setCookie($cookie);
                    $session = $this->getRequest()->getSession();
                    $swift = $session->get('swift');
                    $swift->setPassword($passwordNew);
                    $swift->getAuthTokens();
                }
                return $response;
				

			}else{
				return $this->render('CloudCloudspacesBundle:Default:edit.html.twig',array('usuario' => $id,'formulario' => $formulario->createView()));
			}	
			
		}
		
	}

	/**
	 * Metodo para crear el formulario de registro
	 */
	public function newAction(){	
		
		$peticion = $this->getRequest();

		$usuario = new Usuarios();
		$formulario = $this->createForm(new UsuarioRegistroType(), $usuario);
			
		if ($peticion->getMethod() == 'POST') {
				
			if(!$this->crearUsuarioNuevo($formulario, $peticion, $usuario)){
				return $this->render('CloudUsuarioBundle:Default:register.html.twig',array('formulario' => $formulario->createView()));
			}

            return $this->render('CloudCloudspacesBundle::message.html.twig',array('message' => 'User created', 'path' => ''));

        }else{
			return $this->render('CloudUsuarioBundle:Default:registro.html.twig',array('formulario' => $formulario->createView()));
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
			$em->persist($usuario);
			$resultado = $em->flush();


            $storageuser = $this->get("StorageUserService");

            $storageuser->add($usuario->getEmail(), $passwordNew, 100);

			return true;					
			
		}else{
			return false;				
		}		
	}

    public function createEncodedPassword(&$usuario, $password){
        $usuario->setSalt(md5(time()));
        $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
        $passwordCodificado = $encoder->encodePassword($password, $usuario->getSalt());

        return $passwordCodificado;
    }

    public function updateKeystonePass($usuario, $password){
//        $result = exec('/aux/update_pass.sh '.$usuario->getEmail().' '.$password);
        $em_stacksync = $this->getDoctrine()->getManager('stacksync');

        $name = $usuario->getEmail();

        $storageuser = $this->get("StorageUserService");

        $storageuser->updatePassword($name, $password);
    }

    public function passwordResetAction($token){
        $request = $this->getRequest();

        $form = $this->createFormBuilder()
            ->add('password', 'password')
            ->add('Enviar', 'submit')
            ->getForm();

        $mensaje = '';
        $form->handleRequest($request);

        if ($form->isValid()) {

            $reset = $this->getDoctrine()
                ->getRepository('CloudloginBundle:PasswordReset')
                ->findOneBy(array(
                    'token' => $token
                ));

            $usuario = (!empty($reset)) ? $usuario = $reset->getUsuario() : null;

            if ( empty($usuario)){
                $mensaje = 'Bad address';

            } else {
                $mensaje = 'Password changed successfully';
                $newPassword = $form['password']->getData();

                $passwordCodificado = $this->createEncodedPassword($usuario, $newPassword);
                $usuario->setPassword($passwordCodificado);

                $em = $this->getDoctrine()->getManager();
                $em->persist($usuario);
                $em->remove($reset);
                $resultado = $em->flush();

                $this->updateKeystonePass($usuario, $newPassword);

                $rest_of_requests = $this->getDoctrine()
                    ->getRepository('CloudloginBundle:PasswordReset')
                    ->findBy(array(
                        'usuario' => $usuario
                    ));

                foreach ($rest_of_requests as $value){
                    $em->remove($value);
                }
                $resultado = $em->flush();

            }

            return $this->render(
                'CloudCloudspacesBundle::message.html.twig', array(
                'message' => $mensaje,
                'path' => ''
            ));
        }

        return $this->render(
            'CloudloginBundle:Default:recover_pass.html.twig',array(
            'form' => $form->createView(),
        ));
    }

}
