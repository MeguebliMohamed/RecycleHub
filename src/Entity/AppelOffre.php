<?php

namespace App\Entity;

use App\Repository\AppelOffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: AppelOffreRepository::class)]
class AppelOffre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le titre ne peut pas être vide.")]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "La description ne peut pas être vide.")]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull(message: "Le prix initial ne peut pas être vide.")]
    #[Assert\Type(type: "float", message: "Le prix initial doit être un nombre décimal.")]
    private ?float $prixInitial = null;

    #[ORM\Column(nullable: true)]
     private ?float $prixFinal = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\GreaterThanOrEqual(value: "now +24 hours", message: "La date de fin doit être au moins 24 heures après la date de début.")]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etatPayment = null;

    #[ORM\OneToMany(mappedBy: 'appelOffre', targetEntity: Stocks::class)]
  //  #[Assert\Count(min: 1, minMessage: "L'appel d'offres doit contenir au moins un article.")]
    private Collection $stocks;

    #[ORM\ManyToOne(inversedBy: 'appelOffres')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'appelOffre', targetEntity: Offre::class)]
    private Collection $offre;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->offre = new ArrayCollection();
        $this->dateDebut = new \DateTimeImmutable(); // ou une autre valeur par défaut si nécessaire
    }
public function setliststock(Collection $collection): static
{
    $this->stocks = $collection;
    return $this;
}
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

    public function getPrixInitial(): ?float
    {
        return $this->prixInitial;
    }

    public function setPrixInitial(?float $prixInitial): static
    {
        $this->prixInitial = $prixInitial;

        return $this;
    }

    public function getPrixFinal(): ?float
    {
        return $this->prixFinal;
    }

    public function setPrixFinal(?float $prixFinal): static
    {
        $this->prixFinal = $prixFinal;

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

    /**
     * @return Collection<int, Stocks>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stocks $stock): static
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setAppelOffre($this);
        }

        return $this;
    }

    public function removeStock(Stocks $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getAppelOffre() === $this) {
                $stock->setAppelOffre(null);
            }
        }

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

    /**
     * @return Collection<int, Offre>
     */
    public function getOffre(): Collection
    {
        return $this->offre;
    }

    public function addOffre(Offre $offre): static
    {
        if (!$this->offre->contains($offre)) {
            $this->offre->add($offre);
            $offre->setAppelOffre($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): static
    {
        if ($this->offre->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getAppelOffre() === $this) {
                $offre->setAppelOffre(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->titre ;
    }
}
