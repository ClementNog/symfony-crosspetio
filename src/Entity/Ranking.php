<?php

namespace App\Entity;

use App\Repository\RankingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RankingRepository::class)]
class Ranking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $endrace = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEndrace(): ?\DateTimeInterface
    {
        return $this->endrace;
    }

    public function setEndrace(\DateTimeInterface $endrace): self
    {
        $this->endrace = $endrace;

        return $this;
    }
}
