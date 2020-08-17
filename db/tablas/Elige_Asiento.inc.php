<?php
class Elige_Asiento {
    private $ciusu;
    private $idviaje;
    private $num_asiento;

    public function __construct($ciusu, $idviaje, $num_asiento) {
        $this -> ciusu = $ciusu;
        $this -> idviaje = $idviaje;
        $this -> num_asiento = $num_asiento;
    }

    public function getCIusu() {
        return $this -> ciusu;
    }

    public function getIDviaje() {
        return $this -> idviaje;
    }

    public function getNumAsiento() {
        return $this -> num_asiento;
    }
}