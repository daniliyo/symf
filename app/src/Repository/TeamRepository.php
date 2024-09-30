<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
    * @return Team[] Returns an array of Team objects
    */
    public function findByDivision($value): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.division = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Team[] Returns an array of Team objects
    */
    public function findByDivisionWithStatistics($value): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.division = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

}
