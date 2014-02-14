<?php

namespace Lolol\BattleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lolol\BattleBundle\BattleManager\BattleIcon;

/**
 * @ORM\Entity(repositoryClass="Lolol\BattleBundle\Entity\LogTestRepository")
 */
class LogTest {
	
	/**
	 *
	 * @var integer @ORM\Column(name="id", type="integer")
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 *
	 * @var string @ORM\Column(name="keyLog", type="string", length=255)
	 */
	private $key;
	
	/**
	 *
	 * @var string @ORM\Column(name="icon", type="string", length=255, nullable=true)
	 */
	private $icon;
	
	/**
	 * Constructor.
	 *
	 * @param string $key        	
	 * @param array $parameters        	
	 * @param array $logTypes        	
	 * @param string $icon        	
	 */
	public function __construct($key, $icon = BattleIcon::DEFAULT_ICON) {
		$this->key = $key;
		$this->icon = $icon;
	}

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set key
     *
     * @param string $key
     * @return LogTest
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
     * Set icon
     *
     * @param string $icon
     * @return LogTest
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }
}
