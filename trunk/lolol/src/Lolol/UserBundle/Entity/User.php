<?php

namespace Lolol\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Lolol\AppBundle\Entity\Champion;

/**
 * @ORM\Entity
 * @ORM\Table(name="lolol_user")
 */
class User extends BaseUser {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Lolol\AppBundle\Entity\Champion", cascade={"persist"})
	 */
	private $champions;
	
	/**
	 *
	 * @var boolean @ORM\Column(name="displayHelp", type="boolean")
	 */
	private $displayHelp;
	
	/**
	 *
	 * @var float @ORM\Column(name="elo", type="float")
	 */
	private $elo;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->champions = new \Doctrine\Common\Collections\ArrayCollection();
		$this->displayHelp = true;
		$this->elo = 0;
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
	 * Add champions
	 *
	 * @param \Lolol\AppBundle\Entity\Champion $champions        	
	 * @return User
	 */
	public function addChampion(Champion $champions) {
		$this->champions[] = $champions;
		
		return $this;
	}
	
	/**
	 * Remove champions
	 *
	 * @param \Lolol\AppBundle\Entity\Champion $champions        	
	 */
	public function removeChampion(Champion $champions) {
		$this->champions->removeElement($champions);
	}
	
	/**
	 * Get champions
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getChampions() {
		return $this->champions;
	}
	
	/**
	 * Set displayHelp
	 *
	 * @param boolean $displayHelp        	
	 * @return User
	 */
	public function setDisplayHelp($displayHelp) {
		$this->displayHelp = $displayHelp;
		
		return $this;
	}
	
	/**
	 * Get displayHelp
	 *
	 * @return boolean
	 */
	public function getDisplayHelp() {
		return $this->displayHelp;
	}
	
	/**
	 * Set elo
	 *
	 * @param float $elo        	
	 * @return User
	 */
	public function setElo($elo) {
		$this->elo = $elo;
		
		return $this;
	}
	
	/**
	 * Get elo
	 *
	 * @return float
	 */
	public function getElo() {
		return $this->elo;
	}
}
