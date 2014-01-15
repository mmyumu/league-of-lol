<?php

namespace Lolol\AppBundle\Exceptions;

class AccessRightException extends CustomException {
	public function __construct($message) {
		parent::__construct($message);
	}
}