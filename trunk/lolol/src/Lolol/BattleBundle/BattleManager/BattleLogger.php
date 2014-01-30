<?php

namespace Lolol\BattleBundle\BattleManager;

class BattleLogger {
	private $logs;
	
	public function log($text, $class = "", $strong = false, $icon = BattleIcon::DEFAULT_ICON) {
		$this->logs[] = array('text' => $text, 'class' => $class, 'strong' => $strong, 'icon' => $icon);
	}
	
	public function getLogs() {
		return $this->logs;
	}
}