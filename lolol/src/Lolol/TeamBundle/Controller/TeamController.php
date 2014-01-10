<?php

namespace Lolol\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends Controller {
	/**
	 * Action to display the GUI of the existing teams
	 */
	public function myTeamsAction() {
		return $this->render('LololTeamBundle:Team:myTeams.html.twig');
	}
	/**
	 * Action to display the GUI to build a new team
	 */
	public function teamBuilderAction() {
		// Get the parameters
		$folder = $this->container->getParameter('champions_folder');
		$prefixIcons48 = $this->container->getParameter('champions_icons48_prefix');
		$suffixIcons48 = $this->container->getParameter('champions_icons48_suffix');
		
		$champions = $this->getUser()->getChampions();
		
		return $this->render('LololTeamBundle:Team:newTeam.html.twig', array(
				'team' => array(),
				'champions' => $champions,
				'folder' => $folder,
				'prefixIcons48' => $prefixIcons48,
				'suffixIcons48' => $suffixIcons48));
	}
}
