<?php
include_once 'validacionClave.inc.php';
include_once 'db/repositorios/RepositorioUsuario.inc.php';

class validacionBaja extends validacionClave {

    private $errorClaveRegistrada;

    public function __construct($id, $clave, $repClave, $conexion) {
        $this -> clave = "";
        $this -> repClave = "";

        $this -> errorClave = $this -> validarClave($clave);
        $this -> errorRepClave = $this -> validarRepClave($clave, $repClave);
        $this -> errorClaveRegistrada = $this -> validarClaveRegistrada($id, $clave, $conexion);
    }

    // Sobreescribo el método de validar clave para que solo verifique si se colocó una clave
    private function validarClave($clave) {
        if (!$this -> datoIniciado($clave)) {
            return "Debes colocar tu contraseña";
        }
        return "";
    }

    private function validarClaveRegistrada($id, $clave, $conexion) {
        if ($this -> datoIniciado($clave)) {
            $this -> usuario = RepositorioUsuario :: getDatosUsuario($conexion, $id);
            if(!password_verify($clave, $this -> usuario -> getClave())) {
                return "Tu contraseña es incorrecta";
            }
        }
    }

    public function getErrorClaveRegistrada() {
        return $this -> errorClaveRegistrada;
    }

    public function mostrarErrorClaveRegistrada() {
        if ($this -> errorClaveRegistrada !== ""){
            echo $this -> errorClaveRegistrada;
        }
    }

    public function claveValida() {
        if ($this -> errorClave == "" &&
            $this -> errorRepClave == "" &&
            $this -> errorClaveRegistrada == "") {
            return true;
        } else {
            return false;
        }
    }
}