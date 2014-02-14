<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\BattleBundle\Entity\Battle as Battle;
use Lolol\BattleBundle\Entity\Log as Log;
use Lolol\TeamBundle\Entity\Team as Team;
use Symfony\Component\Translation\TranslatorInterface;

class BattleTeam extends Team {
	
	/**
	 * Team engaged in the battle.
	 *
	 * @var Team
	 */
	private $team;
	
	/**
	 * Tell whether the team is attacker or not.
	 *
	 * @var boolean
	 */
	private $attacker;
	
	/**
	 * The battle
	 *
	 * @var Battle
	 */
	private $battle;
	
	/**
	 * The log types manager to retrieve log types
	 *
	 * @var LogTypesManager
	 */
	private $ltm;
	
	/**
	 * The champions of the team
	 *
	 * @var BattleChampion
	 */
	private $battleChampions;
	
	/**
	 * Initialize the team for the battle
	 *
	 * @param Team $team        	
	 * @param boolean $attacker        	
	 */
	public function __construct(Team $team, $attacker, Battle $battle, LogTypesManager $ltm) {
		$this->team = $team;
		$this->attacker = $attacker;
		$this->battle = $battle;
		$this->ltm = $ltm;
		
		foreach($team->getChampionsTeam() as $championTeam) {
			$this->battleChampions[$championTeam->getPosition()] = new BattleChampion($championTeam->getChampion(), $attacker, $battle, $ltm);
		}
	}
	
	/**
	 * Get the team.
	 */
	public function getTeam() {
		return $this->team;
	}
	
	/**
	 * Tells whether the team is attacker or not in this battle.
	 */
	public function isAttacker() {
		return $this->attacker;
	}
	
	/**
	 * Get the icon name according to the function of the team in the battle.
	 *
	 * @return string
	 */
	public function getIcon() {
		if ($this->isAttacker()) {
			return BattleIcon::ATTACKER;
		}
		else {
			return BattleIcon::DEFENDER;
		}
	}
	
	/**
	 * Function hasLost()
	 * Indique si l'équipe en jeu a perdu
	 *
	 * @return boolean a-t-elle perdu ? Si oui, true, sinon, false
	 */
	public function hasLost() {
		// On a perdu, jusqu'à preuve du contraire
		$lost = true;
		// Condition de non-défaite : au moins un Champion encore en vie
		foreach($this->battleChampions as $battleChampion) {
			if ($battleChampion->isAlive()) {
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
	public function play($time = 0) {
		// On n'a rien fait, jusqu'à preuve du contraire
		// $this->battle->addLog(new Log('battle.report.team.canPlay', array(
		// '%teamName%' => $this->team->getName(),
		// '%time%' => $time)), $this->ltm->get(array(
		// LogTypes::TEAM,
		// LogTypes::CAN_PLAY)), $this->getIcon());
		$action = false;
		
		// Recherche d'un champion qui peut jouer
		foreach($this->battleChampions as $battleChampion) {
			// $this->battle->addLog(new Log('battle.report.team.askChampion', array(
			// '%teamName%' => $this->team->getName(),
			// '%championName%' => $battleChampion->getChampion()->getName()), $this->ltm->get(array(
			// LogTypes::TEAM,
			// LogTypes::ASK_CHAMPION)), $this->getIcon()));
			
			$injury = $battleChampion->play($time);
			$action = $injury;
			if ($injury !== false) {
				// $this->battle->addLog(new Log('battle.report.team.championPlayed', array(
				// '%teamName%' => $this->team->getName(),
				// '%championName%' => $battleChampion->getChampion()->getName()), $this->ltm->get(array(
				// LogTypes::TEAM,
				// LogTypes::CHAMPION_PLAYED)), $this->getIcon()));
				break;
			}
			else {
				// $this->battle->addLog(new Log('battle.report.team.championCannotPlay', array(
				// '%teamName%' => $this->team->getName(),
				// '%championName%' => $battleChampion->getChampion()->getName()), $this->ltm->get(array(
				// LogTypes::TEAM,
				// LogTypes::CHAMPION_PLAYED)), $this->getIcon()));
			}
		}
		if ($action === false) {
			// $this->battle->addLog(new Log('battle.report.team.noMoreChampions', array(
			// '%teamName%' => $this->team->getName(),
			// '%time%' => $time), $this->ltm->get(array(
			// LogTypes::TEAM,
			// LogTypes::NO_MORE_CHAMPIONS)), $this->getIcon()));
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
	public function setInjury(Injury $injury) {
		// $this->battle->addLog(new Log('battle.report.team.injuryWhichChampion', array(
		// '%teamName%' => $this->team->getName()), $this->ltm->get(array(
		// LogTypes::TEAM,
		// LogTypes::ASK_CHAMPION,
		// LogTypes::INJURY)), $this->getIcon()));
		// Recherche d'un champion encore en vie
		foreach($this->battleChampions as $battleChampion) {
			if ($battleChampion->isAlive()) {
				// $this->battle->addLog(new Log('battle.report.team.injureChampion', array(
				// '%teamName%' => $this->team->getName(),
				// '%championName%' => $battleChampion->getChampion()->getName()), $this->ltm->get(array(
				// LogTypes::TEAM,
				// LogTypes::INJURY)), $this->getIcon()));
				$battleChampion->setInjury($injury);
				break;
			}
		}
	}
}