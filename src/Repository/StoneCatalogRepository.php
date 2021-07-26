<?php

namespace App\Repository;

use App\Entity\StoneCatalog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StoneCatalog|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoneCatalog|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoneCatalog[]    findAll()
 * @method StoneCatalog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoneCatalogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoneCatalog::class);
    }

    // /**
    //  * @return StoneCatalog[] Returns an array of StoneCatalog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StoneCatalog
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
