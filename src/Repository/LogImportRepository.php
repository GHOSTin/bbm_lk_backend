<?php

namespace App\Repository;

use App\Entity\LogImport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LogImport|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogImport|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogImport[]    findAll()
 * @method LogImport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogImportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogImport::class);
    }

    // /**
    //  * @return LogImport[] Returns an array of LogImport objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LogImport
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
