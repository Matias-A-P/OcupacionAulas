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

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $rep_semanal;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $rep_fecha_fin;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 0})
     */
    private $rep_id_padre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $user;

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

    public function getRepSemanal(): ?bool
    {
        return $this->rep_semanal;
    }

    public function setRepSemanal(bool $rep_semanal): self
    {
        $this->rep_semanal = $rep_semanal;

        return $this;
    }

    public function getRepFechaFin(): ?\DateTimeInterface
    {
        return $this->rep_fecha_fin;
    }

    public function setRepFechaFin(?\DateTimeInterface $rep_fecha_fin): self
    {
        $this->rep_fecha_fin = $rep_fecha_fin;

        return $this;
    }

    public function getRepIdPadre(): ?int
    {
        return $this->rep_id_padre;
    }

    public function setRepIdPadre(?int $rep_id_padre): self
    {
        $this->rep_id_padre = $rep_id_padre;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }
}
