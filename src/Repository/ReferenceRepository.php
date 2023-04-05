<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ReferenceRepository extends EntityRepository
{
    public function getReferencesWithIndexByPathFile() {
        $qb = $this->createQueryBuilder('r', 'r.pathFile');
        return $qb->getQuery()->getResult();
    }
}
