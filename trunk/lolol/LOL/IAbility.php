<?php
	interface IAbility {

		/**
		 * Interface IAbility
		 *
		 * Cette interface doit �tre impl�ment�e par les comp�tences des Champions
		 */

		/**
		 * Function isCooldown()
		 * Doit retourner vrai si la comp�tence peut �tre utilis�e ou faux sinon
		 *
		 * @param	float	$p_time	Le moment dans la partie
		 * @return	boolean	La comp�tence peut-elle �tre utilis�e ? Vrai si oui, faux sinon
		 */
		public function isCooldown($p_time);

	}
?>