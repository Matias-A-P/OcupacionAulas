<?php

namespace App\Repository;

use App\Entity\Ocupacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ocupacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ocupacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ocupacion[]    findAll()
 * @method Ocupacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OcupacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ocupacion::class);
    }

    public function getOcupacionesDia(string $dia, int $aula)
    {
        $entm = $this->getEntityManager();
        $query = $entm->createQuery(
            'select o from App\Entity\Ocupacion o where o.id_aula=:a and o.fecha=:d order by o.id_aula ASC, o.hora_inicio ASC')
        ->setParameter('a', $aula)
        ->setParameter('d', new \DateTime($dia));

        $result = $query->getResult();

        return $result;
    }

    public function getOcupacionesSemana(string $dia1, $dia2, int $aula)
    {
        $entm = $this->getEntityManager();
        $query = $entm->createQuery(
            'select o from App\Entity\Ocupacion o where o.id_aula=:a and o.fecha>=:d1 and o.fecha<:d2 order by o.id_aula ASC, o.fecha ASC'
        )->setParameter('a', $aula)
            ->setParameter('d1', new \DateTime($dia1))
            ->setParameter('d2', new \DateTime($dia2));
        $result = $query->getResult();

        return $result;
    }

    public function isOcupado(int $aula, string $dia, $hora_inicio, $hora_fin, int $id): bool
    {
        $entm = $this->getEntityManager();
        $query = $entm->createQuery(
            'select o from App\Entity\Ocupacion o where o.id_aula=:a and o.fecha=:d1 and (((:hi >= o.hora_inicio and :hi < o.hora_fin) or (:hf > o.hora_inicio and :hf <= o.hora_fin)) or ((o.hora_inicio >= :hi and o.hora_inicio < :hf) or (o.hora_fin > :hi and o.hora_fin <= :hf))) and id<>:id')
        ->setParameter('a', $aula)
        ->setParameter('d1', $dia)
        ->setParameter('hi', $hora_inicio)
        ->setParameter('hf', $hora_fin)
        ->setParameter('id', $id);

        return (count( $query->getResult())>0); 
    }


    // /**
    //  * @return Ocupacion[] Returns an array of Ocupacion objects
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
    public function findOneBySomeField($value): ?Ocupacion
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
