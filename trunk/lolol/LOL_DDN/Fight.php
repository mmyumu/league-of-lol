<?php
	require_once 'Team.php';

	class Fight {

		/**
		 * class Fight
		 *
		 * Permet de simuler un combat entre deux équipes
		 */

		/**
		 * Attributs membre
		 */
		private $_teamA;
		private $_teamB;
		private $_time;

		/**
		 * Constructeur
		 *
		 * Initialisation des paramètres du combat
		 */
		function __construct(ITeam $p_teamA, ITeam $p_teamB) {
			$this->_teamA = $p_teamA;
			$this->_teamB = $p_teamB;
		}

		/**
		 * Méthode principale de simulation du combat
		 * Chaque équipe joue à tour de rôle jusqu'à ce que tout le monde ait
		 * pu agir pour le temps donné
		 */
		function computeFight() {
			$this->_time = 0;
			$this->_logger->debug('On vérifie si chacune des deux équipes a bien au moins un Champion encore debout');
			// Le combat continue jusqu'à ce qu'une équipe ait perdu
			while (!($this->_teamA->hasLost() || $this->_teamB->hasLost())) {
				$this->_logger->debug('Début round ' . $this->_time);
				$bActionA = true;
				$bActionB = true;
				// On continue d'agir tant qu'on a des actions à faire
				while(($bActionA !== false) || ($bActionB !== false)) {
					// Chaque équipe joue en même temps
					$bActionA = $this->_teamA->play($this->_time);
					$bActionB = $this->_teamB->play($this->_time);
					// On résout les blessures
					if (false !== $bActionB) {
						$this->_teamA->setInjury($bActionB);
					}
					if (false !== $bActionA) {
						$this->_teamB->setInjury($bActionA);
					}
				}
				// Tout le monde a donc joué simultannément, sans vérification d'une victoire intermédiaire
				// C'est seulement après que chacun ait fait son action du moment qu'on avance le temps
				$this->_logger->debug('Fin round ' . $this->_time);
				$this->_logger->debug('On vérifie si chacune des deux équipes a bien au moins un Champion encore debout');
				$this->_time ++;
			}
		}

		/**
		 * Méthode qui affiche le résultat de la simulation de combat entre les deux équipes
		 */
		function displayResult() {
			// Qui a gagné ?
			$aLost = $this->_teamA->hasLost();
			$bLost = $this->_teamB->hasLost();
			if ($aLost) {
				$this->_logger->debug('L\'équipe ' . $this->_teamA->getName() . ' a perdu !');
			}
			else {
				$this->_logger->debug('L\'équipe ' . $this->_teamA->getName() . ' a gagné !');
			}
			if ($bLost) {
				$this->_logger->debug('L\'équipe ' . $this->_teamB->getName() . ' a perdu !');
			}
			else {
				$this->_logger->debug('L\'équipe ' . $this->_teamB->getName() . ' a gagné !');
			}
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