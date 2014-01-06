<?php

namespace Lolol\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller {
	public function indexAction() {
		return $this->render('LololShopBundle:Shop:index.html.twig');
	}
	public function championsAction() {
		$em = $this->getDoctrine()->getManager();
		
		$champions = $em->getRepository('LololTeamBundle:Champion')->findAll();
		
		return $this->render('LololShopBundle:Shop:champions.html.twig', array(
				'champions' => $champions
		));
	}
	public function itemsAction() {
		return $this->render('LololShopBundle:Shop:items.html.twig');
	}
}
