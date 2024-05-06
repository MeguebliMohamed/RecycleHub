<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[ORM\Entity(repositoryClass: StocksRepository::class)]
#[Vich\Uploadable]
class Stocks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotNull(message: 'Veuillez spécifier le type.')]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide.')]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull(message: 'Le prix doit être supérieur à zéro.')]
    #[Assert\GreaterThan(value: 0, message: 'Le prix doit être supérieur à zéro.')]
    private ?float $prixUnit = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull(message: 'Le prix doit être supérieur à zéro.')]
    #[Assert\GreaterThan(value: 0, message: 'Le prix doit être supérieur à zéro.')]
    private ?float $quantite = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotNull(message: "L'unité ne peut pas être vide.")]
    private ?string $unite = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $dateAjout ;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat = null;
    #[Vich\UploadableField(mapping: 'product_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;
    #[ORM\Column(nullable: true)]
    #[Assert\Regex(
        pattern: '/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/',
        message: 'Veuillez fournir une latitude valide.'
    )]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Regex(
        pattern: '/^[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/',
        message: 'Veuillez fournir une longitude valide.'
    )]
    private ?float $longitude = null;


    #[ORM\ManyToOne(inversedBy: 'stocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AppelOffre $appelOffre = null;

    #[ORM\ManyToOne(inversedBy: 'stocks')]
    private ?User $user = null;
    public function __construct()
    {
        $this->dateAjout = new \DateTimeImmutable(); // ou une autre valeur par défaut si nécessaire
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixUnit(): ?float
    {
        return $this->prixUnit;
    }

    public function setPrixUnit(?float $prixUnit): static
    {
        $this->prixUnit = $prixUnit;

        return $this;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(?float $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(?\DateTimeInterface $dateAjout): static
    {
        $this->dateAjout = $dateAjout;
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
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

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
        return $this->nom; // ou n'importe quelle autre propriété de l'utilisateur que vous souhaitez afficher
    }
}
