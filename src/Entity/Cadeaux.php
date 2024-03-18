<?php

namespace App\Entity;

use App\Repository\CadeauxRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "cadeaux")]
#[ORM\Entity(repositoryClass: CadeauxRepository::class)]
class Cadeaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[ORM\Column(name: "nom", type: "string", length: 100, nullable: false)]
    private string $nom;

    #[ORM\Column(name: "description", type: "text", length: 65535, nullable: true)]
    private ?string $description;

    #[ORM\Column(name: "prix", type: "float", precision: 10, scale: 0, nullable: false)]
    private float $prix;

    #[ORM\Column(name: "stock", type: "integer", nullable: false)]
    private int $stock = 0;

    #[ORM\Column(name: "disponible", type: "boolean", nullable: false)]
    private bool $disponible = false;

    #[ORM\Column(name: "date_disponibilite", type: "date", nullable: true)]
    private ?\DateTimeInterface $dateDisponibilite;

    #[ORM\Column(name: "categorie", type: "string", length: 50, nullable: false)]
    private string $categorie;

    #[ORM\Column(name: "imageUrl", type: "string", length: 200, nullable: true)]
    private ?string $imageUrl;

    // Getters and setters

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): static
    {
        $this->disponible = $disponible;

        return $this;
    }

    public function getDateDisponibilite(): ?\DateTimeInterface
    {
        return $this->dateDisponibilite;
    }

    public function setDateDisponibilite(?\DateTimeInterface $dateDisponibilite): static
    {
        $this->dateDisponibilite = $dateDisponibilite;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }
}
