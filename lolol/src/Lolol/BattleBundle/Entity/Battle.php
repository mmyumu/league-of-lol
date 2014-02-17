<?php

namespace Lolol\BattleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lolol\TeamBundle\Entity\Team as Team;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Lolol\BattleBundle\Entity\BattleRepository")
 */
class Battle {
	
	/**
	 *
	 * @var integer @ORM\Column(name="id", type="integer")
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Lolol\TeamBundle\Entity\Team", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $attackerTeam;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Lolol\TeamBundle\Entity\Team", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $opponentTeam;
	
	/**
	 * @ORM\OneToMany(targetEntity="Lolol\BattleBundle\Entity\Log", mappedBy="battle", cascade={"persist"})
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
	 * @param Team $opponentTeam        	
	 * @param Team $attackerTeam        	
	 */
	public function __construct(Team $opponentTeam, Team $attackerTeam) {
		$this->opponentTeam = $opponentTeam;
		$this->attackerTeam = $attackerTeam;
		$this->logs = new ArrayCollection();
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
	public function setResult($result) {
		$this->result = $result;
		
		return $this;
	}
	
	/**
	 * Get result
	 *
	 * @return integer
	 */
	public function getResult() {
		return $this->result;
	}
	
	/**
	 * Add logs
	 *
	 * @param \Lolol\BattleBundle\Entity\Log $logs        	
	 * @return Battle
	 */
	public function addLog(\Lolol\BattleBundle\Entity\Log $logs) {
		$this->logs[] = $logs;
		$logs->setBattle($this);
		
		return $this;
	}
	
	/**
	 * Remove logs
	 *
	 * @param \Lolol\BattleBundle\Entity\Log $logs        	
	 */
	public function removeLog(\Lolol\BattleBundle\Entity\Log $logs) {
		$this->logs->removeElement($logs);
	}
	
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
}
