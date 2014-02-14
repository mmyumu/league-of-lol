<?php
class vote extends Thread {
	private $logs;
	private $em;
	
	public function __construct($logs, $em) {
		$this->logs = $logs;
		$this->em = $em;
	}
	public function run() {
		foreach($logs as $log) {
			$em->persist($log);
		}
		$em->flush();
		$em->clear();
	}
}