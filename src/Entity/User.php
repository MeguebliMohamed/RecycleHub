<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this username')]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "Le champs telephone est obligatoire")]
    private ?string $username ;

    #[ORM\Column]
    private array $roles;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
   // #[Assert\NotBlank(message: "Le champs password est obligatoire")]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le prenom est obligatoire")]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le champs adresse est obligatoire")]
    private ?string $adresse = null;

    #[ORM\Column(length: 8)]
   #[Assert\NotBlank(message: "Le champs cin est obligatoire")]
    private ?string $cin = null;

    #[ORM\Column(length: 12)]
    #[Assert\NotBlank(message: "Le champs telephone est obligatoire")]
    private ?string $telephone = null;

    #[ORM\Column(length: 20)]
    private ?string $rib = null;

    #[ORM\Column(length: 50)]
    private ?string $MatFiscal = null;

    #[ORM\Column]
    private ?int $NbrePoint = null;

    #[ORM\Column(length: 255)]
    private ?string $image_name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

   // #[ORM\Column]
    //private ?boolean $is_verified = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champs email est obligatoire")]
    private ?string $email = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "Le champs nom est obligatoire")]
    private ?string $nom = null;

    #[ORM\OneToMany(targetEntity: AppelOffre::class, mappedBy: 'user')]
    private Collection $appelOffres;

    #[ORM\OneToMany(targetEntity: Stocks::class, mappedBy: 'user')]
    private Collection $stocks;

    #[ORM\OneToMany(targetEntity: Offre::class, mappedBy: 'user')]
    private Collection $offres;

    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'iduser')]
    private Collection $avis;

    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'user')]
    private Collection $reclamations;

    public function __construct()
    {
        $this->appelOffres = new ArrayCollection();
        $this->stocks = new ArrayCollection();
        $this->offres = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(string $rib): static
    {
        $this->rib = $rib;

        return $this;
    }

    public function getMatFiscal(): ?string
    {
        return $this->MatFiscal;
    }

    public function setMatFiscal(string $MatFiscal): static
    {
        $this->MatFiscal = $MatFiscal;

        return $this;
    }

    public function getNbrePoint(): ?int
    {
        return $this->NbrePoint;
    }

    public function setNbrePoint(int $NbrePoint): static
    {
        $this->NbrePoint = $NbrePoint;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->image_name;
    }

    public function setImageName(string $image_name): static
    {
        $this->image_name = $image_name;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): static
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }




    /**
     * @return Collection<int, AppelOffre>
     */
    public function getAppelOffres(): Collection
    {
        return $this->appelOffres;
    }

    public function addAppelOffre(AppelOffre $appelOffre): static
    {
        if (!$this->appelOffres->contains($appelOffre)) {
            $this->appelOffres->add($appelOffre);
            $appelOffre->setUser($this);
        }

        return $this;
    }

    public function removeAppelOffre(AppelOffre $appelOffre): static
    {
        if ($this->appelOffres->removeElement($appelOffre)) {
            // set the owning side to null (unless already changed)
            if ($appelOffre->getUser() === $this) {
                $appelOffre->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Offre>
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): static
    {
        if (!$this->offres->contains($offre)) {
            $this->offres->add($offre);
            $offre->setUser($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): static
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getUser() === $this) {
                $offre->setUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom; // ou n'importe quelle autre propriété de l'utilisateur que vous souhaitez afficher
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
            $stock->setUser($this);
        }

        return $this;
    }

    public function removeStock(Stocks $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getUser() === $this) {
                $stock->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setIduser($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getIduser() === $this) {
                $avi->setIduser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setUser($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getUser() === $this) {
                $reclamation->setUser(null);
            }
        }

        return $this;
    }
}
