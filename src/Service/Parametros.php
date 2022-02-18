<?php

namespace App\Service;

class Parametros
{
    private int $id_edificio;

    public function __construct()
    {
        $this->id_edificio = 99;
    }

    public function getIdEdificio(): ?int
    {
        return $this->id_edificio;
    }

    public function setIdEdificio(int $id_edificio)
    {
        $this->id_edificio = $id_edificio;
        return null;
    }
}

?>