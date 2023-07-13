<?php

namespace App\Entity\Second;

use App\Entity\Trait\LastUpdateTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: \App\Repository\Second\TodolistRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Todolist
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(min: 6, max: 255), Assert\NotBlank]
    private string $title;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min: 10), Assert\NotBlank]
    private string $description;

    use LastUpdateTrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
