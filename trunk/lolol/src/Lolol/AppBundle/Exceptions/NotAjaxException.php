<?php

namespace Lolol\AppBundle\Exceptions;

class NotAjaxException extends \Exception {
	public function __construct() {
		parent::__construct('You must do AJAX call on this URL');
	}
}