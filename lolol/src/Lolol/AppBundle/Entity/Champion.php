<?php

namespace Lolol\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lolol\BattleBundle\Entity\Injury as Injury;

/**
 * Champion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Lolol\AppBundle\Entity\ChampionRepository")
 */
class Champion {
	/**
	 *
	 * @var integer @ORM\Column(name="id", type="integer")
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 *
	 * @var string @ORM\Column(name="name", type="string", length=255)
	 */
	private $name;
	
	/**
	 *
	 * @var string @ORM\Column(name="subName", type="string", length=255)
	 */
	private $subName;
	
	/**
	 *
	 * @var string @ORM\Column(name="imgName", type="string", length=255)
	 */
	private $imgName;
	
	/**
	 *
	 * @var float @ORM\Column(name="attackDamage", type="float")
	 */
	private $attackDamage;
	
	/**
	 *
	 * @var float @ORM\Column(name="bonusAttackDamage", type="float")
	 */
	private $bonusAttackDamage;
	
	/**
	 *
	 * @var float @ORM\Column(name="armor", type="float")
	 */
	private $armor;
	
	/**
	 *
	 * @var float @ORM\Column(name="bonusArmor", type="float")
	 */
	private $bonusArmor;
	
	/**
	 *
	 * @var float @ORM\Column(name="health", type="float")
	 */
	private $health;
	
	/**
	 *
	 * @var float @ORM\Column(name="bonusHealth", type="float")
	 */
	private $bonusHealth;
	
	/**
	 *
	 * @var float @ORM\Column(name="healthRegen", type="float")
	 */
	private $healthRegen;
	
	/**
	 *
	 * @var float @ORM\Column(name="bonusHealthRegen", type="float")
	 */
	private $bonusHealthRegen;
	
	/**
	 *
	 * @var float @ORM\Column(name="mana", type="float", nullable=true)
	 */
	private $mana;
	
	/**
	 *
	 * @var float @ORM\Column(name="bonusMana", type="float", nullable=true)
	 */
	private $bonusMana;
	
	/**
	 *
	 * @var float @ORM\Column(name="manaRegen", type="float", nullable=true)
	 */
	private $manaRegen;
	
	/**
	 *
	 * @var float @ORM\Column(name="bonusManaRegen", type="float", nullable=true)
	 */
	private $bonusManaRegen;
	
	/**
	 *
	 * @var float @ORM\Column(name="magicResist", type="float")
	 */
	private $magicResist;
	
	/**
	 *
	 * @var float @ORM\Column(name="bonusMagicResist", type="float")
	 */
	private $bonusMagicResist;
	
	/**
	 *
	 * @var float @ORM\Column(name="attackRange", type="float")
	 */
	private $attackRange;
	
	/**
	 *
	 * @var float @ORM\Column(name="attackRangeType", type="string", nullable=true)
	 */
	private $attackRangeType;
	
	/**
	 *
	 * @var float @ORM\Column(name="moveSpeed", type="float")
	 */
	private $moveSpeed;
	
	/**
	 *
	 * @var float @ORM\Column(name="bonusMoveSpeed", type="float", nullable=true)
	 */
	private $bonusMoveSpeed;
	
	/**
	 *
	 * @var float @ORM\Column(name="attackSpeed", type="float")
	 */
	private $attackSpeed;
	
	/**
	 *
	 * @var float @ORM\Column(name="bonusAttackSpeed", type="float", nullable=true)
	 */
	private $bonusAttackSpeed;
	
	/**
	 * Current health during battle
	 *
	 * @var float
	 */
	private $currentHealth;
	
	/**
	 * Time of last attack
	 *
	 * @var float
	 */
	private $lastAttackTime;
	
	// TODO MLA: Add the attack power, difficulty and stuff and draw lines (like wiki)
	// TODO MLA: Add the role like primary: tank secondary: mage
	public function __construct() {
	}
	
	/**
	 * Prepare the champion for the battle.
	 */
	public function prepare() {
		$this->currentHealth = $this->health;
	}
	
	/**
	 * Function isAlive()
	 * Indique si le Champion est encore en vie
	 *
	 * @return boolean Champion est-il encore en vie ? Vrai s'il est vivant, faux sinon
	 */
	public function isAlive(&$logs) {
		if ($this->currentHealth > 0) {
			$logs[]['text'] = 'Le Champion ' . $this->getName() . ' est encore en vie';
		}
		else {
			$logs[]['text'] = 'Le Champion ' . $this->getName() . ' est KO';
		}
		return ($this->currentHealth > 0);
	}
	
	/**
	 * Function play()
	 * Permet de faire jouer le Champion, s'il peut jouer
	 * Retourne soit une blessure à infliger, soit false s'il n'a aucun cooldown prêt
	 *
	 * @param	float	$p_time	Le moment dans la partie
	 * @return	IInjury	La blessure à infliger, ou false sinon
	 */
	public function play($time = 0, &$logs) {
		// On n'a rien fait, jusqu'à preuve du contraire
		$action = false;
		// Ici l'intelligence du joueur entre en oeuvre
		// On va prendre en compte ses choix de priorités pour déterminer
		// ce que fait le Champion selon ses cooldowns
		/**
		 * pour l'instant, seule l'attaque par défaut est utilisée
		 */
		if ($this->isAlive($logs)) {
			$action = $this->defaultAttack($time, $logs);
		}
		return $action;
	}
	
	/**
	 * Function defaultAttack()
	 * Permet d'exécuter une attaque par défaut
	 * Retourne la blessure à infliger à l'autre équipe
	 *
	 * @param	float	$p_time	Le moment dans la partie
	 * @return	IInjury	La blessure à infliger
	 */
	public function defaultAttack($time = 0, &$logs) {
		// Par défaut, l'attaque est en cooldown
		$injury = false;
		$logs[]['text'] = 'Le Champion ' . $this->getName() . ' essaie d\'utiliser son attaque par défaut au round ' . $time;
		// Vérification du temps écoulé depuis la dernière attaque de ce type
		$up = $this->lastAttackTime + (1 / $this->attackSpeed) * 2;
		if ($time >= $up) {
			// Attaque disponible
			$logs[]['text'] = 'Le Champion ' . $this->getName() . ' fait une attaque par défaut pour ' . $this->attackDamage . ' dégâts';
			$injury = new Injury($this->attackDamage);
			$this->lastAttackTime = $time;
		}
		else {
			// Cooldown
			$logs[]['text'] = 'Le Champion ' . $this->getName() . ' a son attaque par défaut en cooldown jusqu\'au round ' . ceil($up);
		}
		return $injury;
	}
	
	/**
	 * Function setInjury()
	 * Inflige la blessure passée en paramètre au Champion
	 *
	 * @param	IInjury	p_injury	La blessure à infliger
	 */
	public function setInjury(Injury $injury, &$logs) {
		$logs[]['text'] = 'Le Champion ' . $this->getName() . ' subit une blessure de ' . $injury->getNormalAmount() . ' HP';
		$logs[]['text'] = 'Le Champion ' . $this->getName() . ' absorbe ' . $this->armor . ' dégâts grâce à son armure';
		$this->currentHealth -= ($injury->getNormalAmount() - $this->armor);
		$logs[]['text'] = 'Il reste au Champion ' . $this->getName() . ' ' . $this->currentHealth . ' HP';
	}
	
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set name
	 *
	 * @param string $name        	
	 * @return Champion
	 */
	public function setName($name) {
		$this->name = $name;
		
		return $this;
	}
	
	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Set attackDamage
	 *
	 * @param float $attackDamage        	
	 * @return Champion
	 */
	public function setAttackDamage($attackDamage) {
		$this->attackDamage = $attackDamage;
		
		return $this;
	}
	
	/**
	 * Get attackDamage
	 *
	 * @return float
	 */
	public function getAttackDamage() {
		return $this->attackDamage;
	}
	
	/**
	 * Set armor
	 *
	 * @param float $armor        	
	 * @return Champion
	 */
	public function setArmor($armor) {
		$this->armor = $armor;
		
		return $this;
	}
	
	/**
	 * Get armor
	 *
	 * @return float
	 */
	public function getArmor() {
		return $this->armor;
	}
	
	/**
	 * Set health
	 *
	 * @param float $health        	
	 * @return Champion
	 */
	public function setHealth($health) {
		$this->health = $health;
		
		return $this;
	}
	
	/**
	 * Get health
	 *
	 * @return float
	 */
	public function getHealth() {
		return $this->health;
	}
	
	/**
	 * Set bonusAttackDamage
	 *
	 * @param float $bonusAttackDamage        	
	 * @return Champion
	 */
	public function setBonusAttackDamage($bonusAttackDamage) {
		$this->bonusAttackDamage = $bonusAttackDamage;
		
		return $this;
	}
	
	/**
	 * Get bonusAttackDamage
	 *
	 * @return float
	 */
	public function getBonusAttackDamage() {
		return $this->bonusAttackDamage;
	}
	
	/**
	 * Set bonusArmor
	 *
	 * @param float $bonusArmor        	
	 * @return Champion
	 */
	public function setBonusArmor($bonusArmor) {
		$this->bonusArmor = $bonusArmor;
		
		return $this;
	}
	
	/**
	 * Get bonusArmor
	 *
	 * @return float
	 */
	public function getBonusArmor() {
		return $this->bonusArmor;
	}
	
	/**
	 * Set bonusHealth
	 *
	 * @param float $bonusHealth        	
	 * @return Champion
	 */
	public function setBonusHealth($bonusHealth) {
		$this->bonusHealth = $bonusHealth;
		
		return $this;
	}
	
	/**
	 * Get bonusHealth
	 *
	 * @return float
	 */
	public function getBonusHealth() {
		return $this->bonusHealth;
	}
	
	/**
	 * Set healthRegen
	 *
	 * @param float $healthRegen        	
	 * @return Champion
	 */
	public function setHealthRegen($healthRegen) {
		$this->healthRegen = $healthRegen;
		
		return $this;
	}
	
	/**
	 * Get healthRegen
	 *
	 * @return float
	 */
	public function getHealthRegen() {
		return $this->healthRegen;
	}
	
	/**
	 * Set bonusHealthRegen
	 *
	 * @param float $bonusHealthRegen        	
	 * @return Champion
	 */
	public function setBonusHealthRegen($bonusHealthRegen) {
		$this->bonusHealthRegen = $bonusHealthRegen;
		
		return $this;
	}
	
	/**
	 * Get bonusHealthRegen
	 *
	 * @return float
	 */
	public function getBonusHealthRegen() {
		return $this->bonusHealthRegen;
	}
	
	/**
	 * Set magicResist
	 *
	 * @param float $magicResist        	
	 * @return Champion
	 */
	public function setMagicResist($magicResist) {
		$this->magicResist = $magicResist;
		
		return $this;
	}
	
	/**
	 * Get magicResist
	 *
	 * @return float
	 */
	public function getMagicResist() {
		return $this->magicResist;
	}
	
	/**
	 * Set bonusMagicResist
	 *
	 * @param float $bonusMagicResist        	
	 * @return Champion
	 */
	public function setBonusMagicResist($bonusMagicResist) {
		$this->bonusMagicResist = $bonusMagicResist;
		
		return $this;
	}
	
	/**
	 * Get bonusMagicResist
	 *
	 * @return float
	 */
	public function getBonusMagicResist() {
		return $this->bonusMagicResist;
	}
	
	/**
	 * Set mana
	 *
	 * @param float $mana        	
	 * @return Champion
	 */
	public function setMana($mana) {
		$this->mana = $mana;
		
		return $this;
	}
	
	/**
	 * Get mana
	 *
	 * @return float
	 */
	public function getMana() {
		return $this->mana;
	}
	
	/**
	 * Set bonusMana
	 *
	 * @param float $bonusMana        	
	 * @return Champion
	 */
	public function setBonusMana($bonusMana) {
		$this->bonusMana = $bonusMana;
		
		return $this;
	}
	
	/**
	 * Get bonusMana
	 *
	 * @return float
	 */
	public function getBonusMana() {
		return $this->bonusMana;
	}
	
	/**
	 * Set manaRegen
	 *
	 * @param float $manaRegen        	
	 * @return Champion
	 */
	public function setManaRegen($manaRegen) {
		$this->manaRegen = $manaRegen;
		
		return $this;
	}
	
	/**
	 * Get manaRegen
	 *
	 * @return float
	 */
	public function getManaRegen() {
		return $this->manaRegen;
	}
	
	/**
	 * Set bonusManaRegen
	 *
	 * @param float $bonusManaRegen        	
	 * @return Champion
	 */
	public function setBonusManaRegen($bonusManaRegen) {
		$this->bonusManaRegen = $bonusManaRegen;
		
		return $this;
	}
	
	/**
	 * Get bonusManaRegen
	 *
	 * @return float
	 */
	public function getBonusManaRegen() {
		return $this->bonusManaRegen;
	}
	
	/**
	 * Set rangeType
	 *
	 * @param string $rangeType        	
	 * @return Champion
	 */
	public function setRangeType($rangeType) {
		$this->rangeType = $rangeType;
		
		return $this;
	}
	
	/**
	 * Get rangeType
	 *
	 * @return string
	 */
	public function getRangeType() {
		return $this->rangeType;
	}
	
	/**
	 * Set moveSpeed
	 *
	 * @param float $moveSpeed        	
	 * @return Champion
	 */
	public function setMoveSpeed($moveSpeed) {
		$this->moveSpeed = $moveSpeed;
		
		return $this;
	}
	
	/**
	 * Get moveSpeed
	 *
	 * @return float
	 */
	public function getMoveSpeed() {
		return $this->moveSpeed;
	}
	
	/**
	 * Set bonusMoveSpeed
	 *
	 * @param float $bonusMoveSpeed        	
	 * @return Champion
	 */
	public function setBonusMoveSpeed($bonusMoveSpeed) {
		$this->bonusMoveSpeed = $bonusMoveSpeed;
		
		return $this;
	}
	
	/**
	 * Get bonusMoveSpeed
	 *
	 * @return float
	 */
	public function getBonusMoveSpeed() {
		return $this->bonusMoveSpeed;
	}
	
	/**
	 * Set attackRange
	 *
	 * @param float $attackRange        	
	 * @return Champion
	 */
	public function setAttackRange($attackRange) {
		$this->attackRange = $attackRange;
		
		return $this;
	}
	
	/**
	 * Get attackRange
	 *
	 * @return float
	 */
	public function getAttackRange() {
		return $this->attackRange;
	}
	
	/**
	 * Set attackRangeType
	 *
	 * @param string $attackRangeType        	
	 * @return Champion
	 */
	public function setAttackRangeType($attackRangeType) {
		$this->attackRangeType = $attackRangeType;
		
		return $this;
	}
	
	/**
	 * Get attackRangeType
	 *
	 * @return string
	 */
	public function getAttackRangeType() {
		return $this->attackRangeType;
	}
	
	/**
	 * Set imgName
	 *
	 * @param string $imgName        	
	 * @return Champion
	 */
	public function setImgName($imgName) {
		$this->imgName = $imgName;
		
		return $this;
	}
	
	/**
	 * Get imgName
	 *
	 * @return string
	 */
	public function getImgName() {
		return $this->imgName;
	}
	
	/**
	 * Set attackSpeed
	 *
	 * @param float $attackSpeed        	
	 * @return Champion
	 */
	public function setAttackSpeed($attackSpeed) {
		$this->attackSpeed = $attackSpeed;
		
		return $this;
	}
	
	/**
	 * Get attackSpeed
	 *
	 * @return float
	 */
	public function getAttackSpeed() {
		return $this->attackSpeed;
	}
	
	/**
	 * Set bonusAttackSpeed
	 *
	 * @param float $bonusAttackSpeed        	
	 * @return Champion
	 */
	public function setBonusAttackSpeed($bonusAttackSpeed) {
		$this->bonusAttackSpeed = $bonusAttackSpeed;
		
		return $this;
	}
	
	/**
	 * Get bonusAttackSpeed
	 *
	 * @return float
	 */
	public function getBonusAttackSpeed() {
		return $this->bonusAttackSpeed;
	}
	
	/**
	 * Set subName
	 *
	 * @param string $subName        	
	 * @return Champion
	 */
	public function setSubName($subName) {
		$this->subName = $subName;
		
		return $this;
	}
	
	/**
	 * Get subName
	 *
	 * @return string
	 */
	public function getSubName() {
		return $this->subName;
	}
}
