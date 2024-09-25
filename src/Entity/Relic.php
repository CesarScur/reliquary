<?php

namespace App\Entity;

use App\Repository\RelicRepository;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RelicRepository::class)]
class Relic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'relics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Saint $saint = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\ManyToOne(inversedBy: 'relics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSaint(): ?Saint
    {
        return $this->saint;
    }

    public function setSaint(?Saint $saint): static
    {
        $this->saint = $saint;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }
}
