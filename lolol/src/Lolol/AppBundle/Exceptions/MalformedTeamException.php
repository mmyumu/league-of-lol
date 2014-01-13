<?php

namespace Lolol\AppBundle\Exceptions;

class MalformedTeamException extends \Exception {
	public function __construct($message) {
		parent::__construct($message);
	}
}