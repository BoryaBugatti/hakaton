<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 100)]
    private string $project_name;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $project_stack = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $project_description = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $project_spent_time = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $project_time = null;

    #[ORM\ManyToOne(targetEntity: Milestone::class)]
    #[ORM\JoinColumn(name: 'milestone_name', referencedColumnName: 'milestone_name')]
    private Milestone $milestone;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(name: 'team_id', referencedColumnName: 'team_id')]
    private ?Team $team = null;

    #[ORM\Column]
    private ?int $user_id = null;



    public function getProjectName(): ?string
    {
        return $this->project_name;
    }

    public function setProjectName(string $project_name): static
    {
        $this->project_name = $project_name;

        return $this;
    }

    public function getProjectStack(): ?string
    {
        return $this->project_stack;
    }

    public function setProjectStack(string $project_stack): static
    {
        $this->project_stack = $project_stack;

        return $this;
    }

    public function getProjectDescription(): ?string
    {
        return $this->project_description;
    }

    public function setProjectDescription(string $project_description): static
    {
        $this->project_description = $project_description;

        return $this;
    }

    public function getProjectSpentTime(): ?int
    {
        return $this->project_spent_time;
    }

    public function setProjectSpentTime(int $project_spent_time): static
    {
        $this->project_spent_time = $project_spent_time;

        return $this;
    }

    public function getProjectTime(): ?int
    {
        return $this->project_time;
    }

    public function setProjectTime(int $project_time): static
    {
        $this->project_time = $project_time;

        return $this;
    }

    public function getMilestoneName(): ?string
    {
        return $this->milestone->getMilestoneName();
    }

    public function setMilestoneName(string $milestone_name): static
    {
        $this->milestone->setMilestoneName($milestone_name);

        return $this;
    }

    public function getTeeamId(): ?int
    {
        return $this->team->getId();
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }
}
