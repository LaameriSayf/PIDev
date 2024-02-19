<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(length: 2555, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datepub = null;

    #[ORM\Column(length: 2555, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieu = null;

    #[ORM\Column(nullable: true)]
    private ?float $rate = null;

    #[ORM\OneToMany(mappedBy: 'idblog', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\ManyToOne(inversedBy: 'idblog')]
    private ?Categorieblogs $categorieblogs = null;

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    private ?GlobalUser $id_user = null;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;

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

    public function getDatepub(): ?\DateTimeInterface
    {
        return $this->datepub;
    }

    public function setDatepub(?\DateTimeInterface $datepub): static
    {
        $this->datepub = $datepub;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setIdblog($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdblog() === $this) {
                $commentaire->setIdblog(null);
            }
        }

        return $this;
    }

    public function getCategorieblogs(): ?Categorieblogs
    {
        return $this->categorieblogs;
    }

    public function setCategorieblogs(?Categorieblogs $categorieblogs): static
    {
        $this->categorieblogs = $categorieblogs;

        return $this;
    }

    public function getIdadmin(): ?GlobalUser
    {
        return $this->id_user;
    }

    public function setIdadmin(?GlobalUser $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdUser(): ?GlobalUser
    {
        return $this->id_user;
    }

    public function setIdUser(?GlobalUser $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }
}
