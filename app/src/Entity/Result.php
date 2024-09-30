<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
class Result
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $first_team = null;

    #[ORM\Column]
    private ?int $first_team_goal = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $second_team = null;

    #[ORM\Column]
    private ?int $second_team_goal = null;

    #[ORM\Column]
    private ?int $first_team_point = null;

    #[ORM\Column]
    private ?int $second_team_point = null;

    #[ORM\Column(length: 1, nullable: true)]
    private ?string $division = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFirstTeam(): ?Team
    {
        return $this->first_team;
    }

    public function setFirstTeam(?Team $first_team): static
    {
        $this->first_team = $first_team;

        return $this;
    }

    public function getFirstTeamGoal(): ?int
    {
        return $this->first_team_goal;
    }

    public function setFirstTeamGoal(int $first_team_goal): static
    {
        $this->first_team_goal = $first_team_goal;

        return $this;
    }

    public function getSecondTeam(): ?Team
    {
        return $this->second_team;
    }

    public function setSecondTeam(?Team $second_team): static
    {
        $this->second_team = $second_team;

        return $this;
    }

    public function getSecondTeamGoal(): ?int
    {
        return $this->second_team_goal;
    }

    public function setSecondTeamGoal(int $second_team_goal): static
    {
        $this->second_team_goal = $second_team_goal;

        return $this;
    }

    public function getFirstTeamPoint(): ?int
    {
        return $this->first_team_point;
    }

    public function setFirstTeamPoint(int $first_team_point): static
    {
        $this->first_team_point = $first_team_point;

        return $this;
    }

    public function getSecondTeamPoint(): ?int
    {
        return $this->second_team_point;
    }

    public function setSecondTeamPoint(int $second_team_point): static
    {
        $this->second_team_point = $second_team_point;

        return $this;
    }

    public function getDivision(): ?string
    {
        return $this->division;
    }

    public function setDivision(string $division): static
    {
        $this->division = $division;

        return $this;
    }
}
