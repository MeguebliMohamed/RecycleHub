<?php

namespace App\Entity;

use App\Repository\OffresRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "offres")]
#[ORM\Entity(repositoryClass: OffresRepository::class)]
class Offres
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idOffre", type: "integer", nullable: false)]
    private int $idoffre;

    #[ORM\Column(name: "montant", type: "float", precision: 10, scale: 0, nullable: true)]
    private ?float $montant;

    #[ORM\Column(name: "dateSoumission", type: "datetime", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $datesoumission;

    #[ORM\Column(name: "description", type: "string", length: 255, nullable: true)]
    private ?string $description;

    #[ORM\Column(name: "etat", type: "string", length: 20, nullable: true, options: ["default" => "En cours"])]
    private ?string $etat;

    #[ORM\Column(name: "etatPayment", type: "string", length: 10, nullable: true)]
    private ?string $etatpayment;

    #[ORM\Column(name: "datePayment", type: "datetime", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $datepayment;

    #[ORM\ManyToOne(targetEntity: "Appelsoffres")]
    #[ORM\JoinColumn(name: "idAppelOffre", referencedColumnName: "id")]
    private ?Appelsoffres $idappeloffre;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "idsociete", referencedColumnName: "id")]
    private ?User $idsociete;

    // Getters and setters

    public function getIdoffre(): ?int
    {
        return $this->idoffre;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(?float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDatesoumission(): ?\DateTimeInterface
    {
        return $this->datesoumission;
    }

    public function setDatesoumission(?\DateTimeInterface $datesoumission): static
    {
        $this->datesoumission = $datesoumission;

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): static
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

    public function getDatepayment(): ?\DateTimeInterface
    {
        return $this->datepayment;
    }

    public function setDatepayment(?\DateTimeInterface $datepayment): static
    {
        $this->datepayment = $datepayment;

        return $this;
    }

    public function getIdappeloffre(): ?Appelsoffres
    {
        return $this->idappeloffre;
    }

    public function setIdappeloffre(?Appelsoffres $idappeloffre): static
    {
        $this->idappeloffre = $idappeloffre;

        return $this;
    }

    public function getIdsociete(): ?User
    {
        return $this->idsociete;
    }

    public function setIdsociete(?User $idsociete): static
    {
        $this->idsociete = $idsociete;

        return $this;
    }
}
