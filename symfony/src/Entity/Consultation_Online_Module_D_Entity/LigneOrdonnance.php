<?php
// src/Entity/LigneOrdonnance.php

namespace App\Consultation_Online_Module_D_Entity\Entity;

use App\Consultation_Online_Module_D_Repository\Repository\LigneOrdonnanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LigneOrdonnanceRepository::class)]
class LigneOrdonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lignes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ordonnance $ordonnance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du médicament est obligatoire")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le nom doit faire au moins {{ limit }} caractères",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $nomMedicament = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le dosage est obligatoire")]
    private ?string $dosage = null;

    #[ORM\Column(length: 50)]
    #[Assert\Choice(
        choices: ['COMPRIME', 'GELULE', 'SIROP', 'AMPOULE', 'POMMADE', 'CREME', 'GOUTTES', 'SUPPOSITOIRE'],
        message: "Forme invalide"
    )]
    private ?string $forme = 'COMPRIME';

    #[ORM\Column]
    #[Assert\Positive(message: "La quantité doit être positive")]
    #[Assert\Range(
        min: 1,
        max: 999,
        notInRangeMessage: "La quantité doit être entre {{ min }} et {{ max }}"
    )]
    private ?int $quantite = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La fréquence est obligatoire")]
    private ?string $frequence = null;

    #[ORM\Column]
    #[Assert\Positive(message: "La durée du traitement doit être positive")]
    private ?int $dureeTraitement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $instructions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdonnance(): ?Ordonnance
    {
        return $this->ordonnance;
    }

    public function setOrdonnance(?Ordonnance $ordonnance): static
    {
        $this->ordonnance = $ordonnance;
        return $this;
    }

    public function getNomMedicament(): ?string
    {
        return $this->nomMedicament;
    }

    public function setNomMedicament(string $nomMedicament): static
    {
        $this->nomMedicament = $nomMedicament;
        return $this;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(string $dosage): static
    {
        $this->dosage = $dosage;
        return $this;
    }

    public function getForme(): ?string
    {
        return $this->forme;
    }

    public function setForme(string $forme): static
    {
        $this->forme = $forme;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getFrequence(): ?string
    {
        return $this->frequence;
    }

    public function setFrequence(string $frequence): static
    {
        $this->frequence = $frequence;
        return $this;
    }

    public function getDureeTraitement(): ?int
    {
        return $this->dureeTraitement;
    }

    public function setDureeTraitement(int $dureeTraitement): static
    {
        $this->dureeTraitement = $dureeTraitement;
        return $this;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(?string $instructions): static
    {
        $this->instructions = $instructions;
        return $this;
    }
}