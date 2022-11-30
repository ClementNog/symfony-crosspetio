<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GradeRepository::class)]
class Grade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $shortname = null;

    #[ORM\OneToMany(mappedBy: 'grade', targetEntity: Student::class)]
    private Collection $level;

    public function __construct()
    {
        $this->level = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Student>
     */
    public function getLevel(): Collection
    {
        return $this->level;
    }

    public function addLevel(Student $level): self
    {
        if (!$this->level->contains($level)) {
            $this->level->add($level);
            $level->setGrade($this);
        }

        return $this;
    }

    public function removeLevel(Student $level): self
    {
        if ($this->level->removeElement($level)) {
            // set the owning side to null (unless already changed)
            if ($level->getGrade() === $this) {
                $level->setGrade(null);
            }
        }

        return $this;
    }
}
