<?php

namespace App\Entity;

use App\Repository\AvionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvionRepository::class)]
class Avion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'refAvions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Modele $refModele = null;

    /**
     * @var Collection<int, Vol>
     */
    #[ORM\OneToMany(targetEntity: Vol::class, mappedBy: 'refAvion')]
    private Collection $refVols;

    public function __construct()
    {
        $this->refVols = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getRefModele(): ?Modele
    {
        return $this->refModele;
    }

    public function setRefModele(?Modele $refModele): static
    {
        $this->refModele = $refModele;

        return $this;
    }

    /**
     * @return Collection<int, Vol>
     */
    public function getRefVols(): Collection
    {
        return $this->refVols;
    }

    public function addRefVol(Vol $refVol): static
    {
        if (!$this->refVols->contains($refVol)) {
            $this->refVols->add($refVol);
            $refVol->setRefAvion($this);
        }

        return $this;
    }

    public function removeRefVol(Vol $refVol): static
    {
        if ($this->refVols->removeElement($refVol)) {
            // set the owning side to null (unless already changed)
            if ($refVol->getRefAvion() === $this) {
                $refVol->setRefAvion(null);
            }
        }

        return $this;
    }
}
