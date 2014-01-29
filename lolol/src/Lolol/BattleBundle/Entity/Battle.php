<?php

namespace Lolol\BattleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lolol\TeamBundle\Entity\Team as Team;

/**
 * @ORM\Entity(repositoryClass="Lolol\BattleBundle\Entity\BattleRepository")
 */
class Battle {
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Lolol\TeamBundle\Entity\Team")
	 */
	private $attackerTeam;
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Lolol\TeamBundle\Entity\Team")
	 */
	private $opponentTeam;
	private $logs;
	
	/**
	 * Constructor.
	 *
	 * @param Team $attackerTeam        	
	 * @param Team $opponentTeam        	
	 */
	public function __construct(Team $attackerTeam, Team $opponentTeam) {
		$this->attackerTeam = $attackerTeam;
		$this->opponentTeam = $opponentTeam;
	}
	
	/**
	 * Process the fight
	 *
	 * @return string
	 */
	public function fight() {
		$logs[]['text'] = 'Attacker team: ' . $this->attackerTeam->getName();
		$logs[]['text'] = $this->attackerTeam->championsToString();
		$logs[]['text'] = '';
		
		$logs[]['text'] = 'Opponent team: ' . $this->opponentTeam->getName();
		$logs[]['text'] = $this->opponentTeam->championsToString();
		$logs[]['text'] = '';
		
		$logs[]['text'] = 'Battle starts';
		
		$this->attackerTeam->prepare();
		$this->opponentTeam->prepare();
		
		$time = 0;
		// Le combat continue jusqu'à ce qu'une équipe ait perdu
		while ( !($this->attackerTeam->hasLost($logs) || $this->opponentTeam->hasLost($logs)) ) {
			$logs[]['text'] = 'Début round ' . $time;
			$bActionA = true;
			$bActionB = true;
			// On continue d'agir tant qu'on a des actions à faire
			while ( ($bActionA !== false) || ($bActionB !== false) ) {
				// Chaque équipe joue en même temps
				$bActionA = $this->attackerTeam->play($time, $logs);
				$bActionB = $this->opponentTeam->play($time, $logs);
				// On résout les blessures
				if (false !== $bActionB) {
					$this->attackerTeam->setInjury($bActionB, $logs);
				}
				if (false !== $bActionA) {
					$this->opponentTeam->setInjury($bActionA, $logs);
				}
			}
			// Tout le monde a donc joué simultannément, sans vérification d'une victoire intermédiaire
			// C'est seulement après que chacun ait fait son action du moment qu'on avance le temps
			$logs[]['text'] = 'Fin round ' . $time;
			$logs[]['text'] = 'On vérifie si chacune des deux équipes a bien au moins un Champion encore debout';
			$time ++;
		}
		
		$logs[]['text'] = 'end fight';
		$this->logs = $logs;
	}
	public function getLogs() {
		return $this->logs;
	}
	
	/**
	 * Set attackerTeam
	 *
	 * @param \Lolol\TeamBundle\Entity\Team $attackerTeam        	
	 * @return Battle
	 */
	public function setAttackerTeam(\Lolol\TeamBundle\Entity\Team $attackerTeam) {
		$this->attackerTeam = $attackerTeam;
		
		return $this;
	}
	
	/**
	 * Get attackerTeam
	 *
	 * @return \Lolol\TeamBundle\Entity\Team
	 */
	public function getAttackerTeam() {
		return $this->attackerTeam;
	}
	
	/**
	 * Set opponentTeam
	 *
	 * @param \Lolol\TeamBundle\Entity\Team $opponentTeam        	
	 * @return Battle
	 */
	public function setOpponentTeam(\Lolol\TeamBundle\Entity\Team $opponentTeam) {
		$this->opponentTeam = $opponentTeam;
		
		return $this;
	}
	
	/**
	 * Get opponentTeam
	 *
	 * @return \Lolol\TeamBundle\Entity\Team
	 */
	public function getOpponentTeam() {
		return $this->opponentTeam;
	}
}
