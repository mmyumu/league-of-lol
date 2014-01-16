<?php
	require_once 'IInjury.php';

	class Injury implements IInjury {

		/**
		 * class Injury
		 *
		 * Cette classe repr�sente une blessure inflig�e suite � une attaque.
		 */

		/**
		 * Attributs membre
		 */
		private $_normalAmmount;

		/**
		 * Constructeur
		 *
		 * Initialisation des param�tres de la blessure
		 *
		 * @param	float	p_NormalAmount	Montant de d�g�ts normaux inflig�s
		 */
		function __construct($p_NormalAmount = 0) {
			$this->_normalAmmount = $p_NormalAmount;
		}

		/**
		 * Function getNormalAmount()
		 * Doit retourner la quantit� de d�g�ts inflig�e de type normal
		 *
		 * @return	float	La quantit� de d�g�ts inflig�e de type normal
		 */
		public function getNormalAmount() {
			return $this->_normalAmmount;
		}

	}
?>