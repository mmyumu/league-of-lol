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
	
	/**
	 *
	 * @var string @ORM\Column(name="logs", type="text")
	 */
	private $logs;
	
	/**
	 *
	 * @var integer @ORM\Column(name="result", type="integer")
	 */
	private $result;
	
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
	
	/**
	 * Set logs
	 *
	 * @param string $logs        	
	 * @return Battle
	 */
	public function setLogs($logs) {
		$this->logs = $logs;
		
		return $this;
	}
	
	/**
	 * Get logs
	 *
	 * @return string
	 */
	public function getLogs() {
		return $this->logs;
	}

    /**
     * Set result
     *
     * @param integer $result
     * @return Battle
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return integer 
     */
    public function getResult()
    {
        return $this->result;
    }
}
