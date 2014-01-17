<?php

namespace Lolol\BattleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BattleController extends Controller {
	public function indexAction() {
		return $this->render('LololBattleBundle:Battle:index.html.twig');
	}
	
	public function attackAction() {
		return $this->render('LololBattleBundle:Battle:attack.html.twig');
	}
	
	public function defenseAction() {
		return $this->render('LololBattleBundle:Battle:defense.html.twig');
	}
}
