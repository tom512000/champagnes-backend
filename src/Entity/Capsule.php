<?php

namespace App\Entity;

use App\Repository\CapsuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CapsuleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Capsule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Producteur::class, inversedBy: 'capsules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Producteur $producteur = null;

    #[ORM\ManyToOne(targetEntity: Lieu::class, inversedBy: 'capsules')]
    private ?Lieu $lieu = null;

    #[ORM\ManyToOne(targetEntity: Taille::class, inversedBy: 'capsules')]
    private ?Taille $taille = null;

    #[ORM\ManyToOne(targetEntity: Matiere::class, inversedBy: 'capsules')]
    private ?Matiere $matiere = null;

    #[ORM\ManyToOne(targetEntity: Couleur::class, inversedBy: 'capsules')]
    private ?Couleur $couleur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $embleme = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $inscription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $decoration = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $muselet = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(targetEntity: CapsuleItem::class, mappedBy: 'capsule', orphanRemoval: true)]
    private Collection $capsuleItems;

    public function __construct()
    {
        $this->capsuleItems = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProducteur(): ?Producteur
    {
        return $this->producteur;
    }

    public function setProducteur(?Producteur $producteur): static
    {
        $this->producteur = $producteur;
        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): static
    {
        $this->lieu = $lieu;
        return $this;
    }

    public function getTaille(): ?Taille
    {
        return $this->taille;
    }

    public function setTaille(?Taille $taille): static
    {
        $this->taille = $taille;
        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): static
    {
        $this->matiere = $matiere;
        return $this;
    }

    public function getCouleur(): ?Couleur
    {
        return $this->couleur;
    }

    public function setCouleur(?Couleur $couleur): static
    {
        $this->couleur = $couleur;
        return $this;
    }

    public function getEmbleme(): ?string
    {
        return $this->embleme;
    }

    public function setEmbleme(?string $embleme): static
    {
        $this->embleme = $embleme;
        return $this;
    }

    public function getInscription(): ?string
    {
        return $this->inscription;
    }

    public function setInscription(?string $inscription): static
    {
        $this->inscription = $inscription;
        return $this;
    }

    public function getDecoration(): ?string
    {
        return $this->decoration;
    }

    public function setDecoration(?string $decoration): static
    {
        $this->decoration = $decoration;
        return $this;
    }

    public function isMuselet(): bool
    {
        return $this->muselet;
    }

    public function setMuselet(bool $muselet): static
    {
        $this->muselet = $muselet;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getCapsuleItems(): Collection
    {
        return $this->capsuleItems;
    }

    public function addCapsuleItem(CapsuleItem $capsuleItem): static
    {
        if (!$this->capsuleItems->contains($capsuleItem)) {
            $this->capsuleItems->add($capsuleItem);
            $capsuleItem->setCapsule($this);
        }
        return $this;
    }

    public function removeCapsuleItem(CapsuleItem $capsuleItem): static
    {
        if ($this->capsuleItems->removeElement($capsuleItem)) {
            if ($capsuleItem->getCapsule() === $this) {
                $capsuleItem->setCapsule(null);
            }
        }
        return $this;
    }

    public function getQuantite(): int
    {
        return $this->capsuleItems->count();
    }
}
