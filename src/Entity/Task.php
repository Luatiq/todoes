<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Blameable;
use Gedmo\Mapping\Annotation\Timestampable;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Blameable(on: 'create')]
    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Column(length: 255)]
    private ?string $display = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $deadline = null;

    #[ORM\Column]
    private ?bool $isHardDeadline = false;

    #[PositiveOrZero]
    #[LessThanOrEqual(3)]
    #[ORM\Column]
    private ?int $concentrationLevelRequired = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function setDisplay(string $display): self
    {
        $this->display = $display;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDeadline(): ?DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(?DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function isIsHardDeadline(): ?bool
    {
        return $this->isHardDeadline;
    }

    public function setIsHardDeadline(bool $isHardDeadline): self
    {
        $this->isHardDeadline = $isHardDeadline;

        return $this;
    }

    public function getConcentrationLevelRequired(): ?int
    {
        return $this->concentrationLevelRequired;
    }

    public function setConcentrationLevelRequired(int $concentrationLevelRequired): self
    {
        $this->concentrationLevelRequired = $concentrationLevelRequired;

        return $this;
    }
}
