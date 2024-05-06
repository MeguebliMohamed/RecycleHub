<?php

namespace App\Entity;

use App\Repository\UsercadeauxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "usercadeaux")]
#[ORM\Entity(repositoryClass: UsercadeauxRepository::class)]
class Usercadeaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: "quantite_achetee", type: "integer", nullable: true)]
    private ?int $quantiteAchetee;

    #[ORM\Column(name: "date_achat", type: "datetime", nullable: false, options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTimeInterface $dateAchat;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id")]
    private ?User $utilisateur;

    #[ORM\ManyToOne(targetEntity: "Cadeaux")]
    #[ORM\JoinColumn(name: "cadeau_id", referencedColumnName: "id")]
    private ?Cadeaux $cadeau;

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantiteAchetee(): ?int
    {
        return $this->quantiteAchetee;
    }

    public function setQuantiteAchetee(?int $quantiteAchetee): static
    {
        $this->quantiteAchetee = $quantiteAchetee;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->dateAchat;
    }

    public function setDateAchat(\DateTimeInterface $dateAchat): static
    {
        $this->dateAchat = $dateAchat;

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

    public function getCadeau(): ?Cadeaux
    {
        return $this->cadeau;
    }

    public function setCadeau(?Cadeaux $cadeau): static
    {
        $this->cadeau = $cadeau;

        return $this;
    }
}
