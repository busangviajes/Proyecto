<?php
class Empresa_Tel {
    private $id;
    private $telefono;

    public function __construct($id, $telefono) {
        $this -> id = $id;
        $this -> telefono = $telefono;
    }

    public function getID() {
        return $this -> id;
    }

    public function getTelefono() {
        return $this -> telefono;
    }

    public function setTelefono($telefono) {
        $this -> telefono = $telefono;
    }
}