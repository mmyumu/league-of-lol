<?php

namespace Lolol\SuperAdminBundle\RetrieveChampions;

use Symfony\Component\Filesystem\Filesystem;

class LololRetrieveChampions {
	
	/**
	 * Retrieve the html containing the stats of the given champions
	 *
	 * @param array $championNames        	
	 * @param string $folder        	
	 * @param string $extension        	
	 * @return boolean
	 */
	public function retrieveStats($championNames, $folder, $extension) {
		$limit = ini_get('max_execution_time');
		
		set_time_limit(600);
		
		$fs = new Filesystem();
		
		if (!$fs->exists($folder)) {
			$fs->mkdir($folder);
		}
		
		// Parse all the champion names
		foreach($championNames as $championName) {
			$championName = str_replace(' ', '_', $championName);
			$html = file_get_contents('http://leagueoflegends.wikia.com/wiki/' . $championName);
			
			file_put_contents($folder . '/' . $championName . $extension, $html);
		}
		
		set_time_limit($limit);
		
		return true;
	}
	
	/**
	 * Retrieve the icons of the given champions
	 *
	 * @param array $championNames        	
	 * @param string $folder        	
	 * @return boolean
	 */
	public function retrieveIcons48($championNames, $folder, $prefix, $suffix) {
		$folder = $folder . '/img';
		
		$limit = ini_get('max_execution_time');
		
		set_time_limit(600);
		
		$fs = new Filesystem();
		
		if (!$fs->exists($folder)) {
			$fs->mkdir($folder);
		}
		
		// Get 48px icons
		$html = file_get_contents('http://leagueoflegends.wikia.com/wiki/League_of_Legends_Wiki');
		
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
		$championsDiv = $DOM->getElementById('champions');
		
		// Parse all the champion names
		foreach($championNames as $championName) {
			$championName = str_replace('\'', '', $championName);
			$championName = str_replace(' ', '', $championName);
			$championName = str_replace('.', '', $championName);
			foreach($championsDiv->getElementsByTagName('img') as $img) {
				$url = $img->getAttribute('src');
				$imgName = $prefix . $championName . $suffix;
				if (strpos($url, $imgName) !== FALSE) {
					$imgData = file_get_contents($url);
					file_put_contents($folder . '/' . $imgName, $imgData);
					break;
				}
			}
		}
		
		set_time_limit($limit);
		
		return true;
	}
}