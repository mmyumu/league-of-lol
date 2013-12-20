<?php

namespace Lolol\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Champion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Lolol\TeamBundle\Entity\ChampionRepository")
 */
class Champion {
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
	 *
	 * @var float @ORM\Column(name="attackDamage", type="float")
	 */
	private $attackDamage;
	
	/**
	 *
	 * @var float @ORM\Column(name="bonusAttackDamage", type="float")
	 */
	private $bonusAttackDamage;
	
	/**
	 *
	 * @var float @ORM\Column(name="armor", type="float")
	 */
	private $armor;
	
	/**
	 *
	 * @var float @ORM\Column(name="bonusArmor", type="float")
	 */
	private $bonusArmor;
	
	/**
	 *
	 * @var float @ORM\Column(name="health", type="float")
	 */
	private $health;
	
	/**
	 *
	 * @var float @ORM\Column(name="bonusHealth", type="float")
	 */
	private $bonusHealth;
	
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
	 * @return Champion
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
	 * Set attackDamage
	 *
	 * @param float $attackDamage        	
	 * @return Champion
	 */
	public function setAttackDamage($attackDamage) {
		$this->attackDamage = $attackDamage;
		
		return $this;
	}
	
	/**
	 * Get attackDamage
	 *
	 * @return float
	 */
	public function getAttackDamage() {
		return $this->attackDamage;
	}
	
	/**
	 * Set armor
	 *
	 * @param float $armor        	
	 * @return Champion
	 */
	public function setArmor($armor) {
		$this->armor = $armor;
		
		return $this;
	}
	
	/**
	 * Get armor
	 *
	 * @return float
	 */
	public function getArmor() {
		return $this->armor;
	}
	
	/**
	 * Set health
	 *
	 * @param float $health        	
	 * @return Champion
	 */
	public function setHealth($health) {
		$this->health = $health;
		
		return $this;
	}
	
	/**
	 * Get health
	 *
	 * @return float
	 */
	public function getHealth() {
		return $this->health;
	}

    /**
     * Set bonusAttackDamage
     *
     * @param float $bonusAttackDamage
     * @return Champion
     */
    public function setBonusAttackDamage($bonusAttackDamage)
    {
        $this->bonusAttackDamage = $bonusAttackDamage;

        return $this;
    }

    /**
     * Get bonusAttackDamage
     *
     * @return float 
     */
    public function getBonusAttackDamage()
    {
        return $this->bonusAttackDamage;
    }

    /**
     * Set bonusArmor
     *
     * @param float $bonusArmor
     * @return Champion
     */
    public function setBonusArmor($bonusArmor)
    {
        $this->bonusArmor = $bonusArmor;

        return $this;
    }

    /**
     * Get bonusArmor
     *
     * @return float 
     */
    public function getBonusArmor()
    {
        return $this->bonusArmor;
    }

    /**
     * Set bonusHealth
     *
     * @param float $bonusHealth
     * @return Champion
     */
    public function setBonusHealth($bonusHealth)
    {
        $this->bonusHealth = $bonusHealth;

        return $this;
    }

    /**
     * Get bonusHealth
     *
     * @return float 
     */
    public function getBonusHealth()
    {
        return $this->bonusHealth;
    }
}
