<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\TeamBundle\TeamManager\TeamManager as TeamManager;
use Lolol\BattleBundle\BattleManager\BattleTeam as BattleTeam;
use Lolol\BattleBundle\Entity\Battle as Battle;
use Lolol\TeamBundle\Entity\Team as Team;

class BattleManager {
	private $battleLogger;
	private $teamManager;
	
	public function __construct(BattleLogger $battleLogger, TeamManager $teamManager) {
		$this->battleLogger = $battleLogger;
		$this->teamManager = $teamManager;
	}
	public function fight(Team $opponentTeam, Team $attackerTeam) {
		// Initialize battle teams
		$opponentBattleTeam = new BattleTeam($opponentTeam, false, $this->battleLogger);
		$attackerBattleTeam = new BattleTeam($attackerTeam, true, $this->battleLogger);
		
		$battle = new Battle($opponentTeam, $attackerTeam);
		
		$this->battleLogger->log('Attacker team: ' . $attackerTeam->getName(), '', true, BattleIcon::ATTACKER);
		$this->battleLogger->log($attackerTeam->championsToString(), '', false, BattleIcon::ATTACKER);
		$this->battleLogger->log('', '', false, false);
		
		$this->battleLogger->log('Opponent team: ' . $opponentTeam->getName(), '', true, BattleIcon::DEFENDER);
		$this->battleLogger->log($opponentTeam->championsToString(), '', false, BattleIcon::DEFENDER);
		$this->battleLogger->log('', '', false, false);
		
		$this->battleLogger->log('Battle starts', '', true, BattleIcon::CLOCK);
		
		$time = 0;
		
		$battleResult = $this->computeResult($opponentBattleTeam, $attackerBattleTeam);
		while ($battleResult == null) {
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
			$battleResult = $this->computeResult($opponentBattleTeam, $attackerBattleTeam);
		}

		$battle->setResult($battleResult);
		
		$this->battleLogger->log('end fight');
		
		return $battle;
	}

	/**
	 * Compute the result of the battle, DRAW, WIN, LOOSE
	 * @return the result or null if the battle is not finished
	 */
	public function computeResult(BattleTeam $opponentBattleTeam, BattleTeam $attackerBattleTeam) {
		if($opponentBattleTeam->hasLost() && $attackerBattleTeam->hasLost()) {
			return BattleResult::DRAW;
		}
		if($opponentBattleTeam->hasLost()) {
			return BattleResult::WIN;
		}
		if($attackerBattleTeam->hasLost()) {
			return BattleResult::LOST;
		}
		return null;
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