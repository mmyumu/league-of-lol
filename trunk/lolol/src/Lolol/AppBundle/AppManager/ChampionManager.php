<?php

namespace Lolol\AppBundle\AppManager;

use Lolol\BattleBundle\BattleManager\BattleLogger as BattleLogger;
use Lolol\AppBundle\Entity\Champion as Champion;
use Lolol\BattleBundle\BattleManager\Injury as Injury;

class ChampionManager {
	private $battleLogger;
	public function __construct(BattleLogger $battleLogger) {
		$this->battleLogger = $battleLogger;
	}
	public function prepare(BattleChampion $champion, $attacker) {
		$champion->setAttacker($attacker);
		$champion->setCurrentHealth($champion->getHealth());
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
	public function defaultAttack(Champion $champion, $time = 0) {
		// Par d�faut, l'attaque est en cooldown
		$injury = false;
		$this->battleLogger->log('Le Champion ' . $champion->getName() . ' essaie d\'utiliser son attaque par d�faut au round ' . $time, '', false, $champion->getIcon());
		// V�rification du temps �coul� depuis la derni�re attaque de ce type
		$up = $champion->getLastAttackTime() + (1 / $champion->getAttackSpeed()) * 2;
		if ($time >= $up) {
			// Attaque disponible
			$this->battleLogger->log('Le Champion ' . $champion->getName() . ' fait une attaque par d�faut pour ' . $champion->getAttackDamage() . ' d�g�ts', '', false, $champion->getIcon());
			$injury = new Injury($champion->getAttackDamage());
			$champion->setLastAttackTime($time);
			// $this->lastAttackTime = $time;
		}
		else {
			// Cooldown
			$this->battleLogger->log('Le Champion ' . $champion->getName() . ' a son attaque par d�faut en cooldown jusqu\'au round ' . ceil($up), '', false, $champion->getIcon());
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
	public function setInjury(Champion $champion, Injury $injury) {
		$this->battleLogger->log('Le Champion ' . $champion->getName() . ' subit une blessure de ' . $injury->getNormalAmount() . ' HP', '', false, $champion->getIcon());
		$this->battleLogger->log('Le Champion ' . $champion->getName() . ' absorbe ' . $champion->getArmor() . ' d�g�ts gr�ce � son armure', '', false, $champion->getIcon());
		$champion->setCurrentHealth($champion->getCurrentHealth() - ($injury->getNormalAmount() - $champion->getArmor()));
		$this->battleLogger->log('Il reste au Champion ' . $champion->getName() . ' ' . $champion->getCurrentHealth() . ' HP', '', false, $champion->getIcon());
	}
}