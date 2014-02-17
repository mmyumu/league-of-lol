<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\BattleBundle\Entity\Battle as Battle;
use Lolol\BattleBundle\Entity\Log as Log;
use Lolol\AppBundle\Entity\Champion as Champion;
use Symfony\Component\Translation\TranslatorInterface;

class BattleChampion extends Champion {
	
	/**
	 * The champion engaged in the battle
	 *
	 * @var Champion
	 */
	private $champion;
	
	/**
	 * Current health during battle
	 *
	 * @var float
	 */
	private $currentHealth;
	
	/**
	 * Time of last attack
	 *
	 * @var float
	 */
	private $lastAttackTime;
	
	/**
	 * The battle
	 *
	 * @var Battle
	 */
	private $battle;
	
	/**
	 * Initialize the champion for the battle
	 *
	 * @param Champion $champion        	
	 * @param boolean $attacker        	
	 * @param string $logs        	
	 */
	public function __construct(Champion $champion, $attacker, Battle $battle) {
		$this->champion = $champion;
		$this->attacker = $attacker;
		$this->battle = $battle;
		$lastAttackTime = 0;
		$this->currentHealth = $champion->getHealth();
	}
	public function getChampion() {
		return $this->champion;
	}
	public function setCurrentHealth($currentHealth) {
		$this->currentHealth = $currentHealth;
	}
	public function getCurrentHealth() {
		return $this->currentHealth;
	}
	public function setLastAttackTime($lastAttackTime) {
		$this->lastAttackTime = $lastAttackTime;
	}
	public function getLastAttackTime() {
		return $this->lastAttackTime;
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
	 * Function isAlive()
	 * Indique si le Champion est encore en vie
	 *
	 * @return boolean Champion est-il encore en vie ? Vrai s'il est vivant, faux sinon
	 */
	public function isAlive() {
		if ($this->getCurrentHealth() > 0) {
			// $this->battle->addLog(new Log('battle.report.champion.alive', array(
			// '%championName%' => $this->getChampion()->getName()), $this->ltm->get(array(
			// LogType::CHAMPION,
			// LogType::ALIVE)), $this->getIcon()));
		} else {
			// $this->battle->addLog(new Log('battle.report.champion.ko', array(
			// '%championName%' => $this->getChampion()->getName()), $this->ltm->get(array(
			// LogType::CHAMPION,
			// LogType::KO)), $this->getIcon()));
		}
		return ($this->getCurrentHealth() > 0);
	}
	
	/**
	 * Function play()
	 * Permet de faire jouer le Champion, s'il peut jouer
	 * Retourne soit une blessure à infliger, soit false s'il n'a aucun cooldown prêt
	 *
	 * @param float $p_time
	 *        	dans la partie
	 * @return IInjury blessure à infliger, ou false sinon
	 */
	public function play($time = 0) {
		// On n'a rien fait, jusqu'à preuve du contraire
		$action = false;
		// Ici l'intelligence du joueur entre en oeuvre
		// On va prendre en compte ses choix de priorités pour déterminer
		// ce que fait le Champion selon ses cooldowns
		/**
		 * pour l'instant, seule l'attaque par défaut est utilisée
		 */
		if ($this->isAlive()) {
			$action = $this->defaultAttack($time);
		}
		return $action;
	}
	
	/**
	 * Function defaultAttack()
	 * Permet d'exécuter une attaque par défaut
	 * Retourne la blessure à infliger à l'autre équipe
	 *
	 * @param float $p_time
	 *        	dans la partie
	 * @return IInjury blessure à infliger
	 */
	public function defaultAttack($time = 0) {
		// Par défaut, l'attaque est en cooldown
		$injury = false;
		// $this->battle->addLog(new Log('battle.report.champion.tryDefaultAttack', array(
		// '%championName%' => $this->getChampion()->getName()), $this->ltm->get(array(
		// LogType::CHAMPION,
		// LogType::TRY_DEFAULT_ATTACK)), $this->getIcon()));
		
		// Vérification du temps écoulé depuis la dernière attaque de ce type
		$up = $this->getLastAttackTime() + (1 / $this->champion->getAttackSpeed()) * 2;
		if ($time >= $up) {
			// Attaque disponible
			$this->battle->addLog(new Log('battle.report.champion.defaultAttack', array(
					'%championName%' => $this->getChampion()->getName(),
					'%attackDamage%' => $this->champion->getAttackDamage()
			), array(
					LogType::CHAMPION,
					LogType::DEFAULT_ATTACK
			), $this->getIcon()));
			
			$injury = new Injury($this->champion->getAttackDamage());
			$this->setLastAttackTime($time);
			// $this->lastAttackTime = $time;
		} else {
			// Cooldown
			// $this->battle->addLog(new Log('battle.report.champion.defaultAttackCooldown', array(
			// '%championName%' => $this->getChampion()->getName(),
			// '%time%' => ceil($up)), $this->ltm->get(array(
			// LogType::CHAMPION,
			// LogType::DEFAULT_ATTACK_COOLDOWN)), $this->getIcon()));
		}
		return $injury;
	}
	
	/**
	 * Function setInjury()
	 * Inflige la blessure passée en paramètre au Champion
	 *
	 * @param
	 *        	IInjury	p_injury	La blessure à infliger
	 */
	public function setInjury(Injury $injury) {
		$this->battle->addLog(new Log('battle.report.champion.injured', array(
				'%championName%' => $this->getChampion()->getName(),
				'%damage%' => $injury->getNormalAmount()
		), array(
				LogType::CHAMPION,
				LogType::INJURED
		), $this->getIcon()));
		$this->battle->addLog(new Log('battle.report.champion.armorAbsorption', array(
				'%championName%' => $this->getChampion()->getName(),
				'%armor%' => $this->champion->getArmor()
		), array(
				LogType::CHAMPION,
				LogType::ARMOR_ABSORPTION
		), $this->getIcon()));
		$this->setCurrentHealth($this->getCurrentHealth() - ($injury->getNormalAmount() - $this->champion->getArmor()));
		
		$this->battle->addLog(new Log('battle.report.champion.hp', array(
				'%championName%' => $this->getChampion()->getName(),
				'%currentHP%' => $this->getCurrentHealth()
		), array(
				LogType::CHAMPION,
				LogType::HEALTH
		), $this->getIcon()));
	}
}