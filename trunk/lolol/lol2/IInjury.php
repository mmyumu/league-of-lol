<?php
	interface IInjury {

		/**
		 * Interface IInjury
		 *
		 * Cette interface doit �tre impl�ment�e par les blessures inflig�es suite aux attaques.
		 */

		/**
		 * Function getNormalAmount()
		 * Doit retourner la quantit� de d�g�ts inflig�e de type normal
		 *
		 * @return	float	La quantit� de d�g�ts inflig�e de type normal
		 */
		public function getNormalAmount();

	}
?>