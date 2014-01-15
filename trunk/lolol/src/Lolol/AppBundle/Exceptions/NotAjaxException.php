<?php

namespace Lolol\AppBundle\Exceptions;

class NotAjaxException extends CustomException {
	public function __construct() {
		parent::__construct('You must do AJAX call on this URL');
	}
}