<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 1)]
    private ?string $division = null;

    /**
     * @var Collection<int, Statistics>
     */
    #[ORM\OneToMany(targetEntity: Statistics::class, mappedBy: 'team')]
    private Collection $statistics;

    public function __construct()
    {
        $this->statistics = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, Statistics>
     */
    public function getStatistics(): Collection
    {
        return $this->statistics;
    }

    public function addStatistic(Statistics $statistic): static
    {
        if (!$this->statistics->contains($statistic)) {
            $this->statistics->add($statistic);
            $statistic->setTeam($this);
        }

        return $this;
    }

    public function removeStatistic(Statistics $statistic): static
    {
        if ($this->statistics->removeElement($statistic)) {
            // set the owning side to null (unless already changed)
            if ($statistic->getTeam() === $this) {
                $statistic->setTeam(null);
            }
        }

        return $this;
    }
}
