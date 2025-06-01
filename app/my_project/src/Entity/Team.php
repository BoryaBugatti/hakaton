<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Worker;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'team_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Worker::class)]
    #[ORM\JoinColumn(name: 'worker_id', referencedColumnName: 'worker_id')]
    private ?Worker $worker = null;

    #[ORM\Column(length: 50)]
    private ?string $team_level = null;

    #[ORM\Column(length: 255)]
    private ?string $team_budjet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkerId(): ?int
    {
        return $this->worker->getId();
    }

    public function getTeamLevel(): ?string
    {
        return $this->team_level;
    }

    public function setTeamLevel(string $team_level): static
    {
        $this->team_level = $team_level;

        return $this;
    }

    public function getTeamBudjet(): ?string
    {
        return $this->team_budjet;
    }

    public function setTeamBudjet(string $team_budjet): static
    {
        $this->team_budjet = $team_budjet;

        return $this;
    }
}
