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

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $barcode = null;

    #[ORM\Column(length: 50)]
    private ?string $shortname = null;

    #[ORM\Column(length: 50)]
    private ?string $lastname = null;

    #[ORM\Column(nullable: true)]
    private ?float $mas = null;

    #[ORM\Column(length: 10)]
    private ?string $gender = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $objective = null;

    #[ORM\ManyToOne]
    private ?Grade $grade = null;

    #[ORM\ManyToOne]
    private ?Race $race = null;

    #[ORM\ManyToOne]
    public ?Run $run = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTimeInterface $endrace = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    public function setShortname(string $shortname): self
    {
        $this->shortname = $shortname;

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

    public function setMas(?float $mas): self
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

    public function setObjective(?\DateTimeInterface $objective): self
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
    public function getRace(): ?Race
    {
        return $this->race;
    }

    public function setRace(?Race $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getRun(): ?Run
    {
        return $this->run;
    }

    public function setRun(?Run $run): self
    {
        $this->run = $run;

        return $this;
    }

    public function getEndrace(): ?\DateTimeInterface
    {
        return $this->endrace;
    }

    public function setEndrace(?\DateTimeInterface $endrace): self
    {
        $this->endrace = $endrace;

        return $this;
    }
    public function __toString(): string
    {
        return $this->endrace->format('Y-M-D h-m-s');
    }
}
