<?php

namespace Lolol\BattleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Lolol\TeamBundle\Entity\Team;

class BattleController extends Controller {
	public function indexAction() {
		return $this->render('LololBattleBundle:Battle:index.html.twig');
	}
	
	/**
	 *	Display attack page 
	 */
	public function attackAction() {
		// Get the parameters
		$folder = $this->container->getParameter('champions_folder');
		$prefixIcons20 = $this->container->getParameter('champions_icons20_prefix');
		$suffixIcons = $this->container->getParameter('champions_icons_suffix');
		$teamSize = $this->container->getParameter('team_size');
		
		// Get teams
		$em = $this->getDoctrine()->getManager();
		$teams = $em->getRepository('LololTeamBundle:Team')->findByUser($this->getUser());
		$championRepo = $em->getRepository('LololTeamBundle:ChampionTeam');
		
		// Get champions by teams
		$i = 0;
		$result = array();
		$defender = array();
		foreach($teams as $team) {
			$champions = $championRepo->getWithChampionsByTeam($team, 'ct.position');
			$result[$i]['team'] = $team;
			$result[$i]['champions'] = $champions;
				
			// Initialize the defender team
			if ($team->isDefender()) {
				$defender['team'] = $team;
				$defender['champions'] = $champions;
			}
			$i ++;
		}
		
		return $this->render('LololBattleBundle:Battle:attack.html.twig', array(
				'results' => $result,
				'defender' => $defender,
				'folder' => $folder,
				'prefixIcons20' => $prefixIcons20,
				'suffixIcons' => $suffixIcons,
				'teamSize' => $teamSize));
	}
	
	/**
	 *	Display defense page
	 */
	public function defenseAction() {
		// Get the parameters
		$folder = $this->container->getParameter('champions_folder');
		$prefixIcons48 = $this->container->getParameter('champions_icons48_prefix');
		$suffixIcons = $this->container->getParameter('champions_icons_suffix');
		$teamSize = $this->container->getParameter('team_size');
		
		// Get teams
		$em = $this->getDoctrine()->getManager();
		$teams = $em->getRepository('LololTeamBundle:Team')->findByUser($this->getUser());
		$championRepo = $em->getRepository('LololTeamBundle:ChampionTeam');
		
		// Get champions by teams
		$i = 0;
		$result = array();
		$defender = array();
		foreach($teams as $team) {
			$champions = $championRepo->getWithChampionsByTeam($team, 'ct.position');
			$result[$i]['team'] = $team;
			$result[$i]['champions'] = $champions;
			
			// Initialize the defender team
			if ($team->isDefender()) {
				$defender['team'] = $team;
				$defender['champions'] = $champions;
			}
			$i ++;
		}
		
		return $this->render('LololBattleBundle:Battle:defense.html.twig', array(
				'results' => $result,
				'defender' => $defender,
				'folder' => $folder,
				'prefixIcons48' => $prefixIcons48,
				'suffixIcons' => $suffixIcons,
				'teamSize' => $teamSize));
	}
	
	/**
	 * Promote the given team as defenders team.
	 *
	 * @param Team $team        	
	 */
	public function defensePromoteAction(Team $defenderTeam = null) {
		// Get teams
		$em = $this->getDoctrine()->getManager();
		$teams = $em->getRepository('LololTeamBundle:Team')->findByUser($this->getUser());
		
		foreach($teams as $team) {
			$team->setDefender(false);
			$em->persist($team);
		}
		
		$defenderTeam->setDefender(true);
		$em->persist($defenderTeam);
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('lolol_battle_defense'));
	}
}
