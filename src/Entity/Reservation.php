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
    private ?int $nombre_de_places = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date_reservation = null;

    #[ORM\ManyToOne(targetEntity: Participant::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participant $participant = null;

    #[ORM\ManyToOne(targetEntity: Evenement::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Evenement $evenement = null;

    public function __construct()
    {
        $this->date_reservation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreDePlaces(): ?int
    {
        return $this->nombre_de_places;
    }

    public function setNombreDePlaces(int $nombre_de_places): static
    {
        $this->nombre_de_places = $nombre_de_places;
        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->date_reservation;
    }

    public function setDateReservation(\DateTimeInterface $date_reservation): static
    {
        $this->date_reservation = $date_reservation;
        return $this;
    }

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(?Participant $participant): static
    {
        $this->participant = $participant;
        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): static
    {
        $this->evenement = $evenement;
        return $this;
    }
}
