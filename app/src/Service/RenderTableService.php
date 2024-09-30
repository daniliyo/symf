<?php

namespace App\Service;

use App\Repository\StatisticsRepository;
use App\Repository\ResultRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\DivisionEnum;


class RenderTableService 
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private StatisticsRepository $statRep,
        private ResultRepository $resultRep,
    ) {}

    public function getData(){

        $divisionA = $this->getDivisionRender(DivisionEnum::DIVISION_A);
        $divisionB = $this->getDivisionRender(DivisionEnum::DIVISION_B);

        $playOff = $this->getPlayOffRender();

        $leaderTable = $this->getLeaderTable();
        
        return [
            'divisionA' => $divisionA,
            'divisionB' => $divisionB,
            'playOffQuater' => array_slice($playOff, 0, 4),
            'playOffSemi' => array_slice($playOff, 4, 2),
            'playOffFinal' => array_slice($playOff, 6, 1),
            'leaderTable' => $leaderTable,
        ];
    }

    protected function getLeaderTable(): array
    {
        $listTeams = $this->statRep->getAllPlayOff();

        $data = [];
        $i = 1;

        foreach($listTeams as $k=>$v)
        {
            $data[$k]['name'] = ($v->getTeam())->getName();
            $data[$k]['point'] = $v->getPoint();
            $data[$k]['position'] = $i;
            $i++;
        }
        return $data;
    }

    protected function getPlayOffRender(): array
    {
        $data = [];
        $resultsPlayOff = $this->resultRep->getPlayOffResults();

        foreach($resultsPlayOff as $v)
        {
            $first_team = $v->getFirstTeam();
            $second_team = $v->getSecondTeam();

            $data[] = [
                'first_team' => $first_team->getName(),
                'first_team_division' => $first_team->getDivision(),
                'second_team' => $second_team->getName(),
                'second_team_division' => $second_team->getDivision(),
                'score' => $v->getFirstTeamGoal().':'.$v->getSecondTeamGoal(),
            ];
        }

        return $data;
    }

    protected function getDivisionRender(DivisionEnum $division)
    {
        $result = [];
        $resultsByDivision = $this->resultRep->findByDivision($division->value);
        foreach($resultsByDivision as $k=>$v){
            $first_team = ($v->getFirstTeam())->getName();
            $second_team = ($v->getSecondTeam())->getName();
            
            if(!isset($result[$first_team][$second_team])){
                $result[$first_team][$second_team] = $v->getFirstTeamGoal().':'.$v->getSecondTeamGoal(); 
            }
        }

        $statsByDivision = $this->statRep->findByDivision($division->value);
        $data = [];
        $iData = 0;
        
        $i = 0;
        foreach($statsByDivision as $k => $v){
            
            if($i == 0){
                $data[$iData][] = ' ';
            }
            
            $data[$iData][] = ($v->getTeam())->getName();
            
            $i++;            
        }

        $iData++;
        
        foreach($statsByDivision as $k => $v){
            $first_team = ($v->getTeam())->getName();
            $data[$iData][] = $first_team;
            foreach($statsByDivision as $k1 => $v1){
                $second_team = ($v1->getTeam())->getName();
                if($first_team != $second_team){                    
                    $data[$iData][] = isset($result[$first_team][$second_team]) ? $result[$first_team][$second_team] : $result[$second_team][$first_team];
                } else {
                    $data[$iData][] = 'X';
                }
            }
            $data[$iData][] = $v->getPoint();
            $iData++;
        }       

        return $data;
    }
}