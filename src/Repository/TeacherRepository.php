<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class TeacherRepository extends EntityRepository
{
    public function getTeachersIndexByExternalGuid()
    {
        $qb = $this->createQueryBuilder('t', 't.externalGuid');
        $qb
            ->andWhere(
                $qb->expr()->isNotNull('t.externalGuid')
            )
        ;
        return $qb->getQuery()->getResult();
    }

    public function getTeachersIndexByEmail()
    {
        $qb = $this->createQueryBuilder('t', 't.email');
        return $qb->getQuery()->getResult();
    }
}
