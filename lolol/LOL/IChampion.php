<?php
	interface IChampion {

		/**
		 * Interface IChampion
		 *
		 * Cette interface doit �tre impl�ment�e par chacun des Champions.
		 */

		/**
		 * Function getHealth()
		 * Doit retourner la vie par d�faut du Champion
		 *
		 * @return	float	La vie par d�faut du Champion
		 */
		public function getHealth();

		/**
		 * Function getAttackDamage()
		 * Doit retourner les d�g�ts par d�faut du Champion
		 *
		 * @return	float	Les d�g�ts par d�faut du Champion
		 */
		public function getAttackDamage();

		/**
		 * Function getArmor()
		 * Doit retourner l'armure par d�faut du Champion
		 *
		 * @return	float	L'armure par d�faut du Champion
		 */
		public function getArmor();

		/**
		 * Function setInjury()
		 * Inflige la blessure pass�e en param�tre au Champion
		 *
		 * @param	IInjury	p_injury	La blessure � infliger
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
		 * Retourne soit une blessure � infliger, soit false s'il n'a aucun cooldown pr�t
		 *
		 * @param	float	$p_time	Le moment dans la partie
		 * @return	IInjury	La blessure � infliger, ou false sinon
		 */
		public function play($p_time = 0);

		/**
		 * Function defaultAttack()
		 * Permet d'ex�cuter une attaque par d�faut
		 * Retourne la blessure � infliger � l'autre �quipe
		 *
		 * @param	float	$p_time	Le moment dans la partie
		 * @return	IInjury	La blessure � infliger
		 */
		public function defaultAttack($p_time = 0);

		/**
		 * Function setTeamName()
		 *
		 * Permet de d�finir le nom de l'�quipe � laquelle appartient le Champion
		 *
		 * @param	string	$p_teamName	Le nom de l'�quipe du Champion
		 */
		function setTeamName($p_teamName);

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