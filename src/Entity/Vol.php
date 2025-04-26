<?php

namespace App\Entity;

use App\Repository\VolRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolRepository::class)]
class Vol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $villeDepart = null;

    #[ORM\Column(length: 50)]
    private ?string $villeArrive = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDepart = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heureDepart = null;

    #[ORM\Column]
    private ?float $prixBillet = null;

    #[ORM\ManyToOne(targetEntity: Avion::class, inversedBy: 'vols')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Avion $refAvion = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $refPilote = null;

    // 🆕 Photo associée à la ville d'arrivée
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoVille = null;

    // 🆕 Propriété pour stocker l'URL de l'image de la ville
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cityImageUrl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVilleDepart(): ?string
    {
        return $this->villeDepart;
    }

    public function setVilleDepart(string $villeDepart): static
    {
        $this->villeDepart = $villeDepart;
        return $this;
    }

    public function getVilleArrive(): ?string
    {
        return $this->villeArrive;
    }

    public function setVilleArrive(string $villeArrive): static
    {
        $this->villeArrive = $villeArrive;
        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): static
    {
        $this->dateDepart = $dateDepart;
        return $this;
    }

    public function getHeureDepart(): ?\DateTimeInterface
    {
        return $this->heureDepart;
    }

    public function setHeureDepart(\DateTimeInterface $heureDepart): static
    {
        $this->heureDepart = $heureDepart;
        return $this;
    }

    public function getPrixBillet(): ?float
    {
        return $this->prixBillet;
    }

    public function setPrixBillet(float $prixBillet): static
    {
        $this->prixBillet = $prixBillet;
        return $this;
    }

    public function getRefAvion(): ?Avion
    {
        return $this->refAvion;
    }

    public function setRefAvion(?Avion $refAvion): static
    {
        $this->refAvion = $refAvion;
        return $this;
    }

    public function getRefPilote(): ?Utilisateur
    {
        return $this->refPilote;
    }

    public function setRefPilote(?Utilisateur $refPilote): static
    {
        $this->refPilote = $refPilote;
        return $this;
    }

    // 🆕 Getter et Setter pour la photo associée à la ville d'arrivée
    public function getPhotoVille(): ?string
    {
        return $this->photoVille;
    }

    public function setPhotoVille(?string $photoVille): static
    {
        $this->photoVille = $photoVille;
        return $this;
    }

    // 🆕 Getter et Setter pour l'URL de l'image de la ville
    public function getCityImageUrl(): ?string
    {
        return $this->cityImageUrl;
    }

    public function setCityImageUrl(?string $cityImageUrl): static
    {
        $this->cityImageUrl = $cityImageUrl;
        return $this;
    }
}
