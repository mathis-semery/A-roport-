<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $prix_billet = null;

    #[ORM\ManyToOne(inversedBy: 'refVols')]
    #[ORM\JoinColumn(nullable: false)]
    private ?vol $refVol = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixBillet(): ?float
    {
        return $this->prix_billet;
    }

    public function setPrixBillet(float $prix_billet): static
    {
        $this->prix_billet = $prix_billet;

        return $this;
    }

    public function getRefVol(): ?vol
    {
        return $this->refVol;
    }

    public function setRefVol(?vol $refVol): static
    {
        $this->refVol = $refVol;

        return $this;
    }
}
