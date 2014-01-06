<?php

namespace Lolol\AppBundle\StringReplace;

class LololStringReplace {
	/**
	 * Replace characters to retrieve the image name from the champion name given as parameter.
	 *
	 * @param string $championName        	
	 * @return mixed
	 */
	public function getImgName($championName) {
		$tmpName = str_replace('\'', '', $championName);
		$tmpName = str_replace(' ', '', $tmpName);
		$tmpName = str_replace('.', '', $tmpName);
		return $tmpName;
	}
}