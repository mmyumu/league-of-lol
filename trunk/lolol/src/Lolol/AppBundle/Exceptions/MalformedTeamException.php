<?php

namespace Lolol\AppBundle\Exceptions;

class MalformedTeamException extends CustomException {
	public function __construct($message) {
		parent::__construct($message);
	}
}