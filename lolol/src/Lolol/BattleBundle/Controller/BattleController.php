<?php

namespace Lolol\BattleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Lolol\TeamBundle\Entity\Team;
use Lolol\BattleBundle\BattleManager\BattleTeam;
use Lolol\BattleBundle\Entity\Battle;

class BattleController extends Controller {
	public function indexAction() {
		return $this->render('LololBattleBundle:Battle:index.html.twig');
	}
	
	/**
	 * Display attack page
	 */
	public function attackAction() {
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
			
			$i ++;
		}
		
		// Get opponents
		$opponentTeams = $em->getRepository('LololTeamBundle:Team')->findOpponents($this->getUser());
		
		// Get champions by teams
		$i = 0;
		$opponents = array();
		$defender = array();
		foreach($opponentTeams as $opponentTeam) {
			$champions = $championRepo->getWithChampionsByTeam($opponentTeam, 'ct.position');
			$opponents[$i]['team'] = $opponentTeam;
			$opponents[$i]['champions'] = $champions;
			
			$i ++;
		}
		
		return $this->render('LololBattleBundle:Battle:attack.html.twig', array(
				'results' => $result,
				'opponents' => $opponents,
				'folder' => $folder,
				'prefixIcons48' => $prefixIcons48,
				'suffixIcons' => $suffixIcons,
				'teamSize' => $teamSize));
	}
	
	/**
	 * Display defense page
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
	
	/**
	 * Process the attack.
	 */
	public function attackProcessAction() {
		// Get the services
		$translator = $this->get('translator');
		$logger = $this->get('logger');
		$battleManager = $this->get('lolol_battle.battleManager');
		
		// Get the parameters
		$folder = $this->container->getParameter('champions_folder');
		$prefixIcons48 = $this->container->getParameter('champions_icons48_prefix');
		$suffixIcons = $this->container->getParameter('champions_icons_suffix');
		$teamSize = $this->container->getParameter('team_size');
		
		// Get the opponent/attacker teams
		$request = $this->getRequest();
		$opponentTeamId = $request->request->get('opponentTeam');
		$attackerTeamId = $request->request->get('attackerTeam');
		
		// Check the inputs
		$inputsError = false;
		if (empty($opponentTeamId)) {
			$message = $translator->trans('battle.attack.noOpponent');
			$this->get('session')->getFlashBag()->add('error', $message);
			
			$inputsError = true;
		}
		
		if (empty($attackerTeamId)) {
			$message = $translator->trans('battle.attack.noAttacker');
			$this->get('session')->getFlashBag()->add('error', $message);
			
			$inputsError = true;
		}
		
		if ($inputsError) {
			return $this->redirect($this->generateUrl('lolol_battle_attack'));
		}
		
		// Battle
		$logger->info('Battle starts');
		$em = $this->getDoctrine()->getManager();
		$opponentTeam = $em->getRepository('LololTeamBundle:Team')->findOneByIdWithChampions($opponentTeamId);
		$attackerTeam = $em->getRepository('LololTeamBundle:Team')->findOneByIdWithChampions($attackerTeamId);
		
		// Fight
		$battle = $battleManager->fight($opponentTeam, $attackerTeam);
		
		$logs = $battleManager->getBattleLogger()->getLogs();
		$logs[]['text'] = 'test';
		
		$logs = $battleManager->getBattleLogger()->getLogs();

		return $this->render('LololBattleBundle:Battle:report.html.twig', array(
				'folder' => $folder,
				'team' => $opponentBattleTeam,
				'result' => $battle->getResult(),
				'logs' => $logs,
				'prefixIcons48' => $prefixIcons48,
				'suffixIcons' => $suffixIcons,
				'teamSize' => $teamSize));
	}
}
