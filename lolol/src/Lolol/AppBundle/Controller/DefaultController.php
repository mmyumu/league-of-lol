<?php

namespace Lolol\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LololAppBundle:Default:index.html.twig');
    }
}
