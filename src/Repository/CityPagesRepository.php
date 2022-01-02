<?php

namespace App\Repository;

use App\Entity\CityPages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CityPages|null find($id, $lockMode = null, $lockVersion = null)
 * @method CityPages|null findOneBy(array $criteria, array $orderBy = null)
 * @method CityPages[]    findAll()
 * @method CityPages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityPagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CityPages::class);
    }

    // /**
    //  * @return CityPages[] Returns an array of CityPages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CityPages
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
