<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin extends User
{
    #[ORM\Column(length: 255, nullable: true)]
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

    protected function getDiscr(): string
    {
        return 'admin';
    }
    
}