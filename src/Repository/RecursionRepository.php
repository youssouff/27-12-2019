<?php

namespace App\Repository;

use App\Entity\Recursion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Recursion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recursion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recursion[]    findAll()
 * @method Recursion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecursionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recursion::class);
    }

    // /**
    //  * @return Recursion[] Returns an array of Recursion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recursion
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
