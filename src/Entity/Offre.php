<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull(message: 'Le prix doit être supérieur à zéro.')]
    #[Assert\GreaterThan(value: 0, message: 'Le prix doit être supérieur à zéro.')]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateSoumission = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotNull(message: "La description ne peut pas être vide.")]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etatPayment = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePayment = null;

    #[ORM\ManyToOne(inversedBy: 'offre')]
    #[Assert\NotNull(message: "Tu dois choisir un appel d'offre")]
    private ?AppelOffre $appelOffre = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateSoumission(): ?\DateTimeInterface
    {
        return $this->dateSoumission;
    }

    public function setDateSoumission(?\DateTimeInterface $dateSoumission): static
    {
        $this->dateSoumission = $dateSoumission;

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

    public function getEtatPayment(): ?string
    {
        return $this->etatPayment;
    }

    public function setEtatPayment(?string $etatPayment): static
    {
        $this->etatPayment = $etatPayment;

        return $this;
    }

    public function getDatePayment(): ?\DateTimeInterface
    {
        return $this->datePayment;
    }

    public function setDatePayment(?\DateTimeInterface $datePayment): static
    {
        $this->datePayment = $datePayment;

        return $this;
    }

    public function getAppelOffre(): ?AppelOffre
    {
        return $this->appelOffre;
    }

    public function setAppelOffre(?AppelOffre $appelOffre): static
    {
        $this->appelOffre = $appelOffre;

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
    public function __toString()
    {
        return $this->description; // ou n'importe quelle autre propriété de l'utilisateur que vous souhaitez afficher
    }
    public function __construct()
    {
        $this->dateSoumission = new \DateTimeImmutable(); // ou une autre valeur par défaut si nécessaire
    }
}
