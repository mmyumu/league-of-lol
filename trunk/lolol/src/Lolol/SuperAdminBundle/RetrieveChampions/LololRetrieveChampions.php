<?php
namespace Lolol\SuperAdminBundle\RetrieveChampions;

use Symfony\Component\Filesystem\Filesystem;

class LololRetrieveChampions {	
	public function retrieve($championNames, $folder, $extension) {
		$limit = ini_get('max_execution_time');
		
		set_time_limit(600);
		
		$fs = new Filesystem();
		
		if(!$fs->exists($folder)) {
			$fs->mkdir($folder);
		}
		
		// Parse all the champion names
		foreach($championNames as $championName) {
			$html = file_get_contents('http://leagueoflegends.wikia.com/wiki/' . $championName);
			
			
			file_put_contents($folder.'/'.$championName.$extension, $html);
		}
		
		set_time_limit($limit);
		
		return true;
	}
}