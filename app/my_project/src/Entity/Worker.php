<?php

namespace App\Entity;

use App\Repository\WorkerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkerRepository::class)]
class Worker
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'worker_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $worker_name = null;

    #[ORM\Column(length: 255)]
    private ?string $worker_email = null;

    #[ORM\Column]
    private ?int $worker_time = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkerName(): ?string
    {
        return $this->worker_name;
    }

    public function setWorkerName(string $worker_name): static
    {
        $this->worker_name = $worker_name;

        return $this;
    }

    public function getWorkerEmail(): ?string
    {
        return $this->worker_email;
    }

    public function setWorkerEmail(string $worker_email): static
    {
        $this->worker_email = $worker_email;

        return $this;
    }

    public function getWorkerTime(): ?int
    {
        return $this->worker_time;
    }

    public function setWorkerTime(int $worker_time): static
    {
        $this->worker_time = $worker_time;

        return $this;
    }
}
