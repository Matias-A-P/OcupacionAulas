<?php

namespace App\Entity;

use App\Repository\CatedrasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CatedrasRepository::class)
 */
class Catedras
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
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Ocupacion::class, mappedBy="id_catedra")
     */
    private $ocupacions;

    public function __construct()
    {
        $this->ocupacions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|Ocupacion[]
     */
    public function getOcupacions(): Collection
    {
        return $this->ocupacions;
    }

    public function addOcupacion(Ocupacion $ocupacion): self
    {
        if (!$this->ocupacions->contains($ocupacion)) {
            $this->ocupacions[] = $ocupacion;
            $ocupacion->setIdCatedra($this);
        }

        return $this;
    }

    public function removeOcupacion(Ocupacion $ocupacion): self
    {
        if ($this->ocupacions->removeElement($ocupacion)) {
            // set the owning side to null (unless already changed)
            if ($ocupacion->getIdCatedra() === $this) {
                $ocupacion->setIdCatedra(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nombre;
    }
}
