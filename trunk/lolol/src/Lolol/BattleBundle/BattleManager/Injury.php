<?php

namespace Lolol\BattleBundle\BattleManager;

class Injury {
	
	/**
	 * class Injury
	 *
	 * Cette classe repr�sente une blessure inflig�e suite � une attaque.
	 */
	
	/**
	 * Attributs membre
	 */
	private $normalAmount;
	
	/**
	 * Constructeur
	 *
	 * Initialisation des param�tres de la blessure
	 *
	 * @param
	 *        	float	p_NormalAmount	Montant de d�g�ts normaux inflig�s
	 */
	function __construct($normalAmount = 0) {
		$this->normalAmount = $normalAmount;
	}
	
	/**
	 * Function getNormalAmount()
	 * Doit retourner la quantit� de d�g�ts inflig�e de type normal
	 *
	 * @return float quantit� de d�g�ts inflig�e de type normal
	 */
	public function getNormalAmount() {
		return $this->normalAmount;
	}
}
?>