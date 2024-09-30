<?php

namespace App\Service;

use App\Repository\StatisticsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\DivisionEnum;

class CreatePlayOffService 
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private StatisticsRepository $statRep,
        private CreateResultService $resultService,
    ) {}

    public function create()
    {
        $teams = $this->statRep->findByDivisionTheBest(DivisionEnum::DIVISION_A, 4);
        foreach($teams as $team){
            $teamsA[] = $team->getTeam();
        }

        $teams = $this->statRep->findByDivisionTheBest(DivisionEnum::DIVISION_B, 4);
        foreach($teams as $team){
            $teamsB[] = $team->getTeam();
        }

        $this->resultService->createResultsForPlayOff($teamsA, $teamsB);
    }
}