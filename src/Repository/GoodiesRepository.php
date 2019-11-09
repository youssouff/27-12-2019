<?php

namespace App\Repository;

use App\Entity\Goodies;
use Doctrine\ORM\Query;
use App\Entity\GoodiesSearch;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Goodies|null find($id, $lockMode = null, $lockVersion = null)
 * @method Goodies|null findOneBy(array $criteria, array $orderBy = null)
 * @method Goodies[]    findAll()
 * @method Goodies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoodiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Goodies::class);
    }


    /**
     * @return Query
     */
    public function findAllQuery(GoodiesSearch $search): Query
    {
        $query = $this
            ->createQueryBuilder('a')
        ;

        if($search->getMaxPrice() || $search->getMaxPrice() === 0){
            $query = $query
                ->where('a.price <= :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }

        if($search->getCategory()){
            $query = $query
                ->andWhere('a.category = :category')
                ->setParameter('category', $search->getCategory());
        }

        if($search->getOrderBy()){
            $query = $query->orderBy('a.'.$search->getOrderBy(), $search->getOrderType());
        }
        
        return $query->getQuery();
        
  
    }
    // /**
    //  * @return Goodies[] Returns an array of Goodies objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Goodies
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
