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
	public function prepare(Champion $champion, $attacker) {
		$champion->setAttacker($attacker);
		$champion->setCurrentHealth($champion->getHealth());
	}
	
	/**
	 * Function isAlive()
	 * Indique si le Champion est encore en vie
	 *
	 * @return boolean Champion est-il encore en vie ? Vrai s'il est vivant, faux sinon
	 */
	public function isAlive(Champion $champion) {
		if ($champion->getCurrentHealth() > 0) {
			$this->battleLogger->log('Le Champion ' . $champion->getName() . ' est encore en vie', '', false, $champion->getIcon());
		}
		else {
			$this->battleLogger->log('Le Champion ' . $champion->getName() . ' est KO', '', false, $champion->getIcon());
		}
		return ($champion->getCurrentHealth() > 0);
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
	public function play(Champion $champion, $time = 0) {
		// On n'a rien fait, jusqu'à preuve du contraire
		$action = false;
		// Ici l'intelligence du joueur entre en oeuvre
		// On va prendre en compte ses choix de priorités pour déterminer
		// ce que fait le Champion selon ses cooldowns
		/**
		 * pour l'instant, seule l'attaque par défaut est utilisée
		 */
		if ($this->isAlive($champion)) {
			$action = $this->defaultAttack($champion, $time);
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