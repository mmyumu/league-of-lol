<?php

namespace Lolol\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller
{
    public function indexAction()
    {
        return $this->render('LololShopBundle:Shop:index.html.twig');
    }
}
