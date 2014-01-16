<?php
	interface ITeam {

		/**
		 * Interface ITeam
		 *
		 * Cette interface doit être implémentée par les équipes de Champions
		 */

		/**
		 * Function play()
		 * Fait faire une action minimale de l'équipe à un temps donné
		 * Chaque Champion peut intervenir, mais un seul le fait
		 * Retourne soit une blessure à infliger, soit false s'il n'y a aucun cooldown prêt
		 *
		 * @param	float	$p_time	Le moment dans la partie
		 * @return	IInjury	La blessure à infliger, ou false sinon
		 */
		public function play($p_time = 0);

		/**
		 * Function hasLost()
		 * Indique si l'équipe en jeu a perdu
		 *
		 * @return	boolean	L'équipe a-t-elle perdu ? Si oui, true, sinon, false
		 */
		public function hasLost();

		/**
		 * Function setLogger()
		 *
		 * Permet de définir le LOGGER
		 *
		 * @param	Log	$p_logger	Le Logger
		 */
		function setLogger(Log $p_logger);

	}
?>