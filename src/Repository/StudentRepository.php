<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class StudentRepository extends EntityRepository
{
    public function getStudentByGradeBookIdOrGuid($gradeBookIdOrExternalGuid) {
        $qb = $this->createQueryBuilder('s');

        if (is_integer($gradeBookIdOrExternalGuid)) {
            $qb->andWhere($qb->expr()->eq('s.gradeBookId', ':gradeBookIdOrExternalGuid'));
        }
        else {
            $qb->andWhere($qb->expr()->eq('s.externalGuid', ':gradeBookIdOrExternalGuid'));
        }
        $qb->setParameter('gradeBookIdOrExternalGuid', $gradeBookIdOrExternalGuid);

        try {
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    public function getStudentsIndexByExternalGuid()
    {
        $qb = $this->createQueryBuilder('s', 's.externalGuid');
        $qb
            ->andWhere(
                $qb->expr()->isNotNull('s.externalGuid')
            )
        ;
        return $qb->getQuery()->getResult();
    }
}
