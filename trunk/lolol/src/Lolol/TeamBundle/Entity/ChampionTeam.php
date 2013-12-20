<?php

namespace Lolol\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ChampionTeam {
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Lolol\TeamBundle\Entity\Champion")
	 */
	private $champion;
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Lolol\TeamBundle\Entity\Team")
	 */
	private $team;
	
	/**
	 * @ORM\Column(name="position", type="integer")
	 */
	private $position;
	
	// TODO: add stuff, trinkets
	
	/**
	 * Set position
	 *
	 * @param integer $position        	
	 * @return ChampionTeam
	 */
	public function setPosition($position) {
		$this->position = $position;
		
		return $this;
	}
	
	/**
	 * Get position
	 *
	 * @return integer
	 */
	public function getPosition() {
		return $this->position;
	}
	
	/**
	 * Set champion
	 *
	 * @param \Lolol\TeamBundle\Entity\Champion $champion        	
	 * @return ChampionTeam
	 */
	public function setChampion(\Lolol\TeamBundle\Entity\Champion $champion) {
		$this->champion = $champion;
		
		return $this;
	}
	
	/**
	 * Get champion
	 *
	 * @return \Lolol\TeamBundle\Entity\Champion
	 */
	public function getChampion() {
		return $this->champion;
	}
	
	/**
	 * Set team
	 *
	 * @param \Lolol\TeamBundle\Entity\Team $team        	
	 * @return ChampionTeam
	 */
	public function setTeam(\Lolol\TeamBundle\Entity\Team $team) {
		$this->team = $team;
		
		return $this;
	}
	
	/**
	 * Get team
	 *
	 * @return \Lolol\TeamBundle\Entity\Team
	 */
	public function getTeam() {
		return $this->team;
	}
}
