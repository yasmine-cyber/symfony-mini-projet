<?php

namespace App\Entity;

use App\Repository\OrganisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganisateurRepository::class)]
class Organisateur extends User
{
    #[ORM\OneToMany(targetEntity: Evenement::class, mappedBy: 'organisateur')]
    private Collection $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    protected function getDiscr(): string
    {
        return 'organisateur'; // or 'admin' for Admin.php
    }

    public function eraseCredentials(): void
    {
        parent::eraseCredentials();
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): static
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->setOrganisateur($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): static
    {
        if ($this->evenements->removeElement($evenement)) {
            if ($evenement->getOrganisateur() === $this) {
                $evenement->setOrganisateur(null);
            }
        }

        return $this;
    }
}
