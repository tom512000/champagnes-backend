<?php

namespace App\Entity;

use App\Repository\CapsuleItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CapsuleItemRepository::class)]
class CapsuleItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Capsule::class, inversedBy: 'capsuleItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Capsule $capsule = null;

    #[ORM\ManyToOne(targetEntity: Etat::class, inversedBy: 'capsuleItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $prixEstime = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAcquisition = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapsule(): ?Capsule
    {
        return $this->capsule;
    }

    public function setCapsule(?Capsule $capsule): static
    {
        $this->capsule = $capsule;
        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): static
    {
        $this->etat = $etat;
        return $this;
    }

    public function getPrixEstime(): ?string
    {
        return $this->prixEstime;
    }

    public function setPrixEstime(?string $prixEstime): static
    {
        $this->prixEstime = $prixEstime;
        return $this;
    }

    public function getDateAcquisition(): ?\DateTimeInterface
    {
        return $this->dateAcquisition;
    }

    public function setDateAcquisition(?\DateTimeInterface $dateAcquisition): static
    {
        $this->dateAcquisition = $dateAcquisition;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;
        return $this;
    }
}
