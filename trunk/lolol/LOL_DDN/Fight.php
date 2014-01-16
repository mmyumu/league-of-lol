<?php
	require_once 'Team.php';

	class Fight {

		/**
		 * class Fight
		 *
		 * Permet de simuler un combat entre deux �quipes
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
		 * Initialisation des param�tres du combat
		 */
		function __construct(ITeam $p_teamA, ITeam $p_teamB) {
			$this->_teamA = $p_teamA;
			$this->_teamB = $p_teamB;
		}

		/**
		 * M�thode principale de simulation du combat
		 * Chaque �quipe joue � tour de r�le jusqu'� ce que tout le monde ait
		 * pu agir pour le temps donn�
		 */
		function computeFight() {
			$this->_time = 0;
			$this->_logger->debug('On v�rifie si chacune des deux �quipes a bien au moins un Champion encore debout');
			// Le combat continue jusqu'� ce qu'une �quipe ait perdu
			while (!($this->_teamA->hasLost() || $this->_teamB->hasLost())) {
				$this->_logger->debug('D�but round ' . $this->_time);
				$bActionA = true;
				$bActionB = true;
				// On continue d'agir tant qu'on a des actions � faire
				while(($bActionA !== false) || ($bActionB !== false)) {
					// Chaque �quipe joue en m�me temps
					$bActionA = $this->_teamA->play($this->_time);
					$bActionB = $this->_teamB->play($this->_time);
					// On r�sout les blessures
					if (false !== $bActionB) {
						$this->_teamA->setInjury($bActionB);
					}
					if (false !== $bActionA) {
						$this->_teamB->setInjury($bActionA);
					}
				}
				// Tout le monde a donc jou� simultann�ment, sans v�rification d'une victoire interm�diaire
				// C'est seulement apr�s que chacun ait fait son action du moment qu'on avance le temps
				$this->_logger->debug('Fin round ' . $this->_time);
				$this->_logger->debug('On v�rifie si chacune des deux �quipes a bien au moins un Champion encore debout');
				$this->_time ++;
			}
		}

		/**
		 * M�thode qui affiche le r�sultat de la simulation de combat entre les deux �quipes
		 */
		function displayResult() {
			// Qui a gagn� ?
			$aLost = $this->_teamA->hasLost();
			$bLost = $this->_teamB->hasLost();
			if ($aLost) {
				$this->_logger->debug('L\'�quipe ' . $this->_teamA->getName() . ' a perdu !');
			}
			else {
				$this->_logger->debug('L\'�quipe ' . $this->_teamA->getName() . ' a gagn� !');
			}
			if ($bLost) {
				$this->_logger->debug('L\'�quipe ' . $this->_teamB->getName() . ' a perdu !');
			}
			else {
				$this->_logger->debug('L\'�quipe ' . $this->_teamB->getName() . ' a gagn� !');
			}
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