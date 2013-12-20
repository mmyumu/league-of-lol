<?php

namespace Lolol\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller {
	public function indexAction() {
		return $this->render( 'LololAppBundle:App:index.html.twig' );
	}
}
