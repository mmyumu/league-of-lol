<?php

namespace Lolol\TeamBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Lolol\TeamBundle\Entity\Team;
use Lolol\TeamBundle\Entity\ChampionTeam;
use Lolol\AppBundle\Exceptions\CustomException;
use Lolol\AppBundle\Exceptions\NotAjaxException;
use Lolol\AppBundle\Exceptions\MalformedTeamException;
use Lolol\AppBundle\Exceptions\AccessRightException;

class TeamController extends Controller {
	/**
	 * Action to display the GUI of the existing teams
	 */
	public function myTeamsAction() {
		// Get the parameters
		$folder = $this->container->getParameter('champions_folder');
		$prefixIcons48 = $this->container->getParameter('champions_icons48_prefix');
		$suffixIcons48 = $this->container->getParameter('champions_icons48_suffix');
		$teamSize = $this->container->getParameter('team_size');
		
		// Get teams
		$em = $this->getDoctrine()->getManager();
		$teams = $em->getRepository('LololTeamBundle:Team')->findByUser($this->getUser());
		$championRepo = $em->getRepository('LololTeamBundle:ChampionTeam');
		
		// Get champions by teams
		$i = 0;
		foreach($teams as $team) {
			$champions = $championRepo->getWithChampionsByTeam($team, 'ct.position');
			
			$result[$i]['team'] = $team;
			$result[$i]['champions'] = $champions;
			$i ++;
		}
		
		return $this->render('LololTeamBundle:Team:myTeams.html.twig', array(
				"results" => $result,
				'folder' => $folder,
				'prefixIcons48' => $prefixIcons48,
				'suffixIcons48' => $suffixIcons48,
				'teamSize' => $teamSize));
	}
	/**
	 * * Action to display the GUI to build a new team or edit an existing team
	 * @ParamConverter("team", class="Lolol\TeamBundle\Entity\Team")
	 */
	public function teamBuilderAction(Team $team = null) {
		// Get the parameters
		$folder = $this->container->getParameter('champions_folder');
		$prefixIcons48 = $this->container->getParameter('champions_icons48_prefix');
		$suffixIcons48 = $this->container->getParameter('champions_icons48_suffix');
		$teamSize = $this->container->getParameter('team_size');
		
		$champions = $this->getUser()->getChampions();
		
		// If a team is given as route parameter ...
		if ($team != null) {
			// ... open the builder as edition mode
			$em = $this->getDoctrine()->getManager();
			$championRepo = $em->getRepository('LololTeamBundle:ChampionTeam');
			
			$teamChampions['team'] = $team;
			$teamChampions['champions'] = $championRepo->getWithChampionsByTeam($team, 'ct.position');
		}
		else {
			// ... otherwise open the builder as creation mode
			$teamChampions['team'] = null;
			$teamChampions['champions'] = null;
		}
		
		return $this->render('LololTeamBundle:Team:teamBuilder.html.twig', array(
				'teamChampions' => $teamChampions,
				'champions' => $champions,
				'folder' => $folder,
				'prefixIcons48' => $prefixIcons48,
				'suffixIcons48' => $suffixIcons48,
				'teamSize' => $teamSize));
	}
	
	/**
	 * Action to save a team (AJAX)
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function teamBuilderSaveAction(Request $request) {
		try {
			// Test AJAX call
			if (!$request->isXmlHttpRequest()) {
				throw new NotAjaxException();
			}
			
			// Get the parameters
			$teamSize = $this->container->getParameter('team_size');
			
			// Get the services
			$translator = $this->get('translator');
			$logger = $this->get('logger');
			
			// Get the POST datas
			$teamName = $request->request->get('teamName');
			$idTeam = $request->request->get('idTeam');
			
			$em = $this->getDoctrine()->getManager();
			
			// Check if the team has enough champions
			for($i = 0; $i < $teamSize; $i ++) {
				$championId = $request->request->get('team' . $i);
				if (!empty($championId)) {
					$team[$i] = $request->request->get('team' . $i);
				}
				else {
					$message = $translator->trans('team.built.error.missingChampions');
					throw new MalformedTeamException($message);
				}
			}
			
			// Check team name
			if (empty($teamName)) {
				$message = $translator->trans('team.built.error.missingName');
				throw new MalformedTeamException($message);
			}
			
			// Check if champions are owned by the user
			$logger->info('Checking if the user owns all the champions of the team with name=' . $teamName);
			$champions = array();
			for($i = 0; $i < $teamSize; $i ++) {
				// Get the ID of the champion and retrieve it from DB
				$championId = substr($team[$i], 9);
				$champion = $em->getRepository('LololAppBundle:Champion')->findOneById($championId);
				
				$found = false;
				foreach($this->getUser()->getChampions() as $userChampion) {
					if ($userChampion->getId() == $champion->getId()) {
						$found = true;
						break;
					}
				}
				
				if (!$found) {
					$message = $translator->trans('team.built.error.notOwnedChampion', array(
							'%championName%' => $champion->getName()));
					throw new MalformedTeamException($message);
				}
				
				$champions[$i] = $champion;
			}
			$logger->info('Champions are valid.');
			
			if ($idTeam == null) {
				$logger->info('Creating new team with name=' . $teamName);
				
				// Create new team
				$myTeam = new Team();
				$myTeam->setUser($this->getUser());
			}
			else {
				// Edit team: remove old champions then add new ones
				$myTeam = $em->getRepository('LololTeamBundle:Team')->findOneById($idTeam);
				
				$logger->info('Editing team with name=' . $myTeam->getName() . ' (new name=' . $teamName . ')');
				
				if ($myTeam->getUser()->getId() != $this->getUser()->getId()) {
					throw new AccessRightException('Cannot access this team');
				}
				
				$championRepo = $em->getRepository('LololTeamBundle:ChampionTeam');
				$oldChampions = $championRepo->getWithChampionsByTeam($myTeam);
				
				foreach($oldChampions as $champion) {
					$em->remove($champion);
				}
			}
			
			// Add the team to DB
			$myTeam->setName($teamName);
			$em->persist($myTeam);
			$em->flush();
			
			$logger->info('Team persisted with name=' . $teamName);
			
			// Add the champions to the team
			$position = 0;
			foreach($champions as $champion) {
				$logger->info('Adding champion (ID=' . $championId . ') to the team');
				
				// Link the champion to the team
				$championTeam = new ChampionTeam();
				$championTeam->setTeam($myTeam);
				$championTeam->setChampion($champion);
				$championTeam->setPosition($position);
				
				$em->persist($championTeam);
				$position++;
			}
			
			// Persist link between champions and team
			$em->flush();
			
			$response = array(
					"code" => 100,
					"success" => true,
					"id" => $myTeam->getId());
		}
		catch(CustomException $e) {
			$response = array(
					"code" => 100,
					"success" => false,
					"error" => $e->getMessage());
		}
		return new Response(json_encode($response));
	}
}
