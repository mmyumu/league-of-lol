<?php

namespace Lolol\BattleBundle\BattleManager;

class Injury {
	
	/**
	 * class Injury
	 *
	 * Cette classe représente une blessure infligée suite à une attaque.
	 */
	
	/**
	 * Attributs membre
	 */
	private $normalAmount;
	
	/**
	 * Constructeur
	 *
	 * Initialisation des paramètres de la blessure
	 *
	 * @param
	 *        	float	p_NormalAmount	Montant de dégâts normaux infligés
	 */
	function __construct($normalAmount = 0) {
		$this->normalAmount = $normalAmount;
	}
	
	/**
	 * Function getNormalAmount()
	 * Doit retourner la quantité de dégâts infligée de type normal
	 *
	 * @return float quantité de dégâts infligée de type normal
	 */
	public function getNormalAmount() {
		return $this->normalAmount;
	}
}
?>