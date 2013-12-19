<?php

namespace Lolol\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LololUserBundle extends Bundle {
	public function getParent() {
		return 'FOSUserBundle';
	}
}
