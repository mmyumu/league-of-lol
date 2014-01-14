<?php

namespace Lolol\TeamBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Lolol\TeamBundle\Entity\Team;
use Lolol\TeamBundle\Entity\ChampionTeam;
use Lolol\AppBundle\Exceptions\NotAjaxException;
use Lolol\AppBundle\Exceptions\MalformedTeamException;

class TeamController extends Controller {
	/**
	 * Action to display the GUI of the existing teams
	 */
	public function myTeamsAction() {
		// Get the parameters
		$folder = $this->container->getParameter('champions_folder');
		$prefixIcons48 = $this->container->getParameter('champions_icons48_prefix');
		$suffixIcons48 = $this->container->getParameter('champions_icons48_suffix');
		
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
				'suffixIcons48' => $suffixIcons48));
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
		
		$champions = $this->getUser()->getChampions();
		
		if($team != null) {
			$em = $this->getDoctrine()->getManager();
			$championRepo = $em->getRepository('LololTeamBundle:ChampionTeam');
			
			$teamChampions['team'] = $team;
			$teamChampions['champions'] = $championRepo->getWithChampionsByTeam($team, 'ct.position');
		} else {
			$teamChampions['team'] = null;
			$teamChampions['champions'] = null;			
		}
		
		return $this->render('LololTeamBundle:Team:teamBuilder.html.twig', array(
				'teamChampions' => $teamChampions,
				'champions' => $champions,
				'folder' => $folder,
				'prefixIcons48' => $prefixIcons48,
				'suffixIcons48' => $suffixIcons48));
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
			
			// Check if
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
			
			if (empty($teamName)) {
				$message = $translator->trans('team.built.error.missingName');
				throw new MalformedTeamException($message);
			}
			
			$em = $this->getDoctrine()->getManager();
			
			if ($idTeam == null) {
				$logger->info('Creating new team with name=' . $teamName);
				
				// Create new team
				$myTeam = new Team();
				$myTeam->setName($teamName);
				$myTeam->setUser($this->getUser());
				
				$em->persist($myTeam);
				
				// Flush to get the auto increment ID of the Team, otherwise the persist of ChampionTeam entities doesn't work
				$em->flush();
				
				for($i = 0; $i < $teamSize; $i ++) {
					// Get champion with ID
					$championId = substr($team[$i], 9);
					$champion = $em->getRepository('LololAppBundle:Champion')->findOneById($championId);
					
					$logger->info('Adding champion (ID=' . $championId . ') to the team');
					
					// Link the champion to the team
					$championTeam = new ChampionTeam();
					$championTeam->setTeam($myTeam);
					$championTeam->setChampion($champion);
					$championTeam->setPosition($i);
					
					$em->persist($championTeam);
				}
			}
			else {
				// Edit team: remove old champions then add new ones
			}
			
			$em->flush();
			
			$response = array(
					"code" => 100,
					"success" => true);
		}
		catch(\Exception $e) {
			$response = array(
					"code" => 100,
					"success" => false,
					"error" => $e->getMessage());
		}
		return new Response(json_encode($response));
	}
}
