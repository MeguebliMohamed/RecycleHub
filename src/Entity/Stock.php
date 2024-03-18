<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Table(name: "stock")]
#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: "type", type: "string", length: 255, nullable: true)]
    private ?string $type;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: true)]
    private ?string $nom;

    #[ORM\Column(name: "description", type: "text", length: 65535, nullable: true)]
    private ?string $description;

    #[ORM\Column(name: "prixUnit", type: "decimal", precision: 10, scale: 2, nullable: true)]
    private ?string $prixunit;

    #[ORM\Column(name: "quantite", type: "decimal", precision: 10, scale: 2, nullable: true)]
    private ?string $quantite;

    #[ORM\Column(name: "unite", type: "string", length: 50, nullable: true)]
    private ?string $unite;

    #[ORM\Column(name: "dateAjout", type: "datetime", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $dateajout;

    #[ORM\Column(name: "etat", type: "string", length: 50, nullable: true)]
    private ?string $etat;

    #[ORM\Column(name: "imageUrl", type: "string", length: 255, nullable: true)]
    private ?string $imageurl;

    #[ORM\Column(name: "idAppelOffre", type: "integer", nullable: true)]
    private ?int $idappeloffre;

    #[ORM\Column(name: "latitude", type: "float", precision: 10, scale: 0, nullable: true)]
    private ?float $latitude;

    #[ORM\Column(name: "longitude", type: "float", precision: 10, scale: 0, nullable: true)]
    private ?float $longitude;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "idUser", referencedColumnName: "id")]
    private ?User $user;

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
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

    public function getPrixunit(): ?string
    {
        return $this->prixunit;
    }

    public function setPrixunit(?string $prixunit): static
    {
        $this->prixunit = $prixunit;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(?string $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    public function getDateajout(): ?\DateTimeInterface
    {
        return $this->dateajout;
    }

    public function setDateajout(?\DateTimeInterface $dateajout): static
    {
        $this->dateajout = $dateajout;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getImageurl(): ?string
    {
        return $this->imageurl;
    }

    public function setImageurl(?string $imageurl): static
    {
        $this->imageurl = $imageurl;

        return $this;
    }

    public function getIdappeloffre(): ?int
    {
        return $this->idappeloffre;
    }

    public function setIdappeloffre(?int $idappeloffre): static
    {
        $this->idappeloffre = $idappeloffre;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
