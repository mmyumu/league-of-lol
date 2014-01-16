<?php
	interface IAbility {

		/**
		 * Interface IAbility
		 *
		 * Cette interface doit être implémentée par les compétences des Champions
		 */

		/**
		 * Function isCooldown()
		 * Doit retourner vrai si la compétence peut être utilisée ou faux sinon
		 *
		 * @param	float	$p_time	Le moment dans la partie
		 * @return	boolean	La compétence peut-elle être utilisée ? Vrai si oui, faux sinon
		 */
		public function isCooldown($p_time);

	}
?>