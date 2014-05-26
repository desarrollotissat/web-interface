<?php

namespace Cloud\loginBundle\Controller;

use Cloud\loginBundle\Entity\PasswordReset;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller {

    public function indexAction($name) {
        return $this->render('CloudloginBundle:Default:mobile_index.html.twig', array('name' => $name));
    }

    public function loginAction() {

        $em = $this->getDoctrine()->getManager();

        $request = $this->getRequest();
        $session = $request->getSession();



        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        $usuario =  $session->get(SecurityContext::LAST_USERNAME);

        if (is_object($usuario) == true) {
            //echo "Dentro2";	
            //exit;

            return $this->render('CloudloginBundle:Default:mobile_index.html.twig', array(
                        // last username entered by the user
                        'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                        'errorauth' => $error,
//                        'usuario' => $usuario,
                    ));
        } else {
            //echo "Dentro3: ".$error;
            return $this->render('CloudloginBundle:Default:mobile_index.html.twig', array(
                        // last username entered by the user
                        'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                        'errorauth' => $error,
//                        'usuario' => '',
                    ));
        }
    }

//    public function change_languageAction() {
//        $request = $this->getRequest();
//        $session = $request->getSession();
//        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
//            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
//        } else {
//            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
//        }
//
//        return $this->render('CloudloginBundle:Default:index.html.twig', array(
//            // last username entered by the user
//            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
//            'error' => $error,
//            'usuario' => '',
//        ));
//    }

    public function sendPassword($address, $message){
        $message = Swift_Message::newInstance()
            ->setSubject('Your new password is')
            ->setFrom('swift.cloud.test@gmail.com')
            ->setTo($address)
            ->setBody(
                $this->renderView(
                    'CloudloginBundle:Default:mail.txt.twig',
                    array('message' => $message)
                )
            );

        $this->get('mailer')->send($message);

    }

    public function recoverAction(Request $request){
        $error = '';
        $mensaje = '';

        $server = 'http://'.$_SERVER['HTTP_HOST'];
        $locale = $request->getLocale();

        $form = $this->createFormBuilder()
            ->add('email', 'email')
            ->add('Enviar', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $email = $form['email']->getData();
            $usuario = $this->getDoctrine()
                ->getRepository('CloudUsuarioBundle:Usuarios')->findOneBy(array(
                    'email' => $email
                ));


            if (empty($usuario)){
                $mensaje = 'Email '.$email.' does not exist';

            } else {
                $token = sha1(uniqid($usuario->getEmail(), TRUE));
                //Production server: server/app.php/en
                //DEv server: server/Symfony/app_dev.php/en
                $msg_email = $server . '/app.php/' . $locale . '/' . 'passwordreset/'.  $token;
                $mensaje = $this->get('translator')->trans('An email was sent to your address to reset your password');

                $new_reset = new PasswordReset();
                $new_reset->setToken($token);
                $new_reset->setUsuario($usuario);

                $em = $this->getDoctrine()->getManager();
                $em->persist($new_reset);
                $em->flush();

                $this->sendPassword($email, $msg_email);
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


