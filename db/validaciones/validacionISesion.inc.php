<?php
include_once 'db/repositorios/RepositorioUsuario.inc.php';

class validacionISesion {
    private $usuario;
    private $error;

    public function __construct($nomusuario, $clave, $conexion) {
        $this -> error = "";

        if (!$this -> datoIniciado($nomusuario) || !$this -> datoIniciado($clave)) {
            $this -> usuario = null;
            $this -> error = "Debes introducir tu usuario y contraseña";
        } else {
            $this -> usuario = RepositorioUsuario :: getUsuario($conexion, $nomusuario);
            
            if (is_null($this -> usuario) || !password_verify($clave, $this -> usuario -> getClave())){
                $this -> error = "Datos incorrectos";
            }
        }
    }

    private function datoIniciado($dato) {
        if (isset($dato) && !empty($dato)) {
            return true;
        } else {
            return false;
        }
    }

    public function getUsuario() {
        return $this -> usuario;
    }

    public function getError() {
        return $this -> error;
    }

    public function mostrarError() {
        if ($this -> error !== '') {
            echo $this -> error;
        }
    }
}