<?php

namespace App\Entity;

use App\Repository\SaintRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaintRepository::class)]
class Saint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Relic>
     */
    #[ORM\OneToMany(targetEntity: Relic::class, mappedBy: 'saint')]
    private Collection $relics;

    public function __construct()
    {
        $this->relics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Relic>
     */
    public function getRelics(): Collection
    {
        return $this->relics;
    }

    public function addRelic(Relic $relic): static
    {
        if (!$this->relics->contains($relic)) {
            $this->relics->add($relic);
            $relic->setSaint($this);
        }

        return $this;
    }

    public function removeRelic(Relic $relic): static
    {
        if ($this->relics->removeElement($relic)) {
            // set the owning side to null (unless already changed)
            if ($relic->getSaint() === $this) {
                $relic->setSaint(null);
            }
        }

        return $this;
    }
}
