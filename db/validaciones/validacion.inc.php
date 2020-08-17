<?php

abstract class validacion {
    
    protected $telefono;
    protected $nombre;
    protected $apellido;
    protected $fechaNac;
    protected $email;

    protected $fecha;

    protected $errorTelefono;
    protected $errorNombre;
    protected $errorApellido;
    protected $errorFechaNac;
    protected $errorEmail;

    function __construct() {
    }

    protected function datoIniciado($dato) {
        if (isset($dato) && !empty($dato)) {
            return true;
        } else {
            return false;
        }
    }

    protected function validarTelefono($telefono) {
        if (!$this -> datoIniciado($telefono)) {
            return "Debes colocar tu teléfono";
        } else {
            if (strlen($telefono) < 7 || strlen($telefono) > 10) {
                return "Longitud de teléfono incorrecta";
            } else {
                if (!preg_match('`^[\d]+$`', $telefono)) {
                    return "Sólo puedes colocar números";
                } else {
                    $this -> telefono = $telefono;
                }
            }
        }
        return "";
    }

    protected function validarNombre($nombre) {
        if (!$this -> datoIniciado($nombre)) {
            return "Debes colocar tu nombre";
        } else {
            if (strlen($nombre) > 200) {
                return "Límite excedido (200)";
            } else {
                if (!preg_match('`^[a-zA-ZáéíóúÁÉÍÓÚ]+$`', $nombre)) {
                    return "Sólo puedes colocar letras";
                } else {
                    $this -> nombre = $nombre;
                }
            }
        }
        return "";
    }

    protected function validarApellido($apellido) {
        if ($this -> datoIniciado($apellido)) {
            if (strlen($apellido) > 200) {
                return "Límite excedido (200)";
            } else {
                if (!preg_match('`^[a-zA-ZáéíóúÁÉÍÓÚ]+$`', $apellido)) {
                    return "Sólo puedes colocar letras";
                } else {
                    $this -> apellido = $apellido;
                }
            }
        }
        return "";
    }

    protected function validarFechaNac($fechaNac) {
        if (!$this -> datoIniciado($this -> fecha[0]) || $this -> fecha[1] == 'Mes' || !$this -> datoIniciado($this -> fecha[2])) {
            return "Debes colocar tu fecha de nacimiento completa";
        } else {
            if ($this -> fecha[1] == '04' || $this -> fecha[1] == '06' || $this -> fecha[1] == '09' || $this -> fecha[1] == '11') {
                if ($this -> fecha[0] > 30) {
                    $this -> fecha[0] = "";
                    $this -> fecha[1] = "";
                    $this -> fechaNac = "//" . $this -> fecha[2];
                    return "No es una fecha válida";
                }
            } else if ($this -> fecha[1] == '02') {
                if ($this -> fecha[0] > 28) {
                    if (!(($this -> fecha[2] % 4 == 0) && (($this -> fecha[2] % 100 != 0) || ($this -> fecha[2] % 400 == 0)))) {
                        $this -> fecha[0] = "";
                        $this -> fecha[1] = "";
                        $this -> fechaNac = "//" . $this -> fecha[2];
                        return "No es una fecha válida";
                    }
                }
            }
            $this -> fechaNac = $fechaNac;
        }
        return "";
    }

    protected function validarEmail($conexion, $ci, $email) {
        if (!$this -> datoIniciado($email)) {
            return "Debes colocar tu email";
        } else {
            if (strlen($email) > 255) {
                return "Límite excedido (255)";
            } else {
                $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return "No es un email válido";
                } else {
                    if (!is_null(RepositorioUsuario :: getUsuarioEmail($conexion, $email))) {
                        if (!RepositorioUsuario :: usuario_email($conexion, $ci, $email))
                            return "Este email ya está registrado";
                    } else {
                        $this -> email = $email;
                    }
                }
            }
        }
        return "";
    }

    public function getTelefono() {
        return $this -> telefono;
    }

    public function getErrorTelefono() {
        return $this -> errorTelefono;
    }

    public function getNombre() {
        return $this -> nombre;
    }

    public function getErrorNombre() {
        return $this -> errorNombre;
    }

    public function getApellido() {
        return $this -> apellido;
    }

    public function getErrorApellido() {
        return $this -> errorApellido;
    }

    public function getFechaNac() {
        return $this -> fechaNac;
    }

    public function getErrorFechaNac() {
        return $this -> errorFechaNac;
    }
    
    public function getEmail() {
        return $this -> email;
    }

    public function getErrorEmail() {
        return $this -> errorEmail;
    }

    public function mostrarTelefono() {
        if ($this -> telefono !== "") {
            echo 'value="'. $this -> telefono . '"';
        }
    }

    public function mostrarErrorTelefono() {
        if ($this -> errorTelefono !== ""){
            echo $this -> errorTelefono;
        }
    }

    public function mostrarNombre() {
        if ($this -> nombre !== "") {
            echo 'value="'. $this -> nombre . '"';
        }
    }

    public function mostrarErrorNombre() {
        if ($this -> errorNombre !== ""){
            echo $this -> errorNombre;
        }
    }

    public function mostrarApellido() {
        if ($this -> apellido !== "") {
            echo 'value="'. $this -> apellido . '"';
        }
    }

    public function mostrarErrorApellido() {
        if ($this -> errorApellido !== ""){
            echo $this -> errorApellido;
        }
    }

    public function mostrarFechaNacDia() {
        if ($this -> fechaNac !== "") {
            echo 'value="'. $this -> fecha[0] . '"';
        }
    }

    public function mostrarFechaNacMes() {
        if ($this -> fechaNac !== "") {
            echo 'document.getElementById("fechaNacMes").selectedIndex = "' . $this -> fecha[1] . '"';
        }
    }

    public function mostrarFechaNacAnio() {
        if ($this -> fechaNac !== "") {
            echo 'value="'. $this -> fecha[2] . '"';
        }
    }

    public function mostrarErrorFechaNac() {
        if ($this -> errorFechaNac !== ""){
            echo $this -> errorFechaNac;
        }
    }

    public function mostrarEmail() {
        if ($this -> email !== "") {
            echo 'value="'. $this -> email . '"';
        }
    }

    public function mostrarErrorEmail() {
        if ($this -> errorEmail !== ""){
            echo $this -> errorEmail;
        }
    }
}