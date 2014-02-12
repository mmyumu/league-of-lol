<?php

namespace Lolol\BattleBundle\BattleManager;

use Doctrine\ORM\EntityManager;

class LogTypesManager {
	private $logTypes;
	
	/**
	 * Constructor.
	 *
	 * @param EntityManager $entityManager        	
	 */
	public function __construct(EntityManager $em) {
		$this->logTypes = $em->getRepository('LololBattleBundle:LogType')->getLogTypesAsArray();
	}
	
	/**
	 * Returns the log type associated to the key given as parameter
	 *
	 * @param string $key        	
	 */
	public function getLogType($key) {
		return $this->logTypes[$key];
	}
	
	public function get($keys) {
		$result = array();
		foreach($keys as $key) {
			$result[] = $this->logTypes[$key];
		}
		return $result;
	}
	
	public function getLT() {
		return $this->logTypes;
	}
}