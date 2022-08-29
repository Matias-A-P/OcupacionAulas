<?php

namespace App\Entity;

use App\Repository\UserEdificiosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserEdificiosRepository::class)
 */
class UserEdificios
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userEdificios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Edificios::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $edificio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEdificio(): ?Edificios
    {
        return $this->edificio;
    }

    public function setEdificio(?Edificios $edificio): self
    {
        $this->edificio = $edificio;

        return $this;
    }
}
