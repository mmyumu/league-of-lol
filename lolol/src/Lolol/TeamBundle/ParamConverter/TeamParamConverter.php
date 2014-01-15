<?php

namespace Lolol\TeamBundle\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManager;
use Lolol\TeamBundle\Entity\Team;

class TeamParamConverter implements ParamConverterInterface {
	private $em;
	public function __construct(EntityManager $em) {
		$this->em = $em;
	}
	public function apply(Request $request, ConfigurationInterface $configuration) {
		// Get the route parameter
		$id = $request->attributes->get('id');
		
		// Build the query
		$qb = $this->em->createQueryBuilder();	
		$qb->select('t')->from('LololTeamBundle:Team', 't')->where('t.id = :id')->setParameter('id', $id);
		
		// Return the found team or null
		try {
			$team = $qb->getQuery()->getSingleResult();
		}
		catch(\Doctrine\ORM\NoResultException $e) {
			$team = null;
		}
		$param = $configuration->getName();
		$request->attributes->set($param, $team);
		
		return true;
	}
	public function supports(ConfigurationInterface $configuration) {
		return "Lolol\TeamBundle\Entity\Team" === $configuration->getClass();
	}
}