<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use App\Repository\DossiermedicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Ordonnance; 
use App\Entity\Dossiermedical;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient extends Admin
{
   #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
     private ?int $id;

    #[ORM\Column(nullable: true)]
    private ?int $numcarte = null;


    #[ORM\OneToMany(mappedBy: 'idpatient', targetEntity: Rendezvous::class)]
    private Collection $rendezvouses;

    #[ORM\OneToOne(mappedBy: 'patient', cascade: ['persist', 'remove'])]
    private ?Dossiermedical $dossiermedical = null;

    #[ORM\OneToMany(mappedBy: 'idpatient', targetEntity: Ordonnance::class)]
    private Collection $ordonnances;

    public function __construct()
    {
        parent::__construct();
        $this->ordonnances = new ArrayCollection();
    }

   
   
   public function getId(): ?int
   {
        return $this->id;
   }

    public function getNumcarte(): ?int
    {
        return $this->numcarte;
    }

    public function setNumcarte(?int $numcarte): static
    {
        $this->numcarte = $numcarte;

        return $this;
    }


    /**
     * @return Collection<int, Rendezvous>
     */
    public function getRendezvouses(): Collection
    {
        return $this->rendezvouses;
    }

    public function addRendezvouse(Rendezvous $rendezvouse): static
    {
        if (!$this->rendezvouses->contains($rendezvouse)) {
            $this->rendezvouses->add($rendezvouse);
            $rendezvouse->setIdpatient($this);
        }

        return $this;
    }

    public function removeRendezvouse(Rendezvous $rendezvouse): static
    {
        if ($this->rendezvouses->removeElement($rendezvouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezvouse->getIdpatient() === $this) {
                $rendezvouse->setIdpatient(null);
            }
        }

        return $this;
    

  

        return $this;
    }

    public function getDossiermedical(): ?Dossiermedical
    {
        return $this->dossiermedical;
    }

    public function setDossiermedical(?Dossiermedical $dossiermedical): static
    {
        // unset the owning side of the relation if necessary
        if ($dossiermedical === null && $this->dossiermedical !== null) {
            $this->dossiermedical->setPatient(null);
        }

        // set the owning side of the relation if necessary
        if ($dossiermedical !== null && $dossiermedical->getPatient() !== $this) {
            $dossiermedical->setPatient($this);
        }

        $this->dossiermedical = $dossiermedical;

        return $this;
    }

    /**
     * @return Collection<int, Ordonnance>
     */
    public function getOrdonnances(): Collection
    {
        return $this->ordonnances;
    }

    public function addOrdonnance(Ordonnance $ordonnance): static
    {
        if (!$this->ordonnances->contains($ordonnance)) {
            $this->ordonnances->add($ordonnance);
            $ordonnance->setIdpatient($this);
        }

        return $this;
    }

    public function removeOrdonnance(Ordonnance $ordonnance): static
    {
        if ($this->ordonnances->removeElement($ordonnance)) {
            // set the owning side to null (unless already changed)
            if ($ordonnance->getIdpatient() === $this) {
                $ordonnance->setIdpatient(null);
            }
        }

        return $this;
    }


   
   
}
