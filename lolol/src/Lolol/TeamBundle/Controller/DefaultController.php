<?php

namespace Lolol\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
	public function indexAction() {
		// Créons nous-mêmes la réponse en JSON, grâce à la fonction json_encode()
		$response = new Response( json_encode( array ("code" => 100,"success" => true 
		) ) );
		
		// Ici, nous définissons le Content-type pour dire que l'on renvoie du JSON et non du HTML
		$response->headers->set( 'Content-Type', 'application/json' );
		
		return $response;
		// return $this->render( 'LololTeamBundle:Default:index.html.twig' );
	}
}
