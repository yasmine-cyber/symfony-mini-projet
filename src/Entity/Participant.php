<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant extends User
{
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'participant')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getRoles(): array
    {
        return ['ROLE_PARTICIPANT'];
    }
    public function eraseCredentials(): void
    {
        // Implémentation vide ou appel au parent
        parent::eraseCredentials();
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setParticipant($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getParticipant() === $this) {
                $reservation->setParticipant(null);
            }
        }

        return $this;
    }
}
