<?php

namespace App\Entity;

use App\Repository\AppelOffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppelOffreRepository::class)]
class AppelOffre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $prixInitial = null;

    #[ORM\Column(nullable: true)]
    private ?float $prixFinal = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etatPayment = null;

    #[ORM\OneToMany(mappedBy: 'appelOffre', targetEntity: Stocks::class)]
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
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('appel_offre_id', $this->id));
        return $this->stocks->matching($criteria);
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
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('appel_offre_id', $this->id));
        return $this->offre->matching($criteria);
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
        return $this->titre . ' ' . $this->dateFin->format('Y-m-d H:i:s') . ' ' . $this->description . ' ' . $this->prixInitial;
    }
}
