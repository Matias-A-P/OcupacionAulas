<?php

namespace App\Entity;

use App\Repository\OcupacionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OcupacionRepository::class)
 */
class Ocupacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Aulas::class, inversedBy="ocupacions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_aula;

    /**
     * @ORM\ManyToOne(targetEntity=Catedras::class, inversedBy="ocupacions")
     */
    private $id_catedra;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(type="time")
     */
    private $hora_inicio;

    /**
     * @ORM\Column(type="time")
     */
    private $hora_fin;

    /**
     * @ORM\Column(type="integer")
     */
    private $comision;

    /**
     * @ORM\ManyToOne(targetEntity=Areas::class, inversedBy="ocupacions")
     * @Assert\NotBlank
     */
    private $id_area;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAula(): ?Aulas
    {
        return $this->id_aula;
    }

    public function setIdAula(?Aulas $id_aula): self
    {
        $this->id_aula = $id_aula;

        return $this;
    }

    public function getIdCatedra(): ?Catedras
    {
        return $this->id_catedra;
    }

    public function setIdCatedra(?Catedras $id_catedra): self
    {
        $this->id_catedra = $id_catedra;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getHoraInicio(): ?\DateTimeInterface
    {
        return $this->hora_inicio;
    }

    public function setHoraInicio(\DateTimeInterface $hora_inicio): self
    {
        $this->hora_inicio = $hora_inicio;

        return $this;
    }

    public function getHoraFin(): ?\DateTimeInterface
    {
        return $this->hora_fin;
    }

    public function setHoraFin(\DateTimeInterface $hora_fin): self
    {
        $this->hora_fin = $hora_fin;

        return $this;
    }

    public function getComision(): ?int
    {
        return $this->comision;
    }

    public function setComision(int $comision): self
    {
        $this->comision = $comision;

        return $this;
    }

    public function getIdArea(): ?Areas
    {
        return $this->id_area;
    }

    public function setIdArea(?Areas $id_area): self
    {
        $this->id_area = $id_area;

        return $this;
    }
}
