<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\Repository\ApplicationRepository")]
#[ORM\Table(name: "applications")]
#[ORM\HasLifecycleCallbacks]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "applications")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private ?User $user = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $applicationName;

    #[ORM\Column(type: "text")]
    private string $applicationDescription;

    #[ORM\Column(type: "string", length: 20, options: ["default" => "new"])]
    private string $applicationStatus = 'new';

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $applicationTime = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $applicationType = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $applicationBudget = null;

    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $applicationTimeSpent = 0;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $isAiProcessed = false;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $completedAt = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getApplicationName(): string
    {
        return $this->applicationName;
    }

    public function setApplicationName(string $applicationName): self
    {
        $this->applicationName = $applicationName;
        return $this;
    }

    public function getApplicationDescription(): string
    {
        return $this->applicationDescription;
    }

    public function setApplicationDescription(string $applicationDescription): self
    {
        $this->applicationDescription = $applicationDescription;
        return $this;
    }

    public function getApplicationStatus(): string
    {
        return $this->applicationStatus;
    }

    public function setApplicationStatus(string $applicationStatus): self
    {
        $this->applicationStatus = $applicationStatus;
        
        if ($applicationStatus === 'completed' && $this->completedAt === null) {
            $this->completedAt = new \DateTimeImmutable();
        }
        
        return $this;
    }

    public function getApplicationTime(): ?int
    {
        return $this->applicationTime;
    }

    public function setApplicationTime(?int $applicationTime): self
    {
        $this->applicationTime = $applicationTime;
        return $this;
    }

    public function getApplicationType(): ?string
    {
        return $this->applicationType;
    }

    public function setApplicationType(?string $applicationType): self
    {
        $this->applicationType = $applicationType;
        return $this;
    }

    public function getApplicationBudget(): ?string
    {
        return $this->applicationBudget;
    }

    public function setApplicationBudget(?string $applicationBudget): self
    {
        $this->applicationBudget = $applicationBudget;
        return $this;
    }

    public function getApplicationTimeSpent(): int
    {
        return $this->applicationTimeSpent;
    }

    public function setApplicationTimeSpent(int $applicationTimeSpent): self
    {
        $this->applicationTimeSpent = $applicationTimeSpent;
        
        // Автоматическое обновление статуса при превышении времени
        if ($this->applicationTime !== null && 
            $this->applicationTimeSpent >= $this->applicationTime) {
            $this->setApplicationStatus('completed');
        }
        
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function isAiProcessed(): bool
    {
        return $this->isAiProcessed;
    }

    public function setIsAiProcessed(bool $isAiProcessed): self
    {
        $this->isAiProcessed = $isAiProcessed;
        return $this;
    }

    public function getCompletedAt(): ?\DateTimeInterface
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeInterface $completedAt): self
    {
        $this->completedAt = $completedAt;
        return $this;
    }

    // Дополнительные методы
    public function getRemainingTime(): ?int
    {
        if ($this->applicationTime === null) {
            return null;
        }
        
        return max(0, $this->applicationTime - $this->applicationTimeSpent);
    }

    public function getProgressPercentage(): int
    {
        if ($this->applicationTime === null || $this->applicationTime === 0) {
            return 0;
        }
        
        return min(100, (int) round(($this->applicationTimeSpent / $this->applicationTime) * 100));
    }
}