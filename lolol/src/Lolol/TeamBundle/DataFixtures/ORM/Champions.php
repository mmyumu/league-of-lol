<?php
// src/Lolol/TeamBundle/DataFixtures/ORM/Competences.php
namespace Lolol\TeamBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Lolol\TeamBundle\Entity\Champion;

class Champions implements FixtureInterface {
	private $patternBaseFloat = '/^\d+\.?\d*/';
	private $patternBonusFloat = '/\(\+(\d+\.?\d*)\)/';
	private $patternBonusPercent = '/\(\+(\d+\.?\d*%)\)/';
	private $patternBonusRange = '/[\((Melee|Ranged|)\)]?/';
	private $patternBonusEmpty = '/()/';
	
	public function load(ObjectManager $manager) {
		$championNames = array(
				'Aatrox',
				'Ahri',
				'Akali',
				'Alistar',
				'Amumu',
				'Anivia',
				'Annie',
				'Ashe',
				'Blitzcrank',
				'Brand',
				'Caitlyn',
				'Cassiopeia',
				'Cho\'Gath',
				'Corki',
				'Darius',
				'Diana',
				'Dr._Mundo',
				'Draven',
				'Elise',
				'Evelynn',
				'Ezreal',
				'Fiddlesticks',
				'Fiora',
				'Fizz',
				'Galio',
				'Gangplank',
				'Garen',
				'Gragas',
				'Graves',
				'Hecarim',
				'Heimerdinger',
				'Irelia',
				'Janna',
				'Jarvan_IV',
				'Jax',
				'Jayce',
				'Jinx',
				'Karma',
				'Karthus',
				'Kassadin',
				'Katarina',
				'Kayle',
				'Kennen',
				'Kha\'Zix',
				'Kog\'Maw',
				'LeBlanc',
				'Lee_Sin',
				'Leona',
				'Lissandra',
				'Lucian',
				'Lulu',
				'Lux',
				'Malphite',
				'Malzahar',
				'Maokai',
				'Master_Yi',
				'Miss_Fortune',
				'Mordekaiser',
				'Morgana',
				'Nami',
				'Nasus',
				'Nautilus',
				'Nidalee',
				'Nocturne',
				'Nunu',
				'Olaf',
				'Orianna',
				'Pantheon',
				'Poppy',
				'Quinn',
				'Rammus',
				'Renekton',
				'Rengar',
				'Riven',
				'Rumble',
				'Ryze',
				'Sejuani',
				'Shaco',
				'Shen',
				'Shyvana',
				'Singed',
				'Sion',
				'Sivir',
				'Skarner',
				'Sona',
				'Soraka',
				'Swain',
				'Syndra',
				'Talon',
				'Taric',
				'Teemo',
				'Thresh',
				'Tristana',
				'Trundle',
				'Tryndamere',
				'Twisted_Fate',
				'Twitch',
				'Udyr',
				'Urgot',
				'Varus',
				'Vayne',
				'Veigar',
				'Vi',
				'Viktor',
				'Vladimir',
				'Volibear',
				'Warwick',
				'Wukong',
				'Xerath',
				'Xin_Zhao',
				'Yasuo',
				'Yorick',
				'Zac',
				'Zed',
				'Ziggs',
				'Zilean',
				'Zyra',
		);
		
		$functions = array(
				'Attack damage' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setAttackDamage($base);
							$champion->setBonusAttackDamage($bonus);
						}
				),
				'Armor' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setArmor($base);
							$champion->setBonusArmor($bonus);
						}
				),
				'Health' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setHealth($base);
							$champion->setBonusHealth($bonus);
						}
				),
				'Health regen.' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setHealthRegen($base);
							$champion->setBonusHealthRegen($bonus);
						}
				),
				'Mana' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setMana($base);
							$champion->setBonusMana($bonus);
						}
				),
				'Mana regen.' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setManaRegen($base);
							$champion->setBonusManaRegen($bonus);
						}
				),
				'Magic res.' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusFloat,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setMagicResist($base);
							$champion->setBonusMagicResist($bonus);
						}
				),
				'Range' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusRange,
						'setter' => function ($champion, $base, $type) {
							// Initialize the matching attribute
							$champion->setAttackRange($base);
							$champion->setAttackRangeType($type);
						}
				),
				'Mov. speed' => array(
						'patternBase' => $this->patternBaseFloat,
						'patternBonus' => $this->patternBonusEmpty,
						'setter' => function ($champion, $base, $bonus) {
							// Initialize the matching attribute
							$champion->setMoveSpeed($base);
							$champion->setBonusMoveSpeed($bonus);
						}
				),
		);
		
		/*
		 * $aContext = array( 'http' => array( 'proxy' => 'proxy_aeropark:8080', 'request_fulluri' => true, ), ); $cxContext = stream_context_create($aContext);
		 */
		
		// Parse all the champion names
		foreach($championNames as $championName) {
			echo "Processing $championName...\r\n";
			// $html = file_get_contents('http://leagueoflegends.wikia.com/wiki/' . $championName, False, $cxContext);
			$html = file_get_contents('http://leagueoflegends.wikia.com/wiki/' . $championName);
			
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
			$championTable = $DOM->getElementById('champion_info-lower');
			
			$items = $championTable->getElementsByTagName('td');
			
			// Initialize the champion to be persisted
			$champion = new Champion();
			$champion->setName($championName);
			
			for($i = 0; $i < $items->length; $i ++) {
				$node = $items->item($i);
				$value = trim($node->nodeValue);
				
				// Gets the next cell
				if (array_key_exists($value, $functions)) {
					$patternBase = $functions [trim($value)] ['patternBase'];
					$attribute = $this->parseAttribute($items->item($i + 1)->nodeValue, $patternBase, $functions [trim($value)] ['patternBonus']);
					if ($attribute !== FALSE) {
						$functions [trim($value)] ['setter']($champion, $attribute ['base'], $attribute ['bonus']);
						$i ++;
					}
				}
			}
			
			$manager->persist($champion);
			echo "$championName persisted.\r\n";
		}
		
		// On déclenche l'enregistrement
		$manager->flush();
	}
	
	/**
	 * Parse the given attribute according to the pattern.
	 * Return the base value and bonus value of the attribute in an array.
	 * 
	 * @param string $attribute	the attribute to be parsed
	 * @param string $patternBase the pattern to retrieve the base value of the attribute
	 * @param string $patternBonus the pattern to retrieve the bonus value of the attribute
	 * @return associative array with 'base' and 'bonus' keys
	 */
	private function parseAttribute($attribute, $patternBase, $patternBonus) {
		$attribute = trim($attribute);
		
		// Get base number
		if ($attribute != '' && preg_match($patternBase, $attribute, $base) == 1 && preg_match($patternBonus, $attribute, $bonus) == 1) {
			if(!isset($bonus[1]) || $bonus[1] == '') {
				$bonus[1] = NULL;
			}
			return array(
					'base' => $base [0],
					'bonus' => $bonus [1]
			);
		}
		return FALSE;
	}
}