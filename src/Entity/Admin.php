<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Admin extends User
{
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $role_specifique = null;

    public function getRoleSpecifique(): ?string
    {
        return $this->role_specifique;
    }

    public function setRoleSpecifique(?string $role_specifique): static
    {
        $this->role_specifique = $role_specifique;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = parent::getRoles();
        $roles[] = 'ROLE_ADMIN';
        
        if ($this->role_specifique) {
            $roles[] = $this->role_specifique;
        }
        
        return array_unique($roles);
    }
    public function eraseCredentials(): void
    {
        // Implémentation vide car le parent (User) gère déjà cette méthode
        parent::eraseCredentials();
    }
}