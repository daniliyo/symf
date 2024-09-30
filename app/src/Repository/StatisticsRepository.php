<?php

namespace App\Repository;

use App\Entity\Statistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Enum\DivisionEnum;

/**
 * @extends ServiceEntityRepository<Statistics>
 */
class StatisticsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistics::class);
    }

    /**
    * @return Team[] Returns an array of Statistics objects with condition the N best teams
    */
    public function findByDivisionTheBest($value, $count): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.division = :val')
            ->setParameter('val', $value)
            ->addOrderBy('s.point', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByDivision($value): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.division = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getAllPlayOff(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.division = :val')
            ->setParameter('val', DivisionEnum::PLAY_OFF->value)
            ->addOrderBy('s.point', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
