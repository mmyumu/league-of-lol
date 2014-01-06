<?php

namespace Lolol\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller {
	/**
	 * Renders the shop homepage
	 */
	public function indexAction() {
		return $this->render('LololShopBundle:Shop:index.html.twig');
	}
	
	/**
	 * Renders the shop for champions
	 */
	public function championsAction() {
		// Get the service
		$stringReplace = $this->container->get('lolol_app.stringReplace');
		
		// Get the parameters
		$folder = $this->container->getParameter('champions_folder');
		$prefixIcons48 = $this->container->getParameter('champions_icons48_prefix');
		$suffixIcons48 = $this->container->getParameter('champions_icons48_suffix');
		
		$em = $this->getDoctrine()->getManager();
		
		$champions = $em->getRepository('LololTeamBundle:Champion')->findAll();
		
		return $this->render('LololShopBundle:Shop:champions.html.twig', array(
				'champions' => $champions,
				'folder' => $folder,
				'prefixIcons48' => $prefixIcons48,
				'suffixIcons48' => $suffixIcons48));
	}
	
	/**
	 * Renders the shop for items
	 */
	public function itemsAction() {
		return $this->render('LololShopBundle:Shop:items.html.twig');
	}
}
