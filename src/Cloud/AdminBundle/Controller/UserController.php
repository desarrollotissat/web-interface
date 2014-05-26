<?php

namespace Cloud\AdminBundle\Controller;

use Cloud\AdminBundle\Form\EditUserFormType;
use Cloud\AdminBundle\Form\UsuarioRegistroNoCaptchaType;
use Cloud\UsuarioBundle\Entity\Usuarios;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CloudAdminBundle:Default:index.html.twig', array('name' => $name));
    }

    public function listUsersAction(){
        $form = $this->selectUsersForm();
        $vista = $form->createView();
        $storageuser = $this->get("StorageUserService");
        $quotas = $storageuser->getQuotasPerUser();

        return $this->render('CloudAdminBundle:Default:ListUsers.html.twig',array('quotas' => $quotas, 'form' => $vista,'path' => 'admin/listado'));
    }


    public function listUsersPaginatedAction(){
        $paginator = $this->get('ideup.simple_paginator');
        $users = $paginator->paginate($this->getDoctrine()->getRepository('CloudUsuarioBundle:Usuarios')->findAllUsuariosDQL())->getResult();
        $form = $this->selectUsersPaginatedForm($users);
        $vars = array(
            'users'     => $users,
            'path' => 'path/listado',
            'form' => $form->createView()
        );
        return $this->render('CloudAdminBundle:Default:listUsersPaginated.html.twig', $vars);

//        $form = $this->selectUsersForm();
//        $vista = $form->createView();
//        $storageuser = $this->get("StorageUserService");
//        $quotas = $storageuser->getQuotasPerUser();

//        return $this->render('CloudAdminBundle:Default:listUsers.html.twig',array('quotas' => $quotas, 'form' => $vista,'path' => 'admin/listado'));
    }


    private function selectUsersPaginatedForm($users){
        return $this->createFormBuilder()
            ->add('users', 'entity', array(
                'class' => 'CloudUsuarioBundle:Usuarios',
                'property' => 'id',
                'choices' => $users,
                'expanded' => true,
                'multiple' => true,))
            ->getForm();
    }


    private function selectUsersForm(){
        return $this->createFormBuilder()
            ->add('users', 'entity', array(
                'class' => 'CloudUsuarioBundle:Usuarios',
                'property' => 'id',
                'expanded' => true,
                'multiple' => true,))
            ->getForm();
    }


    /**
     * Displays a form to create a new Usuario entity.
     *
     */
    public function newAction()
    {
        $entity = new Usuarios();
        $form   = $this->createForm(new UsuarioRegistroNoCaptchaType(), $entity);

        return $this->render('CloudAdminBundle:Default:register.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Usuario entity.
     *
     */
    public function createAction()
    {
        $entity  = new Usuarios();
        $request = $this->getRequest();
        $form    = $this->createForm(new UsuarioRegistroNoCaptchaType(), $entity);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $plaintextpassword = $form->getData()->getPassword();
            $quota_limit = $form->get('quota_limit')->getData();

            $webRegister = $this->get("WebUserService");
            $storageServiceRegister = $this->get("StorageUserService");
//
            $webRegister->register($entity, $plaintextpassword);
            $storageServiceRegister->add($entity->getEmail(), $plaintextpassword, $quota_limit );

            return $this->render('CloudCloudspacesBundle::messageCompletePath.html.twig',array('message' => 'User created', 'path' => $this->generateUrl('admin_list_users')));
        }

        return $this->render('CloudAdminBundle:Default:register.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $stacksync_em = $this->getDoctrine()->getManager('stacksync');

        $entity = $em->getRepository('CloudUsuarioBundle:Usuarios')->find($id);
        $stacksync_user = $stacksync_em->getRepository('CloudSyncserviceBundle:User1')->findOneByemail($entity->getEmail());

        if (!$entity) {
            throw $this->createNotFoundException('User not found');
        }

        $form = $this->createForm(new EditUserFormType(), $entity);
        $form->get('quota_limit')->setData($stacksync_user->getQuotaLimit());

        return $this->render('CloudAdminBundle:Default:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Edits an existing Usuario entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CloudUsuarioBundle:Usuarios')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se ha encontrado el usuario solicitado');
        }

        $oldPassword = $entity->getPassword();
        $form = $this->createForm(new EditUserFormType(), $entity);

        $request = $this->getRequest();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $newPassword = $form->getData()->getPassword();
            $quota_limit=$form->get('quota_limit')->getData();

            $this->updateNonMappedData($entity, $oldPassword, $newPassword, $quota_limit);
            $em->persist($entity);
            $em->flush();

            return $this->render('CloudCloudspacesBundle::messageCompletePath.html.twig',
                                 array('message' => 'This operation was successful' ,
                                        'path' => $this->generateUrl('admin_list_users'))
            );
        }

        return $this->render('CloudAdminBundle:Default:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }

    private function updateNonMappedData($entity, $oldPassword, $newpassword, $quota_limit){
        $storageuser = $this->get("StorageUserService");
        $email = $entity->getEmail();
        if (empty($newpassword)){//Form changes password automatically
            $entity->setPassword($oldPassword);
        }else{
            $webservice = $this->get("WebUserService");

            $webservice->changePassword($entity, $newpassword);
            $storageuser->updatePassword($email, $newpassword);
        }

        $storageuser->updateQuota($email, $quota_limit);
    }


    public function deleteAction($id){

        $email = $this->deleteUser($id);
        return $this->render(
            'CloudAdminBundle::message_admin.html.twig', array(
            'message' => "The following user was removed: " . $email,
            'path' => $this->generateUrl('admin_list_users')
        ));
    }


    private function deleteUser($id){

        $em = $this->getDoctrine()->getManager();
        $storageServiceRegister = $this->get("StorageUserService");

        $webuser = $em->getRepository('CloudUsuarioBundle:Usuarios')->find($id);
        $email = $webuser->getEmail();

        $storageServiceRegister->deleteUserByName($email);

        $em->remove($webuser);
        $em->flush();

        return $email;
    }


    public function deleteUsersAction(){
        $request = $this->getRequest();
        $form = $this->selectUsersForm();
        $message = 'Permission denied';

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form['users']->getData();
            $message = $this->get('translator')->trans('Following users were deleted:');
            foreach($data as $user){
                $this->deleteUser($user->getId());
            }
        }

        return $this->render(
            'CloudAdminBundle::message_admin.html.twig', array(
            'message' => $message,
            'users' => $data->toArray(),
            'path' => $this->generateUrl('admin_list_users')
        ));
    }


}
