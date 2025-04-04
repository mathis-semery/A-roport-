<?php

namespace App\Entity;

use App\Repository\ModeleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModeleRepository::class)]
class Modele
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $modele = null;

    #[ORM\Column(length: 50)]
    private ?string $marque = null;

    /**
     * @var Collection<int, Avion>
     */
    #[ORM\OneToMany(targetEntity: Avion::class, mappedBy: 'refModele', orphanRemoval: true)]
    private Collection $refAvions;

    public function __construct()
    {
        $this->refAvions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * @return Collection<int, Avion>
     */
    public function getRefAvions(): Collection
    {
        return $this->refAvions;
    }

    public function addRefAvion(Avion $refAvion): static
    {
        if (!$this->refAvions->contains($refAvion)) {
            $this->refAvions->add($refAvion);
            $refAvion->setRefModele($this);
        }

        return $this;
    }

    public function removeRefAvion(Avion $refAvion): static
    {
        if ($this->refAvions->removeElement($refAvion)) {
            // set the owning side to null (unless already changed)
            if ($refAvion->getRefModele() === $this) {
                $refAvion->setRefModele(null);
            }
        }

        return $this;
    }
}
