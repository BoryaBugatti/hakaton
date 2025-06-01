<?php

namespace App\Entity;

use App\Repository\MilestoneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MilestoneRepository::class)]
class Milestone
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 100)]
    private string $milestone_name;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $milestone_update_date;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $milestone_status = null;
    
    public function getMilestoneName(): ?string
    {
        return $this->milestone_name;
    }

    public function setMilestoneName(string $milestone_name): static
    {
        $this->milestone_name = $milestone_name;

        return $this;
    }

    public function getMilestoneUpdateDate(): ?\DateTime
    {
        return $this->milestone_update_date;
    }

    public function setMilestoneUpdateDate(\DateTime $milestone_update_date): static
    {
        $this->milestone_update_date = $milestone_update_date;

        return $this;
    }

    public function getMilestoneStatus(): ?string
    {
        return $this->milestone_status;
    }

    public function setMilestoneStatus(string $milestone_status): static
    {
        $this->milestone_status = $milestone_status;

        return $this;
    }
}
