<?php

namespace Lolol\TeamBundle\TeamManager;

use Lolol\TeamBundle\Entity\Team as Team;
use Lolol\BattleBundle\BattleManager\BattleLogger as BattleLogger;
use Lolol\AppBundle\AppManager\ChampionManager as ChampionManager;
use Lolol\BattleBundle\BattleManager\Injury as Injury;

class TeamManager {
	private $battleLogger;
	private $championManager;
	public function __construct(BattleLogger $battleLogger, ChampionManager $championManager) {
		$this->battleLogger = $battleLogger;
		$this->championManager = $championManager;
	}
	public function prepare(Team $team, $attacker) {
		$team->setAttacker($attacker);
		foreach($team->getChampionsTeam() as $championTeam) {
			$this->championManager->prepare($championTeam->getChampion(), $attacker);
		}
	}
	
	/**
	 * Function hasLost()
	 * Indique si l'équipe en jeu a perdu
	 *
	 * @return boolean a-t-elle perdu ? Si oui, true, sinon, false
	 */
	public function hasLost(Team $team) {
		// On a perdu, jusqu'à preuve du contraire
		$lost = true;
		// Condition de non-défaite : au moins un Champion encore en vie
		foreach($team->getChampionsTeam() as $championTeam) {
			if ($this->championManager->isAlive($championTeam->getChampion())) {
				$lost = false;
				break;
			}
		}
		return $lost;
	}
	
	/**
	 * Function play()
	 * Fait faire une action minimale de l'équipe à un temps donné
	 * Chaque Champion peut intervenir, mais un seul le fait
	 * Retourne soit une blessure à infliger, soit false s'il n'y a aucun cooldown prêt
	 *
	 * @param float $time
	 *        	dans la partie
	 * @return IInjury blessure à infliger, ou false sinon
	 */
	public function play(Team $team, $time = 0) {
		// On n'a rien fait, jusqu'à preuve du contraire
		$this->battleLogger->log('L\'équipe ' . $team->getName() . ' regarde qui peut jouer au round ' . $time, '', false, $team->getIcon());
		$action = false;
		
		// Recherche d'un champion qui peut jouer
		foreach($team->getChampionsTeam() as $championTeam) {
			$champion = $championTeam->getChampion();
			$this->battleLogger->log('L\'équipe ' . $team->getName() . ' demande au Champion ' . $champion->getName(), '', false, $team->getIcon());
			
			$injury = $this->championManager->play($champion, $time);
			$action = $injury;
			if ($injury !== false) {
				$this->battleLogger->log('L\'équipe ' . $team->getName() . ' a fait jouer son Champion ' . $champion->getName(), '', false, $team->getIcon());
				break;
			}
			else {
				$this->battleLogger->log('L\'équipe ' . $team->getName() . ' n\'a pas pu faire jouer son Champion ' . $champion->getName(), '', false, $team->getIcon());
			}
		}
		if ($action === false) {
			$this->battleLogger->log('L\'équipe ' . $team->getName() . ' n\'a plus de Champion activable au tour ' . $time, '', false, $team->getIcon());
		}
		return $action;
	}
	
	/**
	 * Function setInjury()
	 * Inflige la blessure passée en paramètre à un Champion de l'équipe
	 *
	 * @param
	 *        	IInjury	p_injury	La blessure à infliger
	 */
	public function setInjury(Team $team, Injury $injury) {
		$this->battleLogger->log('L\'équipe ' . $team->getName() . ' regarde à quel Champion infliger la blessure');
		// Recherche d'un champion encore en vie
		foreach($team->getChampionsTeam() as $championTeam) {
			$champion = $championTeam->getChampion();
			if ($this->championManager->isAlive($champion)) {
				$this->battleLogger->log('L\'équipe ' . $team->getName() . ' inflige la blessure à ' . $champion->getName() . ' car il est encore en vie');
				$this->championManager->setInjury($champion, $injury);
				break;
			}
		}
	}
}