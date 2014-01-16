<?php
	require_once 'ITeam.php';

	class Team implements ITeam {

		/**
		 * class Team
		 *
		 * Cette classe permet de gérer les équipes de Champions
		 */

		/**
		 * Attributs membre
		 */
		private $_aChampions;
		private $_name;

		/**
		 * Constructeur
		 *
		 * Initialisation des paramètres de l'équipe
		 */
		function __construct($p_name = 'A') {
			$this->_aChampions = array();
			$this->_name = $p_name;
		}

		/**
		 * Function getName()
		 * Doit retourner le nom du Champion
		 *
		 * @return	string	Le nom du Champion
		 */
		public function getName() {
			return $this->_name;
		}

		/**
		 * Function addChampion()
		 *
		 * Permet d'ajouter un Champion à l'équipe
		 * @param	IChampion	$p_champion	Le Champion à ajouter à l'équipe
		 * @return	int		Renvoie le nombre total de Champions dans l'équipe
		 */
		function addChampion(IChampion $p_champion = null) {
			if ($p_champion != null) {
				$p_champion->setTeamName($this->_name);
				$p_champion->setLogger($this->_logger);
				$this->_aChampions[] = $p_champion;
			}
			return count($this->_aChampions);
		}

		/**
		 * Function play()
		 * Fait faire une action minimale de l'équipe à un temps donné
		 * Chaque Champion peut intervenir, mais un seul le fait
		 * Retourne soit une blessure à infliger, soit false s'il n'y a aucun cooldown prêt
		 *
		 * @param	float	$p_time	Le moment dans la partie
		 * @return	IInjury	La blessure à infliger, ou false sinon
		 */
		public function play($p_time = 0) {
			// On n'a rien fait, jusqu'à preuve du contraire
			$this->_logger->debug('L\'équipe ' . $this->_name . ' regarde qui peut jouer au round ' . $p_time);
			$bAction = false;
			// Recherche d'un champion qui peut jouer
			foreach($this->_aChampions as $champion) {
				$this->_logger->debug('L\'équipe ' . $this->_name . ' demande au Champion ' . $champion->getName());
				$injury = $champion->play($p_time);
				$bAction = $injury;
				if ($injury !== false) {
					$this->_logger->debug('L\'équipe ' . $this->_name . ' a fait jouer son Champion ' . $champion->getName());
					break;
				}
				else {
					$this->_logger->debug('L\'équipe ' . $this->_name . ' n\'a pas pu faire jouer son Champion ' . $champion->getName());
				}
			}
			if ($bAction === false) {
				$this->_logger->debug('L\'équipe ' . $this->_name . ' n\'a plus de Champion activable au tour ' . $p_time);
			}
			return $bAction;
		}

		/**
		 * Function setInjury()
		 * Inflige la blessure passée en paramètre à un Champion de l'équipe
		 *
		 * @param	IInjury	p_injury	La blessure à infliger
		 */
		public function setInjury(IInjury $p_injury) {
			$this->_logger->debug('L\'équipe ' . $this->_name . ' regarde à quel Champion infliger la blessure');
			// Recherche d'un champion encore en vie
			foreach($this->_aChampions as $champion) {
				if ($champion->isAlive()) {
					$this->_logger->debug('L\'équipe ' . $this->_name . ' inflige la blessure à ' . $champion->getName() . ' car il est encore en vie');
					$champion->setInjury($p_injury);
					break;
				}
			}
		}

		/**
		 * Function hasLost()
		 * Indique si l'équipe en jeu a perdu
		 *
		 * @return	boolean	L'équipe a-t-elle perdu ? Si oui, true, sinon, false
		 */
		public function hasLost() {
			// On a perdu, jusqu'à preuve du contraire
			$bLost = true;
			// Condition de non-défaite : au moins un Champion encore en vie
			foreach($this->_aChampions as $champion) {
				if ($champion->isAlive()) {
					$bLost = false;
					break;
				}
			}
			return $bLost;
		}

		private $_logger;
		/**
		 * Function setLogger()
		 *
		 * Permet de définir le LOGGER
		 *
		 * @param	Log	$p_logger	Le Logger
		 */
		function setLogger(Log $p_logger) {
			$this->_logger = $p_logger;
		}

	}
?>