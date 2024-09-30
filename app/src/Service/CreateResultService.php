<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Result;
use App\Entity\Team;
use App\Entity\Statistics;
use App\Enum\DivisionEnum;

class CreateResultService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        protected array $statistics_arr = [],
    ) {}

    public function createResulstForDivision(array $teams)
    {
        $this->statistics_arr = $this->setStartStatistics($teams);

        $copy_teams = $teams;

        do {
            $current_team = array_shift($teams);
            
            array_map(function($second_team) use ($current_team, &$statistics_arr) {
                
                $this->createResult(current_team: $current_team, second_team: $second_team, draw: true);

            }, $teams);

        } while (count($teams) > 1);

        $this->entityManager->flush();

        $this->saveStatistics($copy_teams, $this->statistics_arr);
    }

    public function createResultsForPlayOff(array $teamsA, array $teamsB)
    {
        echo DivisionEnum::PLAY_OFF->value;
        $this->statistics_arr = $this->setStartStatistics(array_merge($teamsA, $teamsB), DivisionEnum::PLAY_OFF);

        $playOffTeamArr = [];

        foreach($teamsA as $k=>$current_team)
        {
            $second_team = $teamsB[$k];

            $this->createResult(current_team: $current_team, second_team: $second_team, draw: false);
            
            $winner = $this->statistics_arr[$current_team->getName()]['point'] > $this->statistics_arr[$second_team->getName()]['point'] ? $current_team : $second_team;
            $playOffTeamArr[$winner->getName()] = $winner;            
        }

        do {
            $knockout_arr = array_chunk($playOffTeamArr, 2, true);
            foreach($knockout_arr as $k=>$v)
            {    
                $first_team = array_shift($v);
                $second_team = array_shift($v);

                $this->createResult(current_team: $first_team, second_team: $second_team, draw: false);
             
                $looser = $this->statistics_arr[$first_team->getName()]['point'] < $this->statistics_arr[$second_team->getName()]['point'] ? $first_team : $second_team;
                unset($playOffTeamArr[$looser->getName()]);
            }
        } while (count($playOffTeamArr) > 1);

        $this->entityManager->flush();

        $this->saveStatistics(array_merge($teamsA, $teamsB), $this->statistics_arr);
    }

    public function createResult($current_team, $second_team, bool $draw = true)
    {
        list($first_team_goal, $second_team_goal) = static::getGoals($draw);

        list($first_team_point, $second_team_point) = static::getPoints($first_team_goal, $second_team_goal);
        
        $division = $draw ? $current_team->getDivision() : DivisionEnum::PLAY_OFF->value;
        
        $result = new Result();
        $result->setFirstTeam($current_team);
        $result->setSecondTeam($second_team);
        $result->setFirstTeamGoal($first_team_goal);
        $result->setSecondTeamGoal($second_team_goal);
        $result->setFirstTeamPoint($first_team_point);
        $result->setSecondTeamPoint($second_team_point);
        $result->setDivision($division);
        
        $this->statistics_arr[$current_team->getName()]['goal'] += $first_team_goal;
        $this->statistics_arr[$current_team->getName()]['point'] += $first_team_point;
        
        $this->statistics_arr[$second_team->getName()]['goal'] += $second_team_goal;
        $this->statistics_arr[$second_team->getName()]['point'] += $second_team_point;
        
        $this->entityManager->persist($result);
    }

    protected function setStartStatistics(array $teams, DivisionEnum $division = null)
    {
        foreach($teams as $team){
            $statistics_arr[$team->getName()]['goal'] = 0;
            $statistics_arr[$team->getName()]['point'] = 0;
            $statistics_arr[$team->getName()]['division'] = is_null($division) ? $team->getDivision() : $division->value;
        echo '!';
        echo $statistics_arr[$team->getName()]['division'];
        }

        return $statistics_arr;
    }

    protected function saveStatistics(array $teams, array $statistics_arr): void
    {
        foreach($teams as $team){
            $current_statistics = $statistics_arr[$team->getName()];

            $statistics = new Statistics();
            $statistics->setTeam($team);
            $statistics->setGoal($current_statistics['goal']);
            $statistics->setPoint($current_statistics['point']);
            $statistics->setDivision($current_statistics['division']);
            $this->entityManager->persist($statistics);
        }

        $this->entityManager->flush();

    }

    public static function getGoals(bool $isDraw = true){
        $result['first_team_goal'] = rand(0,10);
        $result['second_team_goal'] = rand(0,10);

        if(!$isDraw && $result['first_team_goal'] == $result['second_team_goal']) 
        {
            $result[array_rand($result)] += 1;
        }

        return array_values($result);
    }

    public static function getPoints(int $first_team_goal, int $second_team_goal)
    {
        $result['first_team_point'] = 2;
        $result['second_team_point'] = 2;

        if($first_team_goal > $second_team_goal){
            $result['first_team_point'] = 3;
            $result['second_team_point'] = 1;
        } else if($first_team_goal < $second_team_goal){
            $result['first_team_point'] = 1;
            $result['second_team_point'] = 3;
        }

        return array_values($result);
    }
}