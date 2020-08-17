<?php
class Viaje {
    private $id;
    private $fechaHoraSalida;
    private $fechaHoraLlegada;
    private $origen;
    private $destino;
    private $asientos;
    private $categoria;
    private $tarifa;
    private $wifi;
    private $idemp;

    public function __construct($id, $fechaHoraSalida, $fechaHoraLlegada, $origen, $destino, $asientos, $categoria, $tarifa, $wifi, $idemp) {
        $this -> id = $id;
        $this -> fechaHoraSalida = $fechaHoraSalida;
        $this -> fechaHoraLlegada = $fechaHoraLlegada;
        $this -> origen = $origen;
        $this -> destino = $destino;
        $this -> asientos = $asientos;
        $this -> categoria = $categoria;
        $this -> tarifa = $tarifa;
        $this -> wifi = $wifi;
        $this -> idemp = $idemp;
    }

    public function getID() {
        return $this -> id;
    }

    public function getFechaHoraSalida() {
        return $this -> fechaHoraSalida;
    }

    public function getFechaHoraLlegada() {
        return $this -> fechaHoraLlegada;
    }

    public function getOrigen() {
        return $this -> origen;
    }

    public function getDestino() {
        return $this -> destino;
    }

    public function getAsientos() {
        return $this -> asientos;
    }

    public function getCategoria() {
        return $this -> categoria;
    }

    public function getTarifa() {
        return $this -> tarifa;
    }

    public function getWifi() {
        return $this -> wifi;
    }

    public function getIDemp() {
        return $this -> idemp;
    }

    public function setFechaHoraSalida($fechaHoraSalida) {
        $this -> fechaHoraSalida = $fechaHoraSalida;
    }

    public function setFechaHoraLlegada($fechaHoraLlegada) {
        $this -> fechaHoraLlegada = $fechaHoraLlegada;
    }

    public function setOrigen($origen) {
        $this -> origen = $origen;
    }

    public function setDestino($destino) {
        $this -> destino = $destino;
    }

    public function setAsientos($asientos) {
        $this -> asientos = $asientos;
    }

    public function setCategoria($categoria) {
        $this -> categoria = $categoria;
    }

    public function setTarifa($tarifa) {
        $this -> tarifa = $tarifa;
    }

    public function setWifi($wifi) {
        $this -> wifi = $wifi;
    }
}