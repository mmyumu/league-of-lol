<?php
// src/Lolol/TeamBundle/DataFixtures/ORM/Competences.php
namespace Lolol\TeamBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Lolol\UserBundle\Entity\User;

class Users implements FixtureInterface {
	public function load(ObjectManager $manager) {
		
		$user = new User();
		$user->setUsername('mmyumu');
		$user->setPassword('mmyumu');
		
		$manager->persist();
		
		$manager->flush();
	}
}