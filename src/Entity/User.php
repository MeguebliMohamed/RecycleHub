<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
//use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    //#[Assert\NotBlank(message: "Le champs telephone est obligatoire")]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    //#[Assert\NotBlank(message: "Le champs telephone est obligatoire")]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
   // #[Assert\NotBlank(message: "Le prenom est obligatoire")]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
   // #[Assert\NotBlank(message: "Le champs adresse est obligatoire")]
    private ?string $adresse = null;

    #[ORM\Column(length: 8)]
    //#[Assert\NotBlank(message: "Le champs cin est obligatoire")]
    private ?string $cin = null;

    #[ORM\Column(length: 12)]
    //#[Assert\NotBlank(message: "Le champs telephone est obligatoire")]
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
    //#[Assert\NotBlank(message: "Le champs telephone est obligatoire")]
    private ?string $email = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 20)]
    //#[Assert\NotBlank(message: "Le champs telephone est obligatoire")]
    private ?string $nom = null;

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



    /*public function __toString()
    {
        return $this->nom;
    }
   */
}
