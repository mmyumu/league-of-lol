<?php

namespace Lolol\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends Controller {
	/**
	 * Action to display the GUI of the existing teams
	 */
	public function myTeamsAction() {
		return $this->render( 'LololTeamBundle:Team:myTeams.html.twig' );
	}
	/**
	 * Action to display the GUI to build a new team
	 */
	public function newTeamAction() {
		return $this->render( 'LololTeamBundle:Team:newTeam.html.twig' );
	}
}
