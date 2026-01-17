<?php

namespace App\Entity;

use App\Repository\TailleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TailleRepository::class)]
class Taille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $libelle = null;

    #[ORM\Column(nullable: true)]
    private ?float $diametreMm = null;

    #[ORM\OneToMany(targetEntity: Capsule::class, mappedBy: 'taille')]
    private Collection $capsules;

    public function __construct()
    {
        $this->capsules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function getDiametreMm(): ?float
    {
        return $this->diametreMm;
    }

    public function setDiametreMm(?float $diametreMm): static
    {
        $this->diametreMm = $diametreMm;
        return $this;
    }

    public function getCapsules(): Collection
    {
        return $this->capsules;
    }
}
