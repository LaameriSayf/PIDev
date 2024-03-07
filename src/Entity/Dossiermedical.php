<?php

namespace App\Entity;

use App\Repository\DossiermedicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Util\Json;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DossiermedicalRepository::class)]
class Dossiermedical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !')]
    #[Assert\Length(max: 255, maxMessage: 'La longueur maximale est de 255 caractères.')]
    private ?string $resultatexamen = null;


    
    #[ORM\Column(nullable: true)]
     private ?array $antecedentspersonnelles = [];

    #[ORM\OneToMany(mappedBy: 'dossiermedical', targetEntity: Ordonnance::class)]
    private Collection $ordonnance;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DateCreation = null;

    #[ORM\Column(nullable: true)]
    private ?int $numdossier = null;

    #[ORM\OneToOne(inversedBy: 'dossiermedical', cascade: ['persist', 'remove'])]
    private ?Patient $patient = null;

    #[ORM\Column(type:"string", nullable:true)]
    //#[assert\NotNull(message:"image is required")]
    private ?string $image;

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

   
    public function __construct()
    {
        $this->ordonnance = new ArrayCollection();
    }

    
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


    public function getAntecedentspersonnelles(): array
    {
        return $this->antecedentspersonnelles;
    }

    public function setAntecedentspersonnelles(array $antecedentspersonnelles): static
    {
        $this->antecedentspersonnelles = $antecedentspersonnelles;

        return $this;
    }

    /**
     * @return Collection<int, Ordonnance>
     */
    public function getOrdonnance(): Collection
    {
        return $this->ordonnance;
    }

    public function addOrdonnance(Ordonnance $ordonnance): static
    {
        if (!$this->ordonnance->contains($ordonnance)) {
            $this->ordonnance->add($ordonnance);
            $ordonnance->setDossiermedical($this);
        }

        return $this;
    }

    public function removeOrdonnance(Ordonnance $ordonnance): static
    {
        if ($this->ordonnance->removeElement($ordonnance)) {
            // set the owning side to null (unless already changed)
            if ($ordonnance->getDossiermedical() === $this) {
                $ordonnance->setDossiermedical(null);
            }
        }

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->DateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $DateCreation): static
    {
        $this->DateCreation = $DateCreation;

        return $this;
    }

    public function getNumdossier(): ?int
    {
        return $this->numdossier;
    }

    public function setNumdossier(?int $numdossier): static
    {
        $this->numdossier = $numdossier;

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
    
 


   

    
    }


    
   
    
    

   



