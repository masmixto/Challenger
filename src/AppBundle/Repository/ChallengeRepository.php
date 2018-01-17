<?php

namespace AppBundle\Repository;

/**
 * ChallengeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChallengeRepository extends \Doctrine\ORM\EntityRepository
{
    public function challangerUser($userId){

        $qb=$this->createQueryBuilder('o');
        return $qb
            ->where('o.userid=:userid')
            ->setParameter('userid', $userId)
            ->getQuery()
            ->getResult();
    }
}