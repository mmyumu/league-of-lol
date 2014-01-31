<?php

namespace Lolol\TeamBundle\TeamManager;

use Lolol\BattleBundle\BattleManager\BattleTeam as BattleTeam;
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
	public function prepare(BattleTeam $team, $attacker) {
		$team->setAttacker($attacker);
		foreach($team->getChampionsTeam() as $championTeam) {
			$this->championManager->prepare($championTeam->getChampion(), $attacker);
		}
	}
	
	/**
	 * Function setInjury()
	 * Inflige la blessure pass�e en param�tre � un Champion de l'�quipe
	 *
	 * @param
	 *        	IInjury	p_injury	La blessure � infliger
	 */
	public function setInjury(Team $team, Injury $injury) {
		$this->battleLogger->log('L\'�quipe ' . $team->getName() . ' regarde � quel Champion infliger la blessure');
		// Recherche d'un champion encore en vie
		foreach($team->getChampionsTeam() as $championTeam) {
			$champion = $championTeam->getChampion();
			if ($this->championManager->isAlive($champion)) {
				$this->battleLogger->log('L\'�quipe ' . $team->getName() . ' inflige la blessure � ' . $champion->getName() . ' car il est encore en vie');
				$this->championManager->setInjury($champion, $injury);
				break;
			}
		}
	}
}