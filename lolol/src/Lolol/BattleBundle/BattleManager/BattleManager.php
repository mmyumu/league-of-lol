<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\TeamBundle\TeamManager\TeamManager as TeamManager;
use Lolol\TeamBundle\Entity\Team as Team;
use Lolol\BattleBundle\Entity\Battle as Battle;

class BattleManager {
	private $battleLogger;
	private $teamManager;
	
	public function __construct(BattleLogger $battleLogger, TeamManager $teamManager) {
		$this->battleLogger = $battleLogger;
		$this->teamManager = $teamManager;
	}
	public function fight(Team $opponentTeam, Team $attackerTeam) {
		$battle = new Battle($attackerTeam, $opponentTeam);
		$attackerTeam->setAttacker(true);
		$opponentTeam->setAttacker(false);
		
		$this->battleLogger->log('Attacker team: ' . $attackerTeam->getName(), '', true, BattleIcon::ATTACKER);
		$this->battleLogger->log($attackerTeam->championsToString(), '', false, BattleIcon::ATTACKER);
		$this->battleLogger->log('', '', false, false);
		
		$this->battleLogger->log('Opponent team: ' . $opponentTeam->getName(), '', true, BattleIcon::DEFENDER);
		$this->battleLogger->log($opponentTeam->championsToString(), '', false, BattleIcon::DEFENDER);
		$this->battleLogger->log('', '', false, false);
		
		$this->battleLogger->log('Battle starts', '', true, BattleIcon::CLOCK);
		
		$this->prepare($battle);
		
		$time = 0;
		
		$loosers = $this->getLoosers($battle);
		while (count($loosers) == 0) {
			// Le combat continue jusqu'à ce qu'une équipe ait perdu
			$this->battleLogger->log('Début round ' . $time, '', false, BattleIcon::CLOCK);
			$actionOpponent = true;
			$actionAttacker = true;
			// On continue d'agir tant qu'on a des actions à faire
			while ( ($actionOpponent !== false) || ($actionAttacker !== false) ) {
				// Chaque équipe joue en même temps
				$actionOpponent = $this->teamManager->play($opponentTeam, $time);
				$actionAttacker = $this->teamManager->play($attackerTeam, $time);
				
				// On résout les blessures
				if (false !== $actionAttacker) {
					$this->teamManager->setInjury($attackerTeam, $actionAttacker);
				}
				if (false !== $actionOpponent) {
					$this->teamManager->setInjury($opponentTeam, $actionOpponent);
				}
			}
			// Tout le monde a donc joué simultannément, sans vérification d'une victoire intermédiaire
			// C'est seulement après que chacun ait fait son action du moment qu'on avance le temps
			$this->battleLogger->log('Fin round ' . $time);
			$this->battleLogger->log('On vérifie si chacune des deux équipes a bien au moins un Champion encore debout');
			
			$time ++;
			$loosers = $this->getLoosers($battle);
		}

		// Initialize the result of the battle
		if(count($loosers) == 2) {
			$battle->setResult(BattleResult::DRAW);
		} else {
			if($loosers[0] == $opponentTeam) {
				$battle->setResult(BattleResult::WIN);
			} else {
				$battle->setResult(BattleResult::LOOSE);
			}
		}
		
		$this->battleLogger->log('end fight');
		
		return $battle;
	}
	public function prepare(Battle $battle) {
		$this->teamManager->prepare($battle->getOpponentTeam(), false);
		$this->teamManager->prepare($battle->getAttackerTeam(), true);
	}
	public function getLoosers(Battle $battle) {
		$loosers = array();
		if($this->teamManager->hasLost($battle->getOpponentTeam())) {
			$loosers[] = $this->teamManager->hasLost($battle->getOpponentTeam());
		}
		if($this->teamManager->hasLost($battle->getAttackerTeam())) {
			$loosers[] = $this->teamManager->hasLost($battle->getAttackerTeam());
		}
		
		return $loosers;
	}
	
	public function isOver(Battle $battle) {
		return $this->teamManager->hasLost($battle->getOpponentTeam()) || $this->teamManager->hasLost($battle->getAttackerTeam());
	}
	public function getBattleLogger() {
		return $this->battleLogger;
	}
}