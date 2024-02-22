<?php

namespace App\Entity;

use App\Repository\DossiermedicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Util\Json;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DossiermedicalRepository::class)]
class Dossiermedical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25555, nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !')]
    #[Assert\Length(max: 255, maxMessage: 'La longueur maximale est de 255 caractères.')]
    private ?string $resultatexamen = null;


    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Patient $patient = null;

    #[ORM\Column(nullable: true)]
     private ?array $antecedentspersonnelles = [];

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResultatexamen(): ?string
    {
        return $this->resultatexamen;
    }

    public function setResultatexamen(?string $resultatexamen): static
    {
        $this->resultatexamen = $resultatexamen;

        return $this;
    }

    

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getAntecedentspersonnelles(): array
    {
        return $this->antecedentspersonnelles;
    }

    public function setAntecedentspersonnelles(array $antecedentspersonnelles): static
    {
        $this->antecedentspersonnelles = $antecedentspersonnelles;

        return $this;
    }


    
   
    
    

   
}
