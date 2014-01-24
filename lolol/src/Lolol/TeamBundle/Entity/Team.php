<?php

namespace Lolol\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
	 * @ORM\ManyToOne(targetEntity="Lolol\UserBundle\Entity\User")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;
	
	/**
	 *	Tell whether the team is the defender or not (only one team defender per user)
	 * @var boolean @ORM\Column(name="defender", type="boolean")
	 */
	private $defender;
	
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
    public function setDefender($defender)
    {
        $this->defender = $defender;

        return $this;
    }

    /**
     * Get defender
     *
     * @return boolean 
     */
    public function isDefender()
    {
        return $this->defender;
    }
}
