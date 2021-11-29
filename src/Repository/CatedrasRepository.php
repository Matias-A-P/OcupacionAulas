<?php

namespace App\Repository;

use App\Entity\Catedras;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Catedras|null find($id, $lockMode = null, $lockVersion = null)
 * @method Catedras|null findOneBy(array $criteria, array $orderBy = null)
 * @method Catedras[]    findAll()
 * @method Catedras[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatedrasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Catedras::class);
    }

    // /**
    //  * @return Catedras[] Returns an array of Catedras objects
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
    public function findOneBySomeField($value): ?Catedras
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
