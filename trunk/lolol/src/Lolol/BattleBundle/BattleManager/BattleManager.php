<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\BattleBundle\BattleManager\BattleTeam as BattleTeam;
use Lolol\BattleBundle\Entity\Battle as Battle;
use Lolol\TeamBundle\Entity\Team as Team;
use Symfony\Component\Translation\TranslatorInterface;

class BattleManager {
	private $battleLogger;
	private $translator;
	
	public function __construct(BattleLogger $battleLogger, TranslatorInterface $translator) {
		$this->battleLogger = $battleLogger;
		$this->translator = $translator;
	}
	public function fight(Team $opponentTeam, Team $attackerTeam) {
		// Initialize battle teams
		$opponentBattleTeam = new BattleTeam($opponentTeam, false, $this->battleLogger, $this->translator);
		$attackerBattleTeam = new BattleTeam($attackerTeam, true, $this->battleLogger, $this->translator);
		
		$battle = new Battle($opponentTeam, $attackerTeam);
		
		
		
		$this->battleLogger->log($this->translator->trans('battle.report.attacker') . ': ' . $attackerTeam->getName(), '', true, BattleIcon::ATTACKER);
		$this->battleLogger->log($attackerTeam->championsToString(), '', false, BattleIcon::ATTACKER);
		$this->battleLogger->log('', '', false, false);
		
		$this->battleLogger->log($this->translator->trans('battle.report.opponent') . ': ' . $opponentTeam->getName(), '', true, BattleIcon::DEFENDER);
		$this->battleLogger->log($opponentTeam->championsToString(), '', false, BattleIcon::DEFENDER);
		$this->battleLogger->log('', '', false, false);
		
		$this->battleLogger->log($this->translator->trans('battle.report.start'), '', true, BattleIcon::CLOCK);
		
		$time = 0;
		
		$battleResult = $this->computeResult($opponentBattleTeam, $attackerBattleTeam);
		while ($battleResult == null) {
			// Le combat continue jusqu'à ce qu'une équipe ait perdu
			$this->battleLogger->log($this->translator->trans('battle.report.roundBegin', array('%roundNumber%' => $time)), '', true, BattleIcon::CLOCK);
			$actionOpponent = true;
			$actionAttacker = true;
			// On continue d'agir tant qu'on a des actions à faire
			while ( ($actionOpponent !== false) || ($actionAttacker !== false) ) {
				// Chaque équipe joue en même temps
				$actionOpponent = $opponentBattleTeam->play($time);
				$actionAttacker = $attackerBattleTeam->play($time);
				
				// On résout les blessures
				if (false !== $actionAttacker) {
					$opponentBattleTeam->setInjury($actionAttacker);
				}
				if (false !== $actionOpponent) {
					$attackerBattleTeam->setInjury($actionOpponent);
				}
			}
			// Tout le monde a donc joué simultannément, sans vérification d'une victoire intermédiaire
			// C'est seulement après que chacun ait fait son action du moment qu'on avance le temps
			
			$this->battleLogger->log($this->translator->trans('battle.report.roundEnd', array('%roundNumber%' => $time)), '', true);
			
			$time ++;
			$battleResult = $this->computeResult($opponentBattleTeam, $attackerBattleTeam);
		}

		$battle->setResult($battleResult);
		
		return $battle;
	}

	/**
	 * Compute the result of the battle, DRAW, WIN, LOOSE
	 * @return the result or null if the battle is not finished
	 */
	public function computeResult(BattleTeam $opponentBattleTeam, BattleTeam $attackerBattleTeam) {
		$opponentLost = $opponentBattleTeam->hasLost();
		$attackerLost = $attackerBattleTeam->hasLost();
		if($opponentLost && $attackerLost) {
			$this->battleLogger->log($this->translator->trans('battle.report.draw'), 'text-warning', true);
			return BattleResult::DRAW;
		}
		if($opponentLost) {
			$this->battleLogger->log($this->translator->trans('battle.report.victory'), 'text-success', true);
			return BattleResult::VICTORY;
		}
		if($attackerLost) {
			$this->battleLogger->log($this->translator->trans('battle.report.defeat'), 'text-danger', true);
			return BattleResult::DEFEAT;
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