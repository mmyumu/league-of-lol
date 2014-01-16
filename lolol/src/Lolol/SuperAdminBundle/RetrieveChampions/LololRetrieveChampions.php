<?php

namespace Lolol\SuperAdminBundle\RetrieveChampions;

use Symfony\Component\Filesystem\Filesystem;
use Lolol\SuperAdminBundle\RetrieveFile\LololRetrieveFile;
use Lolol\AppBundle\StringHelper\LololStringHelper;

class LololRetrieveChampions {
	private $retrieveFile;
	private $stringHelper;
	private $folder;
	private $statExtension;
	private $prefixIcon48;
	private $prefixIcon20;
	private $suffixIcon;
	private $suffixIconSp;
	
	/**
	 * Initializes the service with the injected services/parameters
	 *
	 * @param LololRetrieveFile $retrieveFile        	
	 * @param LololStringHelper $stringHelper        	
	 * @param string $folder        	
	 * @param string $extension        	
	 * @param string $statExtension        	
	 * @param string $prefixIcon48        	
	 * @param string $prefixIcon20        	
	 * @param string $suffixIcon48        	
	 * @param string $suffixIcon48Sp        	
	 */
	public function __construct(LololRetrieveFile $retrieveFile, LololStringHelper $stringHelper, $folder, $statExtension, $prefixIcon48, $prefixIcon20, $suffixIcon, $suffixIconSp) {
		$this->retrieveFile = $retrieveFile;
		$this->stringHelper = $stringHelper;
		$this->folder = $folder;
		$this->statExtension = $statExtension;
		$this->prefixIcon48 = $prefixIcon48;
		$this->prefixIcon20 = $prefixIcon20;
		$this->suffixIcon = $suffixIcon;
		$this->suffixIconSp = $suffixIconSp;
	}
	
	/**
	 * Retrieve the html containing the stats of the given champions
	 *
	 * @param array $championNames        	
	 * @param string $folder        	
	 * @param string $extension        	
	 * @return boolean
	 */
	public function retrieveStats($championNames) {
		$limit = ini_get('max_execution_time');
		
		set_time_limit(600);
		
		$fs = new Filesystem();
		
		if (!$fs->exists($this->folder)) {
			$fs->mkdir($this->folder);
		}
		
		// Parse all the champion names
		foreach($championNames as $championName) {
			$championName = $this->stringHelper->getWikiPage($championName);
			
			$html = $this->retrieveFile->getFromWiki($championName);
			
			file_put_contents($this->folder . '/' . $championName . $this->statExtension, $html);
		}
		
		set_time_limit($limit);
		
		return true;
	}
	
	/**
	 *
	 * @param array $championNames        	
	 */
	public function clearStats($championNames) {
		$fs = new Filesystem();
		
		// Parse all the champion names
		foreach($championNames as $championName) {
			$championName = $this->stringHelper->getWikiPage($championName);
			
			$fileName = $this->folder . '/' . $championName . $this->statExtension;
			
			if ($fs->exists($fileName)) {
				$fs->remove($fileName);
			}
		}
		
		return true;
	}
	
	/**
	 * Retrieve the icons for the px given as parameters
	 * @param array $championNames
	 * @param int $px
	 */
	public function retrieveIcons($championNames, $px) {
		if ($px == 48) {
			return $this->retrieveIconsFromPage($championNames, 'League_of_Legends_Wiki', 'champions', $this->prefixIcon48);
		}
		else if ($px == 20) {
			return $this->retrieveIconsFromPage($championNames, 'Champion', 'mw-content-text', $this->prefixIcon20);
		}
	}
	
	/**
	 * Retrieve the icons of the given champions
	 *
	 * @param array $championNames        	
	 * @param int $px        	
	 * @return boolean
	 */
	public function retrieveIconsFromPage($championNames, $page, $div, $prefix) {
		$folder = $this->folder . '/img';
		
		$limit = ini_get('max_execution_time');
		
		set_time_limit(600);
		
		$fs = new Filesystem();
		
		if (!$fs->exists($folder)) {
			$fs->mkdir($folder);
		}
		
		// Get icons page
		$html = $this->retrieveFile->getFromWiki($page);
		
		$DOM = new \DomDocument();
		
		// Load the HTML as DOM structure (malformed HTML warnings are disabled during the load)
		// modify state
		$libxml_previous_state = libxml_use_internal_errors(true);
		// parse
		$DOM->loadHTML($html);
		// handle errors
		libxml_clear_errors();
		// restore
		libxml_use_internal_errors($libxml_previous_state);
		
		// Get the table containing the stats of the champion
		$championsDiv = $DOM->getElementById($div);
		
		// Parse all the champion names
		foreach($championNames as $championName) {
			$championName = $this->stringHelper->getImgName($championName);
			foreach($championsDiv->getElementsByTagName('img') as $img) {
				$url = $img->getAttribute('src');
				
				$imgName = $prefix . $championName . $this->suffixIcon;
				$imgNameSp = $prefix . $championName . $this->suffixIconSp;
				
				if ((strpos($url, $imgName) !== FALSE) || (strpos($url, $imgNameSp) !== FALSE)) {
					$imgData = $this->retrieveFile->get($url);
					file_put_contents($folder . '/' . $imgName, $imgData);
					break;
				}
			}
		}
		
		set_time_limit($limit);
		
		return true;
	}
	
	/**
	 * Remove the icons from file system
	 *
	 * @param array $championNames        	
	 * @param
	 *        	int px of the icons
	 */
	public function clearIcons($championNames, $px) {
		$fs = new Filesystem();
		
		// Parse all the champion names
		foreach($championNames as $championName) {
			if ($px == 48) {
				$fileName = $this->stringHelper->getIcon48Path($championName);
			}
			else if ($px == 20) {
				$fileName = $this->stringHelper->getIcon20Path($championName);
			}
			
			if ($fs->exists($fileName)) {
				$fs->remove($fileName);
			}
		}
		
		return true;
	}
}