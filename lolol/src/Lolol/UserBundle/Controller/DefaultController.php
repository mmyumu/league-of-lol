<?php

namespace Lolol\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LololUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
