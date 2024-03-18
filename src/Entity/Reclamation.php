<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "reclamation")]
#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[ORM\Column(name: "reclaimType", type: "string", length: 255, nullable: true)]
    private ?string $reclaimType;

    #[ORM\Column(name: "description", type: "text", length: 65535, nullable: true)]
    private ?string $description;

    #[ORM\Column(name: "status", type: "string", length: 50, nullable: true, options: ["default" => "en coure de traitement"])]
    private ?string $status;

    #[ORM\Column(name: "submissionDate", type: "datetime", nullable: true)]
    private ?\DateTimeInterface $submissionDate;

    #[ORM\Column(name: "updateDate", type: "datetime", nullable: true)]
    private ?\DateTimeInterface $updateDate;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "userId", referencedColumnName: "id")]
    private ?User $user;

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReclaimType(): ?string
    {
        return $this->reclaimType;
    }

    public function setReclaimType(?string $reclaimType): static
    {
        $this->reclaimType = $reclaimType;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSubmissionDate(): ?\DateTimeInterface
    {
        return $this->submissionDate;
    }

    public function setSubmissionDate(?\DateTimeInterface $submissionDate): static
    {
        $this->submissionDate = $submissionDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(?\DateTimeInterface $updateDate): static
    {
        $this->updateDate = $updateDate;

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
}
