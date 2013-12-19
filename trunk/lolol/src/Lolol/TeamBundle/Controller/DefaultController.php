<?php

namespace Lolol\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LololTeamBundle:Default:index.html.twig', array('name' => $name));
    }
}
