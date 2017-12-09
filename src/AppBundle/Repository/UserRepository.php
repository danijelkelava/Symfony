<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository
{
	/**
     * @return User[]
     */
	public function findOneByUsernameOrEmail($username)
	{
	    return $this->createQueryBuilder('user')
	        ->andWhere('user.email = :email')
	        ->setParameter('email', $username)
	        ->getQuery()
	        ->getOneOrNullResult()
	    ;
	}

    /**
     * @return User[]
     */
	public function findUsers($word)
	{
		return $this->createQueryBuilder('user')
	        ->andWhere('user.email = :email')
	        ->setParameter('email', $word)
	        ->getQuery()
	        ->getOneOrNullResult()
	    ;
	}
}