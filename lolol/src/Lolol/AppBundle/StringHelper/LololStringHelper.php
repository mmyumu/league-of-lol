<?php

namespace Lolol\AppBundle\StringHelper;

class LololStringHelper {
	private $folder;
	private $prefixIcons48;
	private $prefixIcons20;
	private $suffixIcons;
	
	/**
	 * Inject parameters
	 *
	 * @param string $folder
	 * @param string $prefixIcons48
	 * @param string $prefixIcons20
	 * @param string $suffixIcons
	 */
	public function __construct($folder, $prefixIcons48, $prefixIcons20, $suffixIcons) {
		$this->folder = $folder;
		$this->prefixIcons48 = $prefixIcons48;
		$this->prefixIcons20 = $prefixIcons20;
		$this->suffixIcons = $suffixIcons;
	}
	
	/**
	 * Replace characters to retrieve the image name from the champion name given as parameter.
	 *
	 * @param string $championName        	
	 * @return the name of the image
	 */
	public function getImgName($championName) {
		$imgName = str_replace('\'', '', $championName);
		$imgName = str_replace(' ', '', $imgName);
		$imgName = str_replace('.', '', $imgName);
		return $imgName;
	}
	
	/**
	 * Replace the characters to retrieve the page of the wiki of the champion given as parameter.
	 *
	 * @param string $championName        	
	 * @return the name of the page
	 */
	public function getWikiPage($championName) {
		return str_replace(' ', '_', $championName);
	}
	
	/**
	 * Get the relative path of the icon (48px) on file system of the champion with the name given as parameter.
	 *
	 * @param string $championName        	
	 */
	public function getIcon48Path($championName) {
		$imgName = $this->getImgName($championName);
		return $this->folder . '/img/' . $this->prefixIcons48 . $imgName . $this->suffixIcons;
	}
	
	/**
	 * Get the relative path of the icon (20px) on file system of the champion with the name given as parameter.
	 *
	 * @param string $championName
	 */
	public function getIcon20Path($championName) {
		$imgName = $this->getImgName($championName);
		return $this->folder . '/img/' . $this->prefixIcons20 . $imgName . $this->suffixIcons;
	}
}