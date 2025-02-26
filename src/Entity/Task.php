<?php

namespace App\Entity;

use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        max:255,
        maxMessage:"Le titre ne doit pas contenir plus de 255 caractères")]
    #[Assert\NotBlank(message:"Le titre est obligatoire")]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]

    private ?string $description = null;

    #[ORM\Column(type: 'string', enumType: TaskStatus::class)]
    private TaskStatus $status;

    #[ORM\Column]
    private ?\DateTimeImmutable $creationDate;

    public function __construct()
    {
        $this->creationDate = new \DateTimeImmutable();
        $this->status = TaskStatus::TODO;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeImmutable $creationDate): static
{
    $this->creationDate = $creationDate;
    return $this;
}
}
