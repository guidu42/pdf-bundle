<?php

namespace App\Repository;

use App\Entity\CustomPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomPage[]    findAll()
 * @method CustomPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomPage::class);
    }

    public function getQbAll(): QueryBuilder
    {
        return $this->createQueryBuilder('cp');
    }
}
