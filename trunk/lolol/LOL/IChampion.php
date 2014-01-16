<?php
	interface IChampion {

		/**
		 * Interface IChampion
		 *
		 * Cette interface doit être implémentée par chacun des Champions.
		 */

		/**
		 * Function getHealth()
		 * Doit retourner la vie par défaut du Champion
		 *
		 * @return	float	La vie par défaut du Champion
		 */
		public function getHealth();

		/**
		 * Function getAttackDamage()
		 * Doit retourner les dégâts par défaut du Champion
		 *
		 * @return	float	Les dégâts par défaut du Champion
		 */
		public function getAttackDamage();

		/**
		 * Function getArmor()
		 * Doit retourner l'armure par défaut du Champion
		 *
		 * @return	float	L'armure par défaut du Champion
		 */
		public function getArmor();

		/**
		 * Function setInjury()
		 * Inflige la blessure passée en paramètre au Champion
		 *
		 * @param	IInjury	p_injury	La blessure à infliger
		 */
		public function setInjury(IInjury $p_injury);

		/**
		 * Function isAlive()
		 * Indique si le Champion est encore en vie
		 *
		 * @return	boolean	Le Champion est-il encore en vie ? Vrai s'il est vivant, faux sinon
		 */
		public function isAlive();

		/**
		 * Function play()
		 * Permet de faire jouer le Champion, s'il peut jouer
		 * Retourne soit une blessure à infliger, soit false s'il n'a aucun cooldown prêt
		 *
		 * @param	float	$p_time	Le moment dans la partie
		 * @return	IInjury	La blessure à infliger, ou false sinon
		 */
		public function play($p_time = 0);

		/**
		 * Function defaultAttack()
		 * Permet d'exécuter une attaque par défaut
		 * Retourne la blessure à infliger à l'autre équipe
		 *
		 * @param	float	$p_time	Le moment dans la partie
		 * @return	IInjury	La blessure à infliger
		 */
		public function defaultAttack($p_time = 0);

		/**
		 * Function setTeamName()
		 *
		 * Permet de définir le nom de l'équipe à laquelle appartient le Champion
		 *
		 * @param	string	$p_teamName	Le nom de l'équipe du Champion
		 */
		function setTeamName($p_teamName);

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