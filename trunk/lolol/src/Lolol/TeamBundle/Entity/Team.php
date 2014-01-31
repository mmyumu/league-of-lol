<?php

namespace Lolol\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lolol\BattleBundle\BattleManager\BattleIcon as BattleIcon;

/**
 * Team
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Lolol\TeamBundle\Entity\TeamRepository")
 */
class Team {
	/**
	 *
	 * @var integer @ORM\Column(name="id", type="integer")
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 *
	 * @var string @ORM\Column(name="name", type="string", length=255)
	 */
	private $name;
	
	/**
	 * @ORM\OneToMany(targetEntity="Lolol\TeamBundle\Entity\ChampionTeam", mappedBy="team")
	 */
	private $championsTeam;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Lolol\UserBundle\Entity\User")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;
	
	/**
	 * Tell whether the team is the defender or not (only one team defender per user)
	 *
	 * @var boolean @ORM\Column(name="defender", type="boolean")
	 */
	private $defender;
	
	
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->defender = false;
	}
	
	/**
	 * Returns a string with the champions and their positions
	 *
	 * @return string
	 */
	public function championsToString() {
		$str = '';
		foreach($this->getChampionsTeam() as $championTeam) {
			$str .= " " . $championTeam->getPosition() + 1 . "-" . $championTeam->getChampion()->getName() . " ";
		}
		return $str;
	}
	
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set name
	 *
	 * @param string $name        	
	 * @return Team
	 */
	public function setName($name) {
		$this->name = $name;
		
		return $this;
	}
	
	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Set user
	 *
	 * @param \Lolol\UserBundle\Entity\User $user        	
	 * @return Team
	 */
	public function setUser(\Lolol\UserBundle\Entity\User $user) {
		$this->user = $user;
		
		return $this;
	}
	
	/**
	 * Get user
	 *
	 * @return \Lolol\UserBundle\Entity\User
	 */
	public function getUser() {
		return $this->user;
	}
	
	/**
	 * Set defender
	 *
	 * @param boolean $defender        	
	 * @return Team
	 */
	public function setDefender($defender) {
		$this->defender = $defender;
		
		return $this;
	}
	
	/**
	 * Get defender
	 *
	 * @return boolean
	 */
	public function isDefender() {
		return $this->defender;
	}
	
	/**
	 * Get defender
	 *
	 * @return boolean
	 */
	public function getDefender() {
		return $this->defender;
	}
	
	/**
	 * Add championsTeam
	 *
	 * @param \Lolol\TeamBundle\Entity\ChampionTeam $championsTeam        	
	 * @return Team
	 */
	public function addChampionsTeam(\Lolol\TeamBundle\Entity\ChampionTeam $championsTeam) {
		$this->championsTeam[] = $championsTeam;
		$championsTeam->setTeam($this);
		return $this;
	}
	
	/**
	 * Remove championsTeam
	 *
	 * @param \Lolol\TeamBundle\Entity\ChampionTeam $championsTeam        	
	 */
	public function removeChampionsTeam(\Lolol\TeamBundle\Entity\ChampionTeam $championsTeam) {
		$this->championsTeam->removeElement($championsTeam);
	}
	
	/**
	 * Get championsTeam
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getChampionsTeam() {
		return $this->championsTeam;
	}
}
