<?php
include_once 'db/repositorios/RepositorioUsuario.inc.php';

class validacionImg {
    private $avisoInicio;
    private $avisoCierre;

    private $aviso;

    public function __construct($imagen, $usuario) {

        $this -> avisoInicio = "<div class='msjAlerta roja'>";
        $this -> avisoCierre = "</div>";

        $this -> aviso = $this -> validarImagen($imagen, $usuario);
    }

    private function datoIniciado($dato) {
        if (isset($dato) && !empty($dato)) {
            return true;
        } else {
            return false;
        }
    }

    private function validarImagen($imagen, $usuario) {
        if (!$this -> datoIniciado($imagen)) {
        } else {
            $directorio = DIRECTORIO_RAIZ . "/subidas/";
            $carpetaObjetivo = $directorio.basename($_FILES['img']['name']);
            $subidaCorrecta = 1;
            $tipoImagen = pathinfo($carpetaObjetivo, PATHINFO_EXTENSION);
            $comprobacion = getimagesize($_FILES['img']['tmp_name']);
            if ($comprobacion !== false) {
                $subidaCorrecta = 1;
            } else {
                $subidaCorrecta = 0;
            }

            if ($_FILES['img']['size'] > 500000) {
                return $this -> avisoInicio . "El archivo no puede ocupar más de 500kb" . $this -> avisoCierre;
                $subidaCorrecta = 0;
            }

            if ($tipoImagen != "jpg" && $tipoImagen != "png" && $tipoImagen != "jpeg") {
                return $this -> avisoInicio . "Sólo se admiten los formatos JPG, JPEG y PNG" . $this -> avisoCierre;
                $subidaCorrecta = 0;
            }

            if ($subidaCorrecta == 0) {
                return $this -> avisoInicio . "Tu archivo no puede subirse" . $this -> avisoCierre;
            } else {
                if (move_uploaded_file($_FILES['img']['tmp_name'], DIRECTORIO_RAIZ . "/subidas/" . $usuario -> getCI())) {
                    return "<div class='msjAlerta verde'>El archivo ".basename($_FILES['img']['name']). " ha sido subido.</div>";
                } else {
                    return $this -> avisoInicio . "Ha ocurrido un error." . $this -> avisoCierre;
                }
            }
            return "";
        }
    }

    public function getAviso() {
        return $this -> aviso;
    }

    public function mostrarAviso() {
        if ($this -> aviso !== "") {
            echo $this -> aviso;
        }
    }

    public function imagenValida() {
        if ($this -> aviso == "") {
            return true;
        } else {
            return false;
        }
    }
}