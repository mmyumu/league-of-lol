<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\AppBundle\Entity\Champion as Champion;

class BattleChampion extends Champion {
	
	/**
	 * The champion engaged in the battle
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
	 * The logger of the battle.
	 * 
	 * @var string
	 */
	private $battleLogger;
	
	/**
	 * Initialize the champion for the battle
	 * @param Champion $champion
	 * @param boolean $attacker
	 * @param string $logs
	 */
	public function __construct(Champion $champion, $attacker, $battleLogger) {
		$this->champion = $champion;
		$this->attacker = $attacker;
		$this->battleLogger = $battleLogger;
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
		}
		else {
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
			$this->battleLogger->log('Le Champion ' . $this->champion->getName() . ' est encore en vie', 'text-success', false, $this->getIcon());
		}
		else {
			$this->battleLogger->log('Le Champion ' . $this->champion->getName() . ' est KO', 'text-danger', false, $this->getIcon());
		}
		return ($this->getCurrentHealth() > 0);
	}
	
	/**
	 * Function play()
	 * Permet de faire jouer le Champion, s'il peut jouer
	 * Retourne soit une blessure � infliger, soit false s'il n'a aucun cooldown pr�t
	 *
	 * @param float $p_time
	 *        	dans la partie
	 * @return IInjury blessure � infliger, ou false sinon
	 */
	public function play($time = 0) {
		// On n'a rien fait, jusqu'� preuve du contraire
		$action = false;
		// Ici l'intelligence du joueur entre en oeuvre
		// On va prendre en compte ses choix de priorit�s pour d�terminer
		// ce que fait le Champion selon ses cooldowns
		/**
		 * pour l'instant, seule l'attaque par d�faut est utilis�e
		 */
		if ($this->isAlive()) {
			$action = $this->defaultAttack($time);
		}
		return $action;
	}
	
	/**
	 * Function defaultAttack()
	 * Permet d'ex�cuter une attaque par d�faut
	 * Retourne la blessure � infliger � l'autre �quipe
	 *
	 * @param float $p_time
	 *        	dans la partie
	 * @return IInjury blessure � infliger
	 */
	public function defaultAttack($time = 0) {
		// Par d�faut, l'attaque est en cooldown
		$injury = false;
		$this->battleLogger->log('Le Champion ' . $this->champion->getName() . ' essaie d\'utiliser son attaque par d�faut au round ' . $time, '', false, $this->getIcon());
		// V�rification du temps �coul� depuis la derni�re attaque de ce type
		$up = $this->getLastAttackTime() + (1 / $this->champion->getAttackSpeed()) * 2;
		if ($time >= $up) {
			// Attaque disponible
			$this->battleLogger->log('Le Champion ' . $this->champion->getName() . ' fait une attaque par d�faut pour ' . $this->champion->getAttackDamage() . ' d�g�ts', '', false, $this->getIcon());
			$injury = new Injury($this->champion->getAttackDamage());
			$this->setLastAttackTime($time);
			// $this->lastAttackTime = $time;
		}
		else {
			// Cooldown
			$this->battleLogger->log('Le Champion ' . $this->champion->getName() . ' a son attaque par d�faut en cooldown jusqu\'au round ' . ceil($up), '', false, $this->getIcon());
		}
		return $injury;
	}
	
	/**
	 * Function setInjury()
	 * Inflige la blessure pass�e en param�tre au Champion
	 *
	 * @param
	 *        	IInjury	p_injury	La blessure � infliger
	 */
	public function setInjury(Injury $injury) {
		$this->battleLogger->log('Le Champion ' . $this->champion->getName() . ' subit une blessure de ' . $injury->getNormalAmount() . ' HP', '', false, $this->getIcon());
		$this->battleLogger->log('Le Champion ' . $this->champion->getName() . ' absorbe ' . $this->champion->getArmor() . ' d�g�ts gr�ce � son armure', '', false, $this->getIcon());
		$this->setCurrentHealth($this->getCurrentHealth() - ($injury->getNormalAmount() - $this->champion->getArmor()));
		$this->battleLogger->log('Il reste au Champion ' . $this->champion->getName() . ' ' . $this->getCurrentHealth() . ' HP', '', false, $this->getIcon());
	}
}