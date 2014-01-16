<?php
	interface ITeam {

		/**
		 * Interface ITeam
		 *
		 * Cette interface doit �tre impl�ment�e par les �quipes de Champions
		 */

		/**
		 * Function play()
		 * Fait faire une action minimale de l'�quipe � un temps donn�
		 * Chaque Champion peut intervenir, mais un seul le fait
		 * Retourne soit une blessure � infliger, soit false s'il n'y a aucun cooldown pr�t
		 *
		 * @param	float	$p_time	Le moment dans la partie
		 * @return	IInjury	La blessure � infliger, ou false sinon
		 */
		public function play($p_time = 0);

		/**
		 * Function hasLost()
		 * Indique si l'�quipe en jeu a perdu
		 *
		 * @return	boolean	L'�quipe a-t-elle perdu ? Si oui, true, sinon, false
		 */
		public function hasLost();

		/**
		 * Function setLogger()
		 *
		 * Permet de d�finir le LOGGER
		 *
		 * @param	Log	$p_logger	Le Logger
		 */
		function setLogger(Log $p_logger);

	}
?>