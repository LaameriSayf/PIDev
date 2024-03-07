<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2555, nullable: true)]
    private ?string $contenue = null;

    #[ORM\Column(nullable: true)]
    private ?bool $jaime = null;

    #[ORM\Column(nullable: true)]
    private ?bool $nejaimepas = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?blog $idblog = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?admin $idadmin = null;

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    public function setContenue(?string $contenue): static
    {
        $this->contenue = $contenue;

        return $this;
    }

    public function isJaime(): ?bool
    {
        return $this->jaime;
    }

    public function setJaime(?bool $jaime): static
    {
        $this->jaime = $jaime;

        return $this;
    }

    public function isNejaimepas(): ?bool
    {
        return $this->nejaimepas;
    }

    public function setNejaimepas(?bool $nejaimepas): static
    {
        $this->nejaimepas = $nejaimepas;

        return $this;
    }

    public function getIdblog(): ?blog
    {
        return $this->idblog;
    }

    public function setIdblog(?blog $idblog): static
    {
        $this->idblog = $idblog;

        return $this;
    }

    public function getIdadmin(): ?admin
    {
        return $this->idadmin;
    }

    public function setIdadmin(?admin $idadmin): static
    {
        $this->idadmin = $idadmin;

        return $this;
    }

   
}
