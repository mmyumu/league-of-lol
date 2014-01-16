<?php
	require_once 'IChampion.php';
	require_once 'Injury.php';

	class Tanky implements IChampion {

		/**
		 * class Tanky
		 *
		 * Un Champion qui tank : peu de d�g�ts, mais une grosse armure et de la vie.
		 */

		/**
		 * Attributs membre
		 */
		private $_health;
		private $_attackDamage;
		private $_armor;
		private $_name;
		private $_teamName;
		private $_attackSpeed;

		private $_lastAttackTime;

		/**
		 * Constructeur
		 *
		 * Initialisation des param�tres du Champion
		 */
		function __construct() {
			$this->_health = 500;
			$this->_attackDamage = 50;
			$this->_armor = 20;
			$this->_attackSpeed = 2;
			$this->_lastAttackTime = 0;
			$this->_name = 'Tanky';
			$this->_teamName = 'undefined';
		}

		/**
		 * Function getHealth()
		 * Doit retourner la vie par d�faut du Champion
		 *
		 * @return	float	La vie par d�faut du Champion
		 */
		public function getHealth() {
			return $this->_health;
		}

		/**
		 * Function getAttackDamage()
		 * Doit retourner les d�g�ts par d�faut du Champion
		 *
		 * @return	float	Les d�g�ts par d�faut du Champion
		 */
		public function getAttackDamage() {
			return $this->_attackDamage;
		}

		/**
		 * Function getArmor()
		 * Doit retourner l'armure par d�faut du Champion
		 *
		 * @return	float	L'armure par d�faut du Champion
		 */
		public function getArmor() {
			return $this->_armor;
		}

		/**
		 * Function getName()
		 * Doit retourner le nom du Champion
		 *
		 * @return	string	Le nom du Champion
		 */
		public function getName() {
			return $this->_name . ' (Equipe ' . $this->_teamName . ')';
		}

		/**
		 * Function setTeamName()
		 *
		 * Permet de d�finir le nom de l'�quipe � laquelle appartient le Champion
		 *
		 * @param	string	$p_teamName	Le nom de l'�quipe du Champion
		 */
		function setTeamName($p_teamName) {
			$this->_teamName = $p_teamName;
		}

		/**
		 * Function setInjury()
		 * Inflige la blessure pass�e en param�tre au Champion
		 *
		 * @param	IInjury	p_injury	La blessure � infliger
		 * @return	boolean	Le Champion est-il mort ? Vrai si la blessure le tue, faux sinon
		 */
		public function setInjury(IInjury $p_injury) {
			$this->_logger->debug('Le Champion ' . $this->getName() . ' subit une blessure de ' . $p_injury->getNormalAmount() . ' HP');
			$this->_logger->debug('Le Champion ' . $this->getName() . ' absorbe ' . $this->_armor . ' d�g�ts gr�ce � son armure');
			$this->_health -= ($p_injury->getNormalAmount() - $this->_armor);
			$this->_logger->debug('Il reste au Champion ' . $this->getName() . ' ' . $this->_health . ' HP');

			return ($this->isAlive());
		}

		/**
		 * Function isAlive()
		 * Indique si le Champion est encore en vie
		 *
		 * @return	boolean	Le Champion est-il encore en vie ? Vrai s'il est vivant, faux sinon
		 */
		public function isAlive() {
			if ($this->_health > 0) {
				$this->_logger->debug('Le Champion ' . $this->getName() . ' est encore en vie');
			}
			else {
				$this->_logger->debug('Le Champion ' . $this->getName() . ' est KO');
			}
			return ($this->_health > 0);
		}

		/**
		 * Function defaultAttack()
		 * Permet d'ex�cuter une attaque par d�faut
		 * Retourne la blessure � infliger � l'autre �quipe
		 *
		 * @param	float	$p_time	Le moment dans la partie
		 * @return	IInjury	La blessure � infliger
		 */
		public function defaultAttack($p_time = 0) {
			// Par d�faut, l'attaque est en cooldown
			$injury = false;
			$this->_logger->debug('Le Champion ' . $this->getName() . ' essaie d\'utiliser son attaque par d�faut');
			// V�rification du temps �coul� depuis la derni�re attaque de ce type
			$up = $this->_lastAttackTime + $this->_attackSpeed;
			if ($p_time >= $up) {
				// Attaque disponible
				$this->_logger->debug('Le Champion ' . $this->getName() . ' fait une attaque par d�faut pour ' . $this->_attackDamage . ' d�g�ts');
				$injury = new Injury($this->_attackDamage);
				$this->_lastAttackTime = $p_time;
			}
			else {
				// Cooldown
				$this->_logger->debug('Le Champion ' . $this->getName() . ' a son attaque par d�faut en cooldown');
			}
			return $injury;
		}

		/**
		 * Function play()
		 * Permet de faire jouer le Champion, s'il peut jouer
		 * Retourne soit une blessure � infliger, soit false s'il n'a aucun cooldown pr�t
		 *
		 * @param	float	$p_time	Le moment dans la partie
		 * @return	IInjury	La blessure � infliger, ou false sinon
		 */
		public function play($p_time = 0) {
			// On n'a rien fait, jusqu'� preuve du contraire
			$bAction = false;
			// Ici l'intelligence du joueur entre en oeuvre
			// On va prendre en compte ses choix de priorit�s pour d�terminer
			// ce que fait le Champion selon ses cooldowns
			/** pour l'instant, seule l'attaque par d�faut est utilis�e */
			if ($this->isAlive()) {
				$this->_logger->debug('Le Champion ' . $this->getName() . ' regarde s\'il peut jouer au round ' . $p_time);
				$bAction = $this->defaultAttack($p_time);
			}
			return $bAction;
		}

		private $_logger;
		/**
		 * Function setLogger()
		 *
		 * Permet de d�finir le LOGGER
		 *
		 * @param	Log	$p_logger	Le Logger
		 */
		function setLogger(Log $p_logger) {
			$this->_logger = $p_logger;
		}

	}
?>