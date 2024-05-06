<?php

namespace App\Entity;

use App\Repository\AppelsoffresRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "appelsoffres")]
#[ORM\Entity(repositoryClass: AppelsoffresRepository::class)]
class Appelsoffres
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[ORM\Column(name: "titre", type: "string", length: 255, nullable: true)]
    private ?string $titre;

    #[ORM\Column(name: "description", type: "text", length: 65535, nullable: true)]
    private ?string $description;

    #[ORM\Column(name: "prixInitial", type: "float", precision: 10, scale: 0, nullable: false)]
    private float $prixinitial = 0;

    #[ORM\Column(name: "prixFinal", type: "float", precision: 10, scale: 0, nullable: false)]
    private float $prixfinal = 0;

    #[ORM\Column(name: "date_debut", type: "datetime", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $dateDebut;

    #[ORM\Column(name: "date_fin", type: "datetime", nullable: true)]
    private ?\DateTimeInterface $dateFin;

    #[ORM\Column(name: "etat", type: "string", length: 20, nullable: false, options: ["default" => "En cours"])]
    private string $etat = 'En cours';

    #[ORM\Column(name: "etatPayment", type: "string", length: 10, nullable: true)]
    private ?string $etatpayment;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "collecteur_id", referencedColumnName: "id")]
    private User $collecteur;

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;

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

    public function getPrixinitial(): ?float
    {
        return $this->prixinitial;
    }

    public function setPrixinitial(float $prixinitial): static
    {
        $this->prixinitial = $prixinitial;

        return $this;
    }

    public function getPrixfinal(): ?float
    {
        return $this->prixfinal;
    }

    public function setPrixfinal(float $prixfinal): static
    {
        $this->prixfinal = $prixfinal;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getEtatpayment(): ?string
    {
        return $this->etatpayment;
    }

    public function setEtatpayment(?string $etatpayment): static
    {
        $this->etatpayment = $etatpayment;

        return $this;
    }

    public function getCollecteur(): ?User
    {
        return $this->collecteur;
    }

    public function setCollecteur(?User $collecteur): static
    {
        $this->collecteur = $collecteur;

        return $this;
    }
}
