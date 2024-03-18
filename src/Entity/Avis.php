<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "avis")]
#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[ORM\Column(name: "note", type: "integer", nullable: false)]
    private int $note;

    #[ORM\Column(name: "avi", type: "string", length: 255, nullable: false)]
    private string $avi;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "iduser", referencedColumnName: "id")]
    private User $iduser;

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getAvi(): ?string
    {
        return $this->avi;
    }

    public function setAvi(string $avi): static
    {
        $this->avi = $avi;

        return $this;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }
}
