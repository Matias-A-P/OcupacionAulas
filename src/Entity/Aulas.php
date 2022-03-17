<?php

namespace App\Entity;

use App\Repository\AulasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AulasRepository::class)
 */
class Aulas
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
    private $aula;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacidad;

    /**
     * @ORM\OneToMany(targetEntity=Ocupacion::class, mappedBy="id_aula")
     */
    private $ocupacions;

    /**
     * @ORM\ManyToOne(targetEntity=Edificios::class, inversedBy="aulas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_edificio;

    /**
     * @ORM\ManyToOne(targetEntity=EdificiosPisos::class, inversedBy="aulas")
     */
    private $id_piso;

    public function __construct()
    {
        $this->ocupacions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAula(): ?string
    {
        return $this->aula;
    }

    public function setAula(string $aula): self
    {
        $this->aula = $aula;

        return $this;
    }

    public function getCapacidad(): ?int
    {
        return $this->capacidad;
    }

    public function setCapacidad(?int $capacidad): self
    {
        $this->capacidad = $capacidad;

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
            $ocupacion->setIdAula($this);
        }

        return $this;
    }

    public function removeOcupacion(Ocupacion $ocupacion): self
    {
        if ($this->ocupacions->removeElement($ocupacion)) {
            // set the owning side to null (unless already changed)
            if ($ocupacion->getIdAula() === $this) {
                $ocupacion->setIdAula(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->aula;
    }

    public function getIdEdificio(): ?Edificios
    {
        return $this->id_edificio;
    }

    public function setIdEdificio(?Edificios $id_edificio): self
    {
        $this->id_edificio = $id_edificio;

        return $this;
    }

    public function getIdPiso(): ?EdificiosPisos
    {
        return $this->id_piso;
    }

    public function setIdPiso(?EdificiosPisos $id_piso): self
    {
        $this->id_piso = $id_piso;

        return $this;
    }
}
