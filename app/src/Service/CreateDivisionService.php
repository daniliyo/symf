<?php

namespace App\Service;

use App\Enum\DivisionEnum;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Team;
use App\Entity\Result;
use App\Entity\Statistics;
use App\Repository\TeamRepository;

class CreateDivisionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TeamRepository $teamRep,
        private CreateResultService $resultService,
    ) {}

    public function create(DivisionEnum $division){        
        $this->generateTeams($division);
        
        $teams = $this->teamRep->findByDivision($division->value);
        
        $this->resultService->createResulstForDivision($teams);
        
        return true;
    }

    protected function generateTeams(DivisionEnum $division): void
    {
        $teams = $division->teams();
        foreach($teams as $v)
        {       
            $team = new Team();
            $team->setName($v);
            $team->setDivision($division->value);

            $this->entityManager->persist($team);
        }
        $this->entityManager->flush();
    }
}