<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "activite")]
#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[ORM\Column(name: "date_parcours", type: "datetime", nullable: false, options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTime $dateParcours;

    #[ORM\Column(name: "date_fin", type: "datetime", nullable: true)]
    private ?\DateTime $dateFin;

    #[ORM\Column(name: "itineraire", type: "integer", nullable: true)]
    private ?int $itineraire;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id")]
    private User $utilisateur;

    #[ORM\ManyToOne(targetEntity: "Stock")]
    #[ORM\JoinColumn(name: "stock_id", referencedColumnName: "id")]
    private Stock $stock;

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateParcours(): ?\DateTimeInterface
    {
        return $this->dateParcours;
    }

    public function setDateParcours(\DateTimeInterface $dateParcours): static
    {
        $this->dateParcours = $dateParcours;

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

    public function getItineraire(): ?int
    {
        return $this->itineraire;
    }

    public function setItineraire(?int $itineraire): static
    {
        $this->itineraire = $itineraire;

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): static
    {
        $this->stock = $stock;

        return $this;
    }
}
