<?php
class Elige {
    private $ciusu;
    private $idviaje;
    private $fechaHoraCompra;

    public function __construct($ciusu, $idviaje, $fechaHoraCompra) {
        $this -> ciusu = $ciusu;
        $this -> idviaje = $idviaje;
        $this -> fechaHoraCompra = $fechaHoraCompra;
    }

    public function getCIusu() {
        return $this -> ciusu;
    }

    public function getIDviaje() {
        return $this -> idviaje;
    }

    public function getFechaHoraCompra() {
        return $this -> fechaHoraCompra;
    }
}