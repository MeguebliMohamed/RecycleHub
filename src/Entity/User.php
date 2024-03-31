<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use http\Message;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Table(name: "user")]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    private ?string $nom;

    #[ORM\Column(name: "prenom", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire")]
    private ?string $prenom;

    #[ORM\Column(name: "adresse", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "L'adresse est obligatoire")]
    private ?string $adresse;

    #[ORM\Column(name: "telephone", type: "string", length: 20, nullable: true)]
    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire")]
    #[Assert\Regex(pattern: "/^\d{10}$/", message: "Le numéro de téléphone doit contenir exactement 10 chiffres.")]
    private ?string $telephone;

    #[ORM\Column(name: "status", type: "string", length: 20, nullable: true)]
    private ?string $status;

    #[ORM\Column(name: "role", type: "string", length: 20, nullable: true)]
    private ?string $role;

    #[ORM\Column(name: "password", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire")]
    private ?string $password;

    #[ORM\Column(name: "verifcode", type: "string", length: 10, nullable: true)]
    #[Assert\NotBlank(message: "Le code de vérification est obligatoire")]
    private ?string $verifcode;

    #[ORM\Column(name: "cin", type: "string", length: 20, nullable: true)]
    #[Assert\NotBlank(message: "Le numéro CIN est obligatoire")]
    private ?string $cin;

    #[ORM\Column(name: "mat_fiscal", type: "string", length: 20, nullable: true)]

    private ?string $matFiscal;

    #[ORM\Column(name: "rib", type: "string", length: 20, nullable: true)]
    #[Assert\NotBlank(message: "Le RIB est obligatoire")]
    private ?string $rib;

    #[ORM\Column(name: "nbre_point", type: "integer", nullable: true)]
    private ?int $nbrePoint;

    #[ORM\Column(name: "Email", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Email(message: "L'email n'est pas valide")]
    private ?string $email;

    #[ORM\Column(name: "user_name", type: "string", length: 50, nullable: true)]
    #[Assert\NotBlank(message: "Le nom d'utilisateur est obligatoire")]
    private ?string $userName;


    #[Vich\UploadableField(mapping: 'product_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;


    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;
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


/**
 * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
 * of 'UploadedFile' is injected into this setter to trigger the update. If this
 * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
 * must be able to accept an instance of 'File' as the bundle will inject one here
 * during Doctrine hydration.
 *
 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
 */
public function setImageFile(?File $imageFile = null): void
{
    $this->imageFile = $imageFile;

    if (null !== $imageFile) {
        // It is required that at least one field changes if you are using doctrine
        // otherwise the event listeners won't be called and the file is lost
        $this->updatedAt = new \DateTimeImmutable();
    }
}

public function getImageFile(): ?File
{
    return $this->imageFile;
}

public function setImageName(?string $imageName): void
{
    $this->imageName = $imageName;
}

public function getImageName(): ?string
{
    return $this->imageName;
}


}
