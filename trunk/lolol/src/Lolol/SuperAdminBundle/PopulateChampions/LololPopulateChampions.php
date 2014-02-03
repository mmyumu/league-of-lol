<?php

namespace Lolol\SuperAdminBundle\PopulateChampions;

use Symfony\Component\Filesystem\Filesystem;
use Lolol\AppBundle\Entity\Champion;
use \Lolol\TeamBundle\Champions\LololChampions;
use \Lolol\AppBundle\StringHelper\LololStringHelper;

class LololPopulateChampions {
	private $championNames;
	private $stringHelper;
	private $folder;
	private $statExtension;
	private $patternBaseFloat = '/^\d+\.?\d*/';
	private $patternBonusFloat = '/\(\+(\d+\.?\d*)\)/';
	private $patternBonusPercent = '/\(\+(\d+\.?\d*)%\)/';
	private $patternBonusRange = '/(Melee|Ranged|)/';
	private $patternBonusEmpty = '/()/';
	
	/**
	 * Initializes the service with injected services/parameters
	 *
	 * @param \Lolol\TeamBundle\Champions\LololChampions $championNames:
	 *        	the service to get the campions list
	 * @param string $folder:
	 *        	parameter of the folder containing the retrived files
	 * @param string $statExtension:
	 *        	parameter of the extension of the stats files
	 */
	public function __construct(LololChampions $championNames, LololStringHelper $stringHelper, $folder, $statExtension) {
		$this->championNames = $championNames;
		$this->stringHelper = $stringHelper;
		$this->folder = $folder;
		$this->statExtension = $statExtension;
	}
	
	/**
	 * Persists the champions retrieved in the database.
	 *
	 * @param \Doctrine\ORM\EntityManager $em        	
	 * @return associative array containing the added/updated champions
	 */
	public function populate(\Doctrine\ORM\EntityManager $em) {
		$functions = array(
				'Attack damage' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setAttackDamage($base);
							$champion->setBonusAttackDamage($bonus);
						}),
				'Armor' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setArmor($base);
							$champion->setBonusArmor($bonus);
						}),
				'Health' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setHealth($base);
							$champion->setBonusHealth($bonus);
						}),
				'Health regen.' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setHealthRegen($base);
							$champion->setBonusHealthRegen($bonus);
						}),
				'Mana' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setMana($base);
							$champion->setBonusMana($bonus);
						}),
				'Mana regen.' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setManaRegen($base);
							$champion->setBonusManaRegen($bonus);
						}),
				'Magic res.' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setMagicResist($base);
							$champion->setBonusMagicResist($bonus);
						}),
				'Range' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusRange,
						'setter' => function ($champion, $base, $type) {
							// Initialize the matching attribute
							$champion->setAttackRange($base);
							$champion->setAttackRangeType($type);
						}),
				'Mov. speed' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusEmpty,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setMoveSpeed($base);
							$champion->setBonusMoveSpeed($bonus);
						}),
				'Attack speed' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusPercent,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setAttackSpeed($base);
							$champion->setBonusAttackSpeed($bonus);
						}));
		
		$fs = new Filesystem();
		
		$updated = array();
		$added = array();
		
		// Parse all the champion names
		foreach($this->championNames->getList() as $championName) {
			/*
			 * $aContext = array( 'http' => array( 'proxy' => 'proxy_aeropark:8080', 'request_fulluri' => true, ), ); $cxContext = stream_context_create($aContext);
			 */
			$championFileName = str_replace(' ', '_', $championName);
			$filename = $this->folder . '/' . $championFileName . $this->statExtension;
			
			// If there is a matching wiki file, user it to add/update champion in DB, otherwise skip this champion
			if ($fs->exists($filename)) {
				// $html = file_get_contents('http://leagueoflegends.wikia.com/wiki/' . $championName, False, $cxContext);
				$html = file_get_contents($filename);
			}
			else {
				continue;
			}
			
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
								
			// Try to retrieve the existing champion from database
			$champion = $em->getRepository('LololAppBundle:Champion')->findOneByName($championName);
			
			if ($champion == null) {
				// Initialize the champion to be persisted
				$champion = new Champion();
				$champion->setName($championName);
				$added[] = $championName;
			}
			else {
				$updated[] = $championName;
			}
			
			$champion->setImgName($this->stringHelper->getImgName($championName));
			
			
			// Get the table containing the infos of the champion
			$championUpperTable = $DOM->getElementById('champion_info-upper');
				
			$tds = $championUpperTable->getElementsByTagName('td');
				
			foreach($tds as $td) {
				$spans = $td->getElementsByTagName('span');
				for($i = 0; $i < $spans->length; $i++) {
					$node = $spans->item($i);
					$value = trim($node->nodeValue);
					if(strpos($value, $championName) !== FALSE) {
						$nextNode = $spans->item($i+1);
						$nextValue = trim($nextNode->nodeValue);
						$champion->setSubName($nextValue);
						break;
					}
				}
			}
			
			// Get the table containing the stats of the champion
			$championTable = $DOM->getElementById('champion_info-lower');
				
			$items = $championTable->getElementsByTagName('td');
			
			for($i = 0; $i < $items->length; $i ++) {
				$node = $items->item($i);
				$value = trim($node->nodeValue);
				
				// Gets the next cell
				if (array_key_exists($value, $functions)) {
					$patternBase = $functions[trim($value)]['patternBase'];
					$attribute = $this->parseAttribute($items->item($i + 1)->nodeValue, $patternBase, $functions[trim($value)]['patternBonus']);
					if ($attribute !== FALSE) {
						$functions[trim($value)]['setter']($champion, $attribute['base'], $attribute['bonus']);
						$i ++;
					}
				}
			}
			
			$em->persist($champion);
		}
		
		$em->flush();
		
		$result['added'] = $added;
		$result['updated'] = $updated;
		
		return $result;
	}
	
	/**
	 * Parse the given attribute according to the pattern.
	 * Return the base value and bonus value of the attribute in an array.
	 *
	 * @param string $attribute
	 *        	attribute to be parsed
	 * @param string $patternBase
	 *        	the pattern to retrieve the base value of the attribute
	 * @param string $patternBonus
	 *        	the pattern to retrieve the bonus value of the attribute
	 * @return associative array with 'base' and 'bonus' keys
	 */
	private function parseAttribute($attribute, $patternBase, $patternBonus) {
		$attribute = trim($attribute);
		
		// Get base number
		if ($attribute != '' && preg_match($patternBase, $attribute, $base) == 1 && preg_match($patternBonus, $attribute, $bonus) == 1) {
			if (!isset($bonus[1]) || $bonus[1] == '') {
				$bonus[1] = NULL;
			}
			return array(
					'base' => $base[0],
					'bonus' => $bonus[1]);
		}
		return FALSE;
	}
}