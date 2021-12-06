<?php

namespace App\Entity;

use App\Repository\EdificiosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EdificiosRepository::class)
 */
class Edificios
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $edificio;

    /**
     * @ORM\OneToMany(targetEntity=Aulas::class, mappedBy="id_edificio")
     */
    private $aulas;

    public function __construct()
    {
        $this->aulas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEdificio(): ?string
    {
        return $this->edificio;
    }

    public function setEdificio(string $edificio): self
    {
        $this->edificio = $edificio;

        return $this;
    }

    /**
     * @return Collection|Aulas[]
     */
    public function getAulas(): Collection
    {
        return $this->aulas;
    }

    public function addAula(Aulas $aula): self
    {
        if (!$this->aulas->contains($aula)) {
            $this->aulas[] = $aula;
            $aula->setIdEdificio($this);
        }

        return $this;
    }

    public function removeAula(Aulas $aula): self
    {
        if ($this->aulas->removeElement($aula)) {
            // set the owning side to null (unless already changed)
            if ($aula->getIdEdificio() === $this) {
                $aula->setIdEdificio(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->edificio;
    }
}
