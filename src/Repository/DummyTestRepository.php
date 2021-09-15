<?php

namespace App\Repository;

use App\Entity\DummyTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DummyTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method DummyTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method DummyTest[]    findAll()
 * @method DummyTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DummyTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DummyTest::class);
    }

    public function getQbAll()
    {
        return $this->createQueryBuilder('dm');
    }

    // /**
    //  * @return DummyTest[] Returns an array of DummyTest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DummyTest
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
