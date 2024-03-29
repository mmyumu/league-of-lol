<?php

namespace Lolol\TeamBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Lolol\UserBundle\Entity\User;

/**
 * TeamRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TeamRepository extends EntityRepository {
	public function findOpponents(User $user) {
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('t')
		->from('LololTeamBundle:Team', 't')
		->leftJoin('t.user', 'u')
		->addSelect('u')
		->where('t.defender = 1')
		->andWhere('u.id <> :userId')
		->setParameter('userId', $user->getId());
		
		return $qb->getQuery()
		->getResult();
	}
	
	public function findOneByTeamWithChampions(Team $team) {
		return $this->findOneByIdWithChampions($team->getId());
	}
	
	public function findOneByIdWithChampions($id, $orderBy = 'ct.position') {
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('t')
		->from('LololTeamBundle:Team', 't')
		->leftJoin('t.championsTeam', 'ct')
		->addSelect('ct')
		->leftJoin('ct.champion', 'c')
		->addSelect('c')
		->andWhere('t.id = :teamId')
		->orderBy($orderBy, 'ASC')
		->setParameter('teamId', $id);
		
		return $qb->getQuery()
		->getSingleResult();
	}
}
