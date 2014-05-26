<?php

namespace Cloud\SyncserviceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CloudSyncserviceBundle:Default:index.html.twig', array('name' => $name));
    }
}
