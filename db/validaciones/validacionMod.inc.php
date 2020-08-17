<?php
include_once 'validacion.inc.php';
include_once 'db/repositorios/RepositorioUsuario.inc.php';

class validacionMod extends validacion {

    public function __construct($ci, $telefono, $nombre, $apellido, $fechaNac, $email, $conexion) {
        $this -> telefono = "";
        $this -> nombre = "";
        $this -> apellido = "";
        $this -> fechaNac = "";
        $this -> email = "";
        $this -> fecha = explode("/", $fechaNac);

        $this -> errorTelefono = $this -> validarTelefono($telefono);
        $this -> errorNombre = $this -> validarNombre($nombre);
        $this -> errorApellido = $this -> validarApellido($apellido);
        $this -> errorFechaNac = $this -> validarFechaNac($fechaNac);
        $this -> errorEmail = $this -> validarEmail($conexion, $ci, $email);
    }

    public function modificacionValida() {
        if ($this -> errorTelefono == "" &&
            $this -> errorNombre == "" &&
            $this -> errorApellido == "" &&
            $this -> errorFechaNac == "" &&
            $this -> errorEmail == "") {
            return true;
        } else {
            return false;
        }
    }
}