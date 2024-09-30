<?php

namespace App\Repository;

use App\Entity\Result;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Enum\DivisionEnum;

/**
 * @extends ServiceEntityRepository<Result>
 */
class ResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Result::class);
    }

    public function findByDivision($value): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.division = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getPlayOffResults(): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.division = :val')
            ->setParameter('val', DivisionEnum::PLAY_OFF->value)
            ->getQuery()
            ->getResult()
        ;
    }

}
