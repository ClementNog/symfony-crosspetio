<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $barcode = null;

    #[ORM\Column(length: 20)]
    private ?string $firstname = null;

    #[ORM\Column(length: 20)]
    private ?string $lastname = null;

    #[ORM\Column]
    private ?float $mas = null;

    #[ORM\Column(length: 10)]
    private ?string $gender = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $objective = null;

    #[ORM\ManyToOne(inversedBy: 'level')]
    private ?Grade $grade = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    private ?Race $start = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    private ?Ranking $ranking = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getMas(): ?float
    {
        return $this->mas;
    }

    public function setMas(float $mas): self
    {
        $this->mas = $mas;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getObjective(): ?\DateTimeInterface
    {
        return $this->objective;
    }

    public function setObjective(\DateTimeInterface $objective): self
    {
        $this->objective = $objective;

        return $this;
    }

    public function getGrade(): ?Grade
    {
        return $this->grade;
    }

    public function setGrade(?Grade $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getStart(): ?Race
    {
        return $this->start;
    }

    public function setStart(?Race $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getRanking(): ?Ranking
    {
        return $this->ranking;
    }

    public function setRanking(?Ranking $ranking): self
    {
        $this->ranking = $ranking;

        return $this;
    }
}
