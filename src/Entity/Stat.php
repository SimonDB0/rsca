<?php

namespace App\Entity;

use App\Repository\StatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatRepository::class)]
class Stat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $matchDate = null;

    #[ORM\Column(length: 255)]
    private ?string $opponent = null;

    #[ORM\Column(length: 255)]
    private ?string $score = null;

    #[ORM\Column]
    private array $statistics = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatchDate(): ?\DateTimeInterface
    {
        return $this->matchDate;
    }

    public function setMatchDate(\DateTimeInterface $matchDate): static
    {
        $this->matchDate = $matchDate;

        return $this;
    }

    public function getOpponent(): ?string
    {
        return $this->opponent;
    }

    public function setOpponent(string $opponent): static
    {
        $this->opponent = $opponent;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getStatistics(): array
    {
        return $this->statistics;
    }

    public function setStatistics(array $statistics): static
    {
        $this->statistics = $statistics;

        return $this;
    }
}
