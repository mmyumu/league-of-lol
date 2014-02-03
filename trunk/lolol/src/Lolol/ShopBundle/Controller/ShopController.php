<?php

namespace Lolol\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Lolol\AppBundle\Entity\Champion;

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
		// Get the parameters
		$folder = $this->container->getParameter('champions_folder');
		$prefixIcons48 = $this->container->getParameter('champions_icons48_prefix');
		$suffixIcons = $this->container->getParameter('champions_icons_suffix');
		
		$em = $this->getDoctrine()->getManager();
		
		$champions = $em->getRepository('LololAppBundle:Champion')->findBy(array(), array('name' => 'ASC'));
		
		return $this->render('LololShopBundle:Shop:champions.html.twig', array(
				'champions' => $champions,
				'folder' => $folder,
				'prefixIcons48' => $prefixIcons48,
				'suffixIcons' => $suffixIcons
		));
	}
	
	/**
	 * Renders the shop for items
	 */
	public function itemsAction() {
		return $this->render('LololShopBundle:Shop:items.html.twig');
	}
	
	/**
	 * Buy the champion for the current user.
	 *
	 * @param int $id        	
	 */
	public function buyChampionAction(Champion $champion) {
		$em = $this->getDoctrine()->getManager();
		
		$user = $this->getUser();
		
		$owned = false;
		foreach($user->getChampions() as $userChampion) {
			if ($userChampion->getId() == $champion->getId()) {
				$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('champion.owned.error', array(
						'%championName%' => $champion->getName()
				)));
				$owned = true;
				break;
			}
		}
		
		if (!$owned) {
			$user->addChampion($champion);
			$em->persist($user);
			$em->flush();
		}
		
		// Redirect to previous page, or homepage if no previous page
		$url = $this->getRequest()->headers->get('referer');
		if (empty($url)) {
			$url = $this->generateUrl('lolol_app_homepage');
		}
		return $this->redirect($url);
	}
}
