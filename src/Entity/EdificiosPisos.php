<?php

namespace App\Entity;

use App\Repository\EdificiosPisosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EdificiosPisosRepository::class)
 */
class EdificiosPisos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Edificios::class, inversedBy="edificiosPisos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_edificio;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $piso;

    /**
     * @ORM\OneToMany(targetEntity=Aulas::class, mappedBy="id_piso")
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

    public function getIdEdificio(): ?Edificios
    {
        return $this->id_edificio;
    }

    public function setIdEdificio(?Edificios $id_edificio): self
    {
        $this->id_edificio = $id_edificio;

        return $this;
    }

    public function getPiso(): ?string
    {
        return $this->piso;
    }

    public function setPiso(string $piso): self
    {
        $this->piso = $piso;

        return $this;
    }

    /**
     * @return Collection<int, Aulas>
     */
    public function getAulas(): Collection
    {
        return $this->aulas;
    }

    public function addAula(Aulas $aula): self
    {
        if (!$this->aulas->contains($aula)) {
            $this->aulas[] = $aula;
            $aula->setIdPiso($this);
        }

        return $this;
    }

    public function removeAula(Aulas $aula): self
    {
        if ($this->aulas->removeElement($aula)) {
            // set the owning side to null (unless already changed)
            if ($aula->getIdPiso() === $this) {
                $aula->setIdPiso(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->piso;
    }
}
