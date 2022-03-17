<?php

namespace App\Repository;

use App\Entity\EdificiosPisos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EdificiosPisos|null find($id, $lockMode = null, $lockVersion = null)
 * @method EdificiosPisos|null findOneBy(array $criteria, array $orderBy = null)
 * @method EdificiosPisos[]    findAll()
 * @method EdificiosPisos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EdificiosPisosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EdificiosPisos::class);
    }

    // /**
    //  * @return EdificiosPisos[] Returns an array of EdificiosPisos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EdificiosPisos
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
