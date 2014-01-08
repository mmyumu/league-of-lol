<?php

namespace Lolol\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller {
	public function indexAction() {
		return $this->render('LololAppBundle:App:index.html.twig');
	}
	public function championAction($id) {
		// Get the parameters
		$folder = $this->container->getParameter('champions_folder');
		$prefixIcons = $this->container->getParameter('champions_icons48_prefix');
		$suffixIcons = $this->container->getParameter('champions_icons48_suffix');
		
		$em = $this->getDoctrine()->getManager();
		
		$champion = $em->getRepository('LololAppBundle:Champion')->findOneById($id);
		
		$ownedChampion = false;
		foreach($this->getUser()->getChampions() as $userChampion) {
			if($userChampion->getId() == $champion->getId()) {
				$ownedChampion = true;
				break;
			}
		}
		
		return $this->render('LololAppBundle:App:champion.html.twig', array(
				'champion' => $champion,
				'folder' => $folder,
				'prefixIcons' => $prefixIcons,
				'suffixIcons' => $suffixIcons,
				'owned' => $ownedChampion,
		));
	}
}
