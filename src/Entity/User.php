<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "user")]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: true)]
    private ?string $nom;

    #[ORM\Column(name: "prenom", type: "string", length: 255, nullable: true)]
    private ?string $prenom;

    #[ORM\Column(name: "adresse", type: "string", length: 255, nullable: true)]
    private ?string $adresse;

    #[ORM\Column(name: "telephone", type: "string", length: 20, nullable: true)]
    private ?string $telephone;

    #[ORM\Column(name: "status", type: "string", length: 20, nullable: true)]
    private ?string $status;

    #[ORM\Column(name: "role", type: "string", length: 20, nullable: true)]
    private ?string $role;

    #[ORM\Column(name: "password", type: "string", length: 255, nullable: true)]
    private ?string $password;

    #[ORM\Column(name: "verifcode", type: "string", length: 10, nullable: true)]
    private ?string $verifcode;

    #[ORM\Column(name: "cin", type: "string", length: 20, nullable: true)]
    private ?string $cin;

    #[ORM\Column(name: "mat_fiscal", type: "string", length: 20, nullable: true)]
    private ?string $matFiscal;

    #[ORM\Column(name: "rib", type: "string", length: 20, nullable: true)]
    private ?string $rib;

    #[ORM\Column(name: "nbre_point", type: "integer", nullable: true)]
    private ?int $nbrePoint;

    #[ORM\Column(name: "Email", type: "string", length: 255, nullable: true)]
    private ?string $email;

    #[ORM\Column(name: "user_name", type: "string", length: 50, nullable: true)]
    private ?string $userName;

    #[ORM\Column(name: "image", type: "string", length: 60, nullable: true)]
    private ?string $image;

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getVerifcode(): ?string
    {
        return $this->verifcode;
    }

    public function setVerifcode(?string $verifcode): static
    {
        $this->verifcode = $verifcode;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getMatFiscal(): ?string
    {
        return $this->matFiscal;
    }

    public function setMatFiscal(?string $matFiscal): static
    {
        $this->matFiscal = $matFiscal;

        return $this;
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(?string $rib): static
    {
        $this->rib = $rib;

        return $this;
    }

    public function getNbrePoint(): ?int
    {
        return $this->nbrePoint;
    }

    public function setNbrePoint(?int $nbrePoint): static
    {
        $this->nbrePoint = $nbrePoint;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): static
    {
        $this->userName = $userName;

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
    public function __toString() {
        return $this->nom . ' ' . $this->prenom . ' ' . $this->email;
    }
}
