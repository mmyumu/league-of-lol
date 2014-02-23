<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\BattleBundle\Entity\Battle as Battle;
use Lolol\BattleBundle\Entity\Log as Log;
use Lolol\AppBundle\Entity\Champion as Champion;
use Symfony\Component\Translation\TranslatorInterface;

class BattleChampion extends Champion {
	
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
		parent::copyFrom($champion);
		$this->attacker = $attacker;
		$this->battle = $battle;
		$this->lastAttackTime = 0;
		$this->currentHealth = $champion->getHealth();
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
	public function play($time) {
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
	public function defaultAttack($time) {
		$injury = false;
		
		// Atatck time calculated from the attack frequency (attack speed)
		$attackTime = 1 / $this->getAttackSpeed();
		
		// Time between now and the last attack
		$lastAttackDelay = $time - $this->getLastAttackTime(); 
		
		// If the delay is greater
		if ($lastAttackDelay >= $attackTime) {
			// Attaque disponible
			$this->battle->addLog(new Log($time, 'battle.report.champion.defaultAttack', array(
					'%championName%' => $this->getName(),
					'%attackDamage%' => $this->getAttackDamage()
			), array(
					LogType::CHAMPION,
					LogType::DEFAULT_ATTACK
			), $this->getIcon()));
			
			$injury = new Injury($this->getAttackDamage());
			$this->setLastAttackTime($time);
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
	public function setInjury(Injury $injury, $time) {
		$finalDamage = $injury->getNormalAmount() - $this->getArmor();
		
		$this->battle->addLog(new Log($time, 'battle.report.champion.injured', array(
				'%championName%' => $this->getName(),
				'%damage%' => $finalDamage,
				'%armor%' => $this->getArmor()
		), array(
				LogType::CHAMPION,
				LogType::INJURED
		), $this->getIcon()));
		
		$this->setCurrentHealth($this->getCurrentHealth() - $finalDamage);
		
		$this->battle->addLog(new Log($time, 'battle.report.champion.hp', array(
				'%championName%' => $this->getName(),
				'%currentHP%' => $this->getCurrentHealth(),
				'%totalHP%' => $this->getHealth(),
		), array(
				LogType::CHAMPION,
				LogType::HEALTH
		), $this->getIcon()));
	}
}