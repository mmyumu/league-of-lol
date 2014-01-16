<?php
	interface IInjury {

		/**
		 * Interface IInjury
		 *
		 * Cette interface doit être implémentée par les blessures infligées suite aux attaques.
		 */

		/**
		 * Function getNormalAmount()
		 * Doit retourner la quantité de dégâts infligée de type normal
		 *
		 * @return	float	La quantité de dégâts infligée de type normal
		 */
		public function getNormalAmount();

	}
?>