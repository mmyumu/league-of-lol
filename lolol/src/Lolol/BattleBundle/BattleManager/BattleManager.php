<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\BattleBundle\BattleManager\BattleTeam as BattleTeam;
use Lolol\BattleBundle\Entity\Battle as Battle;
use Lolol\BattleBundle\Entity\Log as Log;
use Lolol\TeamBundle\Entity\Team as Team;
use Symfony\Component\Translation\TranslatorInterface;

class BattleManager {
	/**
	 * The logger of the battle.
	 *
	 * @var BattleLogger
	 */
	private $battleLogger;
	
	/**
	 * The manager to retrieve log types.
	 *
	 * @var LogTypesManager
	 */
	private $ltm;
	
	/**
	 * The translator service
	 *
	 * @var TranslatorInterface
	 */
	private $translator;
	
	/**
	 * Logger
	 *
	 * @var unknown
	 */
	private $logger;
	
	/**
	 * Constructor.
	 *
	 * @param BattleLogger $battleLogger        	
	 * @param TranslatorInterface $translator        	
	 */
	public function __construct(BattleLogger $battleLogger, LogTypesManager $logTypesManager, TranslatorInterface $translator, $logger) {
		$this->battleLogger = $battleLogger;
		$this->ltm = $logTypesManager;
		$this->translator = $translator;
		$this->logger = $logger;
	}
	
	/**
	 * Compute the fight between the 2 teams
	 *
	 * @param Team $opponentTeam        	
	 * @param Team $attackerTeam        	
	 * @return \Lolol\BattleBundle\Entity\Battle
	 */
	public function fight(Team $opponentTeam, Team $attackerTeam) {
		// Initialize battle teams
		$opponentBattleTeam = new BattleTeam($opponentTeam, false, $this->battleLogger, $this->translator);
		$attackerBattleTeam = new BattleTeam($attackerTeam, true, $this->battleLogger, $this->translator);
		
		$battle = new Battle($opponentTeam, $attackerTeam);
		
		$battle->addLog(new Log('battle.report.attacker', array(), $this->ltm->get(array(
				LogTypes::STRONG,
				LogTypes::PRESENTATION)), BattleIcon::ATTACKER));
		$battle->addLog(new Log($attackerTeam->championsToString(), array(), $this->ltm->get(array(
				LogTypes::PRESENTATION))));
		$battle->addLog(new Log('', array(), array(), null));
		
		$battle->addLog(new Log('battle.report.opponent'), '', false, BattleIcon::DEFENDER);
		$battle->addLog(new Log($opponentTeam->championsToString()), '', false, BattleIcon::DEFENDER);
		$battle->addLog(new Log(''), '', false, false);
		
		$battle->addLog(new Log('battle.report.start'), '', true, BattleIcon::CLOCK);
		
		$time = 0;
		
		$battleResult = $this->computeResult($opponentBattleTeam, $attackerBattleTeam);
		while ( $battleResult == null ) {
			// Le combat continue jusqu'à ce qu'une équipe ait perdu
			
			$battle->addLog(new Log('battle.report.roundBegin', array(
					'%time%' => $time)));
			// $this->battleLogger->log($this->translator->trans('battle.report.roundBegin', array('%time%' => $time)), '', true, BattleIcon::CLOCK);
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
			
			$this->battleLogger->log($this->translator->trans('battle.report.roundEnd', array(
					'%time%' => $time)), '', true);
			
			$time ++;
			$battleResult = $this->computeResult($opponentBattleTeam, $attackerBattleTeam);
		}
		
		$battle->setResult($battleResult);
		
		return $battle;
	}
	
	/**
	 * Compute the result of the battle, DRAW, WIN, LOOSE
	 *
	 * @return the result or null if the battle is not finished
	 */
	public function computeResult(BattleTeam $opponentBattleTeam, BattleTeam $attackerBattleTeam) {
		$opponentLost = $opponentBattleTeam->hasLost();
		$attackerLost = $attackerBattleTeam->hasLost();
		if ($opponentLost && $attackerLost) {
			// $this->battleLogger->log($this->translator->trans('battle.report.draw'), 'text-warning', true);
			return BattleResult::DRAW;
		}
		if ($opponentLost) {
			// $this->battleLogger->log($this->translator->trans('battle.report.victory'), 'text-success', true);
			return BattleResult::VICTORY;
		}
		if ($attackerLost) {
			// $this->battleLogger->log($this->translator->trans('battle.report.defeat'), 'text-danger', true);
			return BattleResult::DEFEAT;
		}
		return null;
	}
	
	/**
	 * Get the battle logger.
	 */
	public function getBattleLogger() {
		return $this->battleLogger;
	}
}