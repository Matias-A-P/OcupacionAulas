<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AreasRepository;

/**
 * @ORM\Entity(repositoryClass=AreasRepository::class)
 */
class Areas
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $area;

    /**
     * @ORM\OneToMany(targetEntity=Catedras::class, mappedBy="area")
     * @ORM\OrderBy({"nombre" = "ASC"})
     */
    private $catedras;

    /**
     * @ORM\OneToMany(targetEntity=Ocupacion::class, mappedBy="id_area")
     */
    private $ocupacions;

    public function __construct()
    {
        $this->catedras = new ArrayCollection();
        $this->ocupacions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setArea(string $area): self
    {
        $this->area = $area;

        return $this;
    }

    /**
     * @return Collection|Catedras[]
     */
    public function getCatedras(): Collection
    {
        return $this->catedras;
    }

    public function addCatedra(Catedras $catedra): self
    {
        if (!$this->catedras->contains($catedra)) {
            $this->catedras[] = $catedra;
            $catedra->setArea($this);
        }

        return $this;
    }

    public function removeCatedra(Catedras $catedra): self
    {
        if ($this->catedras->removeElement($catedra)) {
            // set the owning side to null (unless already changed)
            if ($catedra->getArea() === $this) {
                $catedra->setArea(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->area;
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
            $ocupacion->setIdArea($this);
        }

        return $this;
    }

    public function removeOcupacion(Ocupacion $ocupacion): self
    {
        if ($this->ocupacions->removeElement($ocupacion)) {
            // set the owning side to null (unless already changed)
            if ($ocupacion->getIdArea() === $this) {
                $ocupacion->setIdArea(null);
            }
        }

        return $this;
    }
}
