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
	 * Permet d'exécuter une attaque par défaut
	 * Retourne la blessure à infliger à l'autre équipe
	 *
	 * @param float $p_time
	 *        	dans la partie
	 * @return IInjury blessure à infliger
	 */
	public function defaultAttack(Champion $champion, $time = 0) {
		// Par défaut, l'attaque est en cooldown
		$injury = false;
		$this->battleLogger->log('Le Champion ' . $champion->getName() . ' essaie d\'utiliser son attaque par défaut au round ' . $time, '', false, $champion->getIcon());
		// Vérification du temps écoulé depuis la dernière attaque de ce type
		$up = $champion->getLastAttackTime() + (1 / $champion->getAttackSpeed()) * 2;
		if ($time >= $up) {
			// Attaque disponible
			$this->battleLogger->log('Le Champion ' . $champion->getName() . ' fait une attaque par défaut pour ' . $champion->getAttackDamage() . ' dégâts', '', false, $champion->getIcon());
			$injury = new Injury($champion->getAttackDamage());
			$champion->setLastAttackTime($time);
			// $this->lastAttackTime = $time;
		}
		else {
			// Cooldown
			$this->battleLogger->log('Le Champion ' . $champion->getName() . ' a son attaque par défaut en cooldown jusqu\'au round ' . ceil($up), '', false, $champion->getIcon());
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
	public function setInjury(Champion $champion, Injury $injury) {
		$this->battleLogger->log('Le Champion ' . $champion->getName() . ' subit une blessure de ' . $injury->getNormalAmount() . ' HP', '', false, $champion->getIcon());
		$this->battleLogger->log('Le Champion ' . $champion->getName() . ' absorbe ' . $champion->getArmor() . ' dégâts grâce à son armure', '', false, $champion->getIcon());
		$champion->setCurrentHealth($champion->getCurrentHealth() - ($injury->getNormalAmount() - $champion->getArmor()));
		$this->battleLogger->log('Il reste au Champion ' . $champion->getName() . ' ' . $champion->getCurrentHealth() . ' HP', '', false, $champion->getIcon());
	}
}