<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\TeamBundle\Entity\Team as Team;
use Symfony\Component\Translation\TranslatorInterface;

class BattleTeam {
	
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
	 * The logger of the battle.
	 *
	 * @var string
	 */
	private $battleLogger;
	
	/**
	 * The translator service
	 * 
	 * @var TranslatorInterface
	 */
	private $translator;
	
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
	public function __construct(Team $team, $attacker, $battleLogger, TranslatorInterface $translator) {
		$this->team = $team;
		$this->attacker = $attacker;
		$this->battleLogger = $battleLogger;
		$this->translator = $translator;
		
		foreach($team->getChampionsTeam() as $championTeam) {
			$this->battleChampions[$championTeam->getPosition()] = new BattleChampion($championTeam->getChampion(), $attacker, $battleLogger);
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
			if($battleChampion->isAlive()) {
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
		$this->battleLogger->log($this->translator->trans('battle.report.team.canPlay', array('%teamName%' => $this->team->getName(), '%roundNumber%' => $time)), '', false, $this->getIcon());
		$action = false;
	
		// Recherche d'un champion qui peut jouer
		foreach($this->battleChampions as $battleChampion) {
			$this->battleLogger->log($this->translator->trans('battle.report.team.askChampion', array('%teamName%' => $this->team->getName(), '%championName%' => $battleChampion->getChampion()->getName())), '', false, $this->getIcon());
				
			$injury = $battleChampion->play($time);
			$action = $injury;
			if ($injury !== false) {
				$this->battleLogger->log($this->translator->trans('battle.report.team.championPlayed', array('%teamName%' => $this->team->getName(), '%championName%' => $battleChampion->getChampion()->getName())), '', false, $this->getIcon());
				break;
			}
			else {
				$this->battleLogger->log('L\'équipe ' . $this->team->getName() . ' n\'a pas pu faire jouer son Champion ' . $battleChampion->getChampion()->getName(), '', false, $this->getIcon());
			}
		}
		if ($action === false) {
			$this->battleLogger->log('L\'équipe ' . $this->team->getName() . ' n\'a plus de Champion activable au tour ' . $time, '', false, $this->getIcon());
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
		$this->battleLogger->log('L\'équipe ' . $this->team->getName() . ' regarde à quel Champion infliger la blessure');
		// Recherche d'un champion encore en vie
		foreach($this->battleChampions as $battleChampion) {
			if ($battleChampion->isAlive()) {
				$this->battleLogger->log('L\'équipe ' . $this->team->getName() . ' inflige la blessure à ' . $battleChampion->getChampion()->getName() . ' car il est encore en vie');
				$battleChampion->setInjury($injury);
				break;
			}
		}
	}
}