<?php

namespace Lolol\BattleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Lolol\BattleBundle\Entity\LogTypeRepository")
 */
class LogType {
	
	/**
	 *
	 * @var integer @ORM\Column(name="id", type="integer")
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 *
	 * @var string @ORM\Column(name="keyType", type="string", length=255)
	 */
	private $key;
	
	/**
	 *
	 * @var string @ORM\Column(name="valueType", type="string", length=255)
	 */
	private $value;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->logs = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * Get logs
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getLogs() {
		return $this->logs;
	}

    /**
     * Set key
     *
     * @param string $key
     * @return LogType
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string 
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return LogType
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
}
