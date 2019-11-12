<?php

namespace App\Repository;

use App\Entity\OrderHystory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OrderHystory|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderHystory|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderHystory[]    findAll()
 * @method OrderHystory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderHystoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderHystory::class);
    }

    // /**
    //  * @return OrderHystory[] Returns an array of OrderHystory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderHystory
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
