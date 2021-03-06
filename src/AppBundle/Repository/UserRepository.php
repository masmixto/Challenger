<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByUsername($username)
    {
        $qb=$this->createQueryBuilder('o');
        return $qb
            ->where('o.username LIKE :username')
            ->setParameter('username', '%'.$username.'%')
            ->getQuery()
            ->getResult();
    }

}
