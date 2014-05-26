<?php

namespace Cloud\CloudspacesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller {

    public function indexAction() {
        $usuario = $this->get('security.context')->getToken()->getUser();
        $security = $this->get('security.context');

        if (!empty($usuario)) {
            $this->updateLastVisit($usuario);
        }

        $session = $this->getRequest()->getSession();

        try{
            $pass = $this->getRequest()->cookies->get('pwd');
            $this->InitializeSwift($session, $usuario, $pass);

            if ( $security->isGranted('ROLE_ADMIN')){
                return $this->redirect($this->generateUrl('admin_list_users'));
            }
        }catch (Exception $e){

            $mensaje = $this->get('translator')->trans('Your cookie is not valid. Log in again');

            return $this->render(
                'CloudCloudspacesBundle::message.html.twig', array(
                'message' => $mensaje,
                'path' => '',
            ));
        }

        //Get a json list of files and folders
        $directorios = $session->get('swift')->getMetadata();

        return $this->render('CloudCloudspacesBundle:Default:mobile_index.html.twig', array('directorios' => $directorios->contents, 'path' => ''));
    }

    private function updateLastVisit($usuario){
        $em = $this->getDoctrine()->getManager();
        $fecha = date('Y-m-d H:i:s');

        //Actualizacion del campo Ultimo_acceso
        $consulta = $em->createQuery('UPDATE CloudUsuarioBundle:Usuarios u SET u.UltimoAcceso = \'' . $fecha . '\' where u.id = ' . $usuario->getId());
        $res = $consulta->getResult();
    }

    private function InitializeSwift($session, $user, $pass){
//        $authUrl = 'http://folsom2.tissat.es:5000/v2.0/tokens';
        $username = $user->getEmail();
        $tenant = $username;

        $swift = $this->get("swift");
        $swift->setPassword($pass);
        $swift->setUsername($username);
        $swift->setTenant($tenant);
        $swift->getAuthTokens();

//        $swift = new Swift(new HTTPClient(),$authUrl, $tenant, $username, $pass );
        $swift->setContainer('stacksync');
        $session->set('swift', $swift);
    }

    public function mostrarContainerAction($path) {

        $session = $this->getRequest()->getSession();
        $swift = $session->get('swift');
        if (empty($swift)){
            $usuario = $this->get('security.context')->getToken()->getUser();
            $pass = $this->getRequest()->cookies->get('pwd');
            $this->InitializeSwift($session, $usuario, $pass);
        }

        $directorios = $swift->getMetadata($path);

        if ($swift->checkErrors($directorios)) {
            return $this->render('CloudCloudspacesBundle::message.html.twig',
                        array(
                            'message' => $this->get('translator')->trans($directorios->description),
                            'path' => null,
                        ));
        }

        $pathPadre = $directorios->parent_file_id;

        return $this->render('CloudCloudspacesBundle:Default:mobile_index.html.twig', array('directorios' => $directorios->contents, 'path' => $path, 'pathPadre' => $pathPadre));
    }

    public function descargarFicheroAction($id) {

        $session = $this->getRequest()->getSession();

        $file_data = explode("/", $id);
        $file_name = $file_data[0];
        $id_number = $file_data[1];
        $formatted_file_name = rawurldecode($file_name);

        $session->get('swift')->downloadFileFromSwift($id_number);

        return $this->setDownloadDialog($id_number, $formatted_file_name);
    }

    private function setDownloadDialog($id_number, $formatted_file_name){
        $response = new Response();
        $response->headers->set('Content-type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $formatted_file_name));
//        $response->headers->set('Content_Length', filesize($id_number) );

//        $response->headers->set("X-Sendfile", $id_number);
        $response->sendHeaders();
        $response->setContent(readfile($id_number));
        unlink($id_number);

        return $response;
    }

    public function getFileMetadata($id){
        $session = $this->getRequest()->getSession();
        return $session->get('swift')->getMetadata($id);
    }

    public function uploadAction($path)
    {
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('Fichero', 'file', array('label' => 'File'))
            ->add('Enviar', 'submit', array('label' => 'Send'))
            ->getForm();

        $message = '';

        $form->handleRequest($request);
        if ($form->isValid()) {

            $file = $form['Fichero']->getData();
            $localFilePath = $file->getPathname();
            $remoteFileName = rawurlencode($file->getClientOriginalName());
            $remoteParentId = $path;

            $message = $this->uploadToSwift($localFilePath, $remoteFileName, $remoteParentId);



            return $this->render('CloudCloudspacesBundle::message.html.twig',
                array(
                    'message' => $message,
                    'path' => $path,
                ));

        }

        return $this->render('CloudCloudspacesBundle:Default:upload.html.twig',
                             array(
                                 'formulario' => $form->createView(),
                                 'path' => $path,
                                 'message' => $message,
                             ));
    }

    public function uploadToSwift($localFilePath, $remoteFileName, $remoteParentId){
        $session = $this->getRequest()->getSession();
        $message = $session->get('swift')->uploadFileToSwift($localFilePath, $remoteFileName, $remoteParentId);
        if (is_null($message)) $message=$this->get('translator')->trans('An error occurred');
        else $message = $this->get('translator')->trans('Your file is saved');

        return $message;
    }

    public function eliminarFicheroAction($id) {
        $session = $this->getRequest()->getSession();
        $file = $session->get('swift')->deleteFile($id);
        $parent = isset($file) ? $file->parent_file_id : $file;

        return $this->redirect($this->generateUrl(
            'cloud_listado_container',
            array('path' => $parent)
        ));
    }

    public function confirmarFicheroAction($id) {
        return $this->render(
            'CloudCloudspacesBundle::delete.html.twig', array(
            'message' => '',
            'id' => $id,
        ));
    }

    public function crearCarpetaAction($path){
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('Folder', 'text')
            ->add('Create', 'submit')
            ->getForm();
        $message = 'Create folder';

        $form->handleRequest($request);

        if ($form->isValid()) {
            $session = $this->getRequest()->getSession();
            $folder = rawurlencode($form['Folder']->getData());
//            $folder = urlencode($folder);
            $parent_folder_id = $path;
            $message = $session->get('swift')->createFolder($folder, $parent_folder_id);
            $message = 'Folder created';
            return $this->render(
                'CloudCloudspacesBundle::message.html.twig', array(
                'message' => $message,
                'path' => $path,
            ));
        }

        return $this->render('CloudCloudspacesBundle:Default:folder.html.twig',
            array(
                'formulario' => $form->createView(),
                'path' => $path,
                'message' => $message,
            ));
    }
}
