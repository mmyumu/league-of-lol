<?php

namespace Lolol\BattleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Lolol\BattleBundle\Entity\ParameterRepository")
 */
class Parameter {
	
	/**
	 *
	 * @var integer @ORM\Column(name="id", type="integer")
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Lolol\BattleBundle\Entity\Log", inversedBy="parameters")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $log;
	
	/**
	 *
	 * @var string @ORM\Column(name="keyParam", type="string", length=255)
	 */
	private $key;
	
	/**
	 *
	 * @var string @ORM\Column(name="valueParam", type="string", length=255)
	 */
	private $value;
	
	/**
	 * @ORM\OneToMany(targetEntity="Lolol\BattleBundle\Entity\Parameter", mappedBy="log", cascade={"persist", "remove"})
	 */
	private $parameters;
	
	/**
	 * Constructor
	 * 
	 * @param string $key        	
	 * @param string $value        	
	 */
	public function __construct($key, $value) {
		$this->key = $key;
		$this->value = $value;
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
	 * Set key
	 *
	 * @param string $key        	
	 * @return Parameter
	 */
	public function setKey($key) {
		$this->key = $key;
		
		return $this;
	}
	
	/**
	 * Get key
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}
	
	/**
	 * Set value
	 *
	 * @param string $value        	
	 * @return Parameter
	 */
	public function setValue($value) {
		$this->value = $value;
		
		return $this;
	}
	
	/**
	 * Get value
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * Add parameters
	 *
	 * @param \Lolol\BattleBundle\Entity\Parameter $parameters        	
	 * @return Parameter
	 */
	public function addParameter(\Lolol\BattleBundle\Entity\Parameter $parameters) {
		$this->parameters[] = $parameters;
		
		return $this;
	}
	
	/**
	 * Remove parameters
	 *
	 * @param \Lolol\BattleBundle\Entity\Parameter $parameters        	
	 */
	public function removeParameter(\Lolol\BattleBundle\Entity\Parameter $parameters) {
		$this->parameters->removeElement($parameters);
	}
	
	/**
	 * Get parameters
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getParameters() {
		return $this->parameters;
	}

    /**
     * Set log
     *
     * @param \Lolol\BattleBundle\Entity\Log $log
     * @return Parameter
     */
    public function setLog(\Lolol\BattleBundle\Entity\Log $log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return \Lolol\BattleBundle\Entity\Log 
     */
    public function getLog()
    {
        return $this->log;
    }
}
