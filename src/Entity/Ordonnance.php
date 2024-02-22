<?php

namespace App\Entity;

use App\Repository\OrdonnanceRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use DateTime;
#[ORM\Entity(repositoryClass: OrdonnanceRepository::class)]
class Ordonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;
    

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !')]
    private ?\DateTimeInterface $renouvellement = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "Votre description doit contenir au moins {{ limit }} caractères.")]
    #[Assert\NotBlank(message:"Champs obligatoire !")]
    private ?string $medecamentprescrit = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !')]
    #[Assert\NotBlank(message:"Champs obligatoire")]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !')]
    private ?\DateTimeInterface $dateprescription = null;

    #[ORM\ManyToOne(inversedBy: 'ordonnances_patient')]
    private ?Patient $patient ;

    #[ORM\ManyToOne(inversedBy: 'ordonnances')]
    private ?Dossiermedical $dossier = null;

    public function getRenouvellement(): ?\DateTimeInterface
    {
        return $this->renouvellement;
    }

    public function setRenouvellement(\DateTimeInterface $renouvellement): static
    {
        $this->renouvellement = $renouvellement;

        return $this;
    }

    public function getMedecamentprescrit(): ?string
    {
        return $this->medecamentprescrit;
    }

    public function setMedecamentprescrit(string $medecamentprescrit): static
    {
        $this->medecamentprescrit = $medecamentprescrit;

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

    public function getDateprescription(): ?\DateTimeInterface
    {
        return $this->dateprescription;
    }

    public function setDateprescription( $dateprescription): static
    {
        $this->dateprescription = $dateprescription;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDossier(): ?Dossiermedical
    {
        return $this->dossier;
    }

    public function setDossier(?Dossiermedical $dossier): static
    {
        $this->dossier = $dossier;

        return $this;
    }
    

}
