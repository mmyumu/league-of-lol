<?php

namespace Lolol\BattleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lolol\BattleBundle\BattleManager\BattleIcon;

/**
 * @ORM\Entity(repositoryClass="Lolol\BattleBundle\Entity\LogRepository")
 */
class Log {
	
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
	 * @ORM\OneToMany(targetEntity="Lolol\BattleBundle\Entity\Parameter", mappedBy="log", cascade={"persist", "remove"})
	 */
	private $parameters;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Lolol\BattleBundle\Entity\LogType")
	 */
	private $logTypes;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Lolol\BattleBundle\Entity\Battle", inversedBy="logs")
	 */
	private $battle;
	
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
	public function __construct($key, $parameters = array(), $logTypes = array(), $icon = BattleIcon::DEFAULT_ICON) {
		$this->key = $key;
		foreach($parameters as $key => $value) {
			$this->addParameter(new Parameter($key, $value));
		}
		$this->logTypes = $logTypes;
		$this->icon = $icon;
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
	 * @return Log
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
	 * Set battle
	 *
	 * @param \Lolol\BattleBundle\Entity\Battle $battle        	
	 * @return Log
	 */
	public function setBattle(\Lolol\BattleBundle\Entity\Battle $battle) {
		$this->battle = $battle;
		
		return $this;
	}
	
	/**
	 * Get battle
	 *
	 * @return \Lolol\BattleBundle\Entity\Battle
	 */
	public function getBattle() {
		return $this->battle;
	}
	
	/**
	 * Add parameters
	 *
	 * @param \Lolol\BattleBundle\Entity\Parameter $parameters        	
	 * @return Log
	 */
	public function addParameter(\Lolol\BattleBundle\Entity\Parameter $parameters) {
		$this->parameters[] = $parameters;
		$parameters->setLog($this);
		
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
	public function getParametersArray() {
		$result = array();
		if (!empty($this->parameters)) {
			foreach($this->parameters as $parameter) {
				$result[$parameter->getKey()] = $parameter->getValue();
			}
		}
		return $result;
	}
	public function isStrong() {
		return false;
	}
	public function getText() {
		return '';
	}
	
	/**
	 * Add logTypes
	 *
	 * @param \Lolol\BattleBundle\Entity\LogType $logTypes        	
	 * @return Log
	 */
	public function addLogType(\Lolol\BattleBundle\Entity\LogType $logTypes) {
		$this->logTypes[] = $logTypes;
		return $this;
	}
	
	/**
	 * Remove logTypes
	 *
	 * @param \Lolol\BattleBundle\Entity\LogType $logTypes        	
	 */
	public function removeLogType(\Lolol\BattleBundle\Entity\LogType $logTypes) {
		$this->logTypes->removeElement($logTypes);
	}
	
	/**
	 * Get logTypes
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getLogTypes() {
		return $this->logTypes;
	}
	
	/**
	 * Set icon
	 *
	 * @param string $icon        	
	 * @return Log
	 */
	public function setIcon($icon) {
		$this->icon = $icon;
		
		return $this;
	}
	
	/**
	 * Get icon
	 *
	 * @return string
	 */
	public function getIcon() {
		return $this->icon;
	}
}
