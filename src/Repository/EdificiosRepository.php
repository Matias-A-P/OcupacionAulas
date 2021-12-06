<?php

namespace App\Repository;

use App\Entity\Edificios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Edificios|null find($id, $lockMode = null, $lockVersion = null)
 * @method Edificios|null findOneBy(array $criteria, array $orderBy = null)
 * @method Edificios[]    findAll()
 * @method Edificios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EdificiosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Edificios::class);
    }

    // /**
    //  * @return Edificios[] Returns an array of Edificios objects
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
    public function findOneBySomeField($value): ?Edificios
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
