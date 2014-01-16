<?php
	require_once 'IInjury.php';

	class Injury implements IInjury {

		/**
		 * class Injury
		 *
		 * Cette classe représente une blessure infligée suite à une attaque.
		 */

		/**
		 * Attributs membre
		 */
		private $_normalAmmount;

		/**
		 * Constructeur
		 *
		 * Initialisation des paramètres de la blessure
		 *
		 * @param	float	p_NormalAmount	Montant de dégâts normaux infligés
		 */
		function __construct($p_NormalAmount = 0) {
			$this->_normalAmmount = $p_NormalAmount;
		}

		/**
		 * Function getNormalAmount()
		 * Doit retourner la quantité de dégâts infligée de type normal
		 *
		 * @return	float	La quantité de dégâts infligée de type normal
		 */
		public function getNormalAmount() {
			return $this->_normalAmmount;
		}

	}
?>