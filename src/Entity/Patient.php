<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Ordonnance; 
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

   
}
