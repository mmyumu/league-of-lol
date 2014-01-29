<?php

namespace Lolol\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lolol\BattleBundle\Entity\Injury as Injury;

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
	
	public function prepare() {
		foreach($this->championsTeam as $championTeam) {
			$championTeam->getChampion()->prepare();
		}
	}
	
	/**
	 * Function play()
	 * Fait faire une action minimale de l'équipe à un temps donné
	 * Chaque Champion peut intervenir, mais un seul le fait
	 * Retourne soit une blessure à infliger, soit false s'il n'y a aucun cooldown prêt
	 *
	 * @param float $time
	 *        	dans la partie
	 * @return IInjury blessure à infliger, ou false sinon
	 */
	public function play($time = 0, &$logs) {
		// On n'a rien fait, jusqu'à preuve du contraire
		$logs[]['text'] = 'L\'équipe ' . $this->name . ' regarde qui peut jouer au round ' . $time;
		$action = false;
		// Recherche d'un champion qui peut jouer
		
		foreach($this->championsTeam as $championTeam) {
			$champion = $championTeam->getChampion();
			$logs[]['text'] = 'L\'équipe ' . $this->name . ' demande au Champion ' . $champion->getName();
			$injury = $champion->play($time, $logs);
			$action = $injury;
			if ($injury !== false) {
				$logs[]['text'] = 'L\'équipe ' . $this->name . ' a fait jouer son Champion ' . $champion->getName();
				break;
			}
			else {
				$logs[]['text'] = 'L\'équipe ' . $this->name . ' n\'a pas pu faire jouer son Champion ' . $champion->getName();
			}
		}
		if ($action === false) {
			$logs[]['text'] = 'L\'équipe ' . $this->name . ' n\'a plus de Champion activable au tour ' . $time;
		}
		return $action;
	}
	
	/**
	 * Function hasLost()
	 * Indique si l'équipe en jeu a perdu
	 *
	 * @return	boolean	L'équipe a-t-elle perdu ? Si oui, true, sinon, false
	 */
	public function hasLost(&$logs) {
		// On a perdu, jusqu'à preuve du contraire
		$bLost = true;
		// Condition de non-défaite : au moins un Champion encore en vie
		foreach($this->championsTeam as $championTeam) {
			$logs[]['text'] = $championTeam->getChampion()->getName();
			if ($championTeam->getChampion()->isAlive($logs)) {
				$bLost = false;
				break;
			}
		}
		return $bLost;
	}
	
	/**
	 * Function setInjury()
	 * Inflige la blessure passée en paramètre à un Champion de l'équipe
	 *
	 * @param	IInjury	p_injury	La blessure à infliger
	 */
	public function setInjury(Injury $injury, &$logs) {
		$logs[]['text'] = 'L\'équipe ' . $this->name . ' regarde à quel Champion infliger la blessure';
		// Recherche d'un champion encore en vie
		foreach($this->championsTeam as $championTeam) {
			$champion = $championTeam->getChampion();
			if ($champion->isAlive($logs)) {
				$logs[]['text'] = 'L\'équipe ' . $this->name . ' inflige la blessure à ' . $champion->getName() . ' car il est encore en vie';
				$champion->setInjury($injury, $logs);
				break;
			}
		}
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
