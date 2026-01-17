<?php

namespace App\Entity;

use App\Repository\CouleurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouleurRepository::class)]
class Couleur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $codeHex = null;

    #[ORM\OneToMany(targetEntity: Capsule::class, mappedBy: 'couleur')]
    private Collection $capsules;

    public function __construct()
    {
        $this->capsules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getCodeHex(): ?string
    {
        return $this->codeHex;
    }

    public function setCodeHex(?string $codeHex): static
    {
        $this->codeHex = $codeHex;
        return $this;
    }

    public function getCapsules(): Collection
    {
        return $this->capsules;
    }
}
