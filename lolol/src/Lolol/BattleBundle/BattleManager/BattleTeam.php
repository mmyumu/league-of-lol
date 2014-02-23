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
	public function __construct(Team $team, $attacker, Battle $battle) {
		$this->team = $team;
		$this->attacker = $attacker;
		$this->battle = $battle;
		
		foreach($team->getChampionsTeam() as $championTeam) {
			$this->battleChampions[$championTeam->getPosition()] = new BattleChampion($championTeam->getChampion(), $attacker, $battle);
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
		} else {
			return BattleIcon::DEFENDER;
		}
	}
	
	/**
	 * Function hasLost()
	 * Indique si l'équipe en jeu a perdu
	 *
	 * @return boolean a-t-elle perdu ? Si oui, true, sinon, false
	 */
	public function hasLost($time) {
		// Condition de non-défaite : au moins un Champion encore en vie
		foreach($this->battleChampions as $battleChampion) {
			if ($battleChampion->isAlive()) {
				return false;
			}
		}
		
		$this->battle->addLog(new Log($time, 'battle.report.team.defeated', array(
				'%teamName%' => $this->team->getName(),
				'%championName%' => $battleChampion->getName()
		), array(
				LogType::TEAM,
				LogType::DEFEATED,
				LogType::STRONG
		), $this->getIcon()));
		return true;
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
	public function play($time) {
		// On n'a rien fait, jusqu'à preuve du contraire
		$action = false;
		
		// Recherche d'un champion qui peut jouer
		foreach($this->battleChampions as $battleChampion) {
			
			$injury = $battleChampion->play($time);
			$action = $injury;
			if ($injury !== false) {
				break;
			}
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
	public function setInjury(Injury $injury, $time) {
		// Recherche d'un champion encore en vie
		foreach($this->battleChampions as $battleChampion) {
			if ($battleChampion->isAlive()) {
				$battleChampion->setInjury($injury, $time);
				break;
			}
		}
	}
}