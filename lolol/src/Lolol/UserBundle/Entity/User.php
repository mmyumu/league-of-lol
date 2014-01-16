<?php

namespace Lolol\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
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
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->champions = new \Doctrine\Common\Collections\ArrayCollection();
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
		$this->champions [] = $champions;
		
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
}