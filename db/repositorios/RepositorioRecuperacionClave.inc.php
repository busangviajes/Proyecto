<?php

class RepositorioRecuperacionClave {

    public static function generarPeticion($conexion, $ciusu, $url) {
        $peticionGenerada = false;
        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO recuperacion_clave (ciusu, url, fecha) VALUES (:ciusu, 
                :url, NOW())";
                $sentencia = $conexion -> prepare($sql);

                $sentencia -> bindParam(':ciusu', $ciusu, PDO::PARAM_STR);
                $sentencia -> bindParam(':url', $url, PDO::PARAM_STR);

                $peticionGenerada = $sentencia -> execute();
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $peticionGenerada;
    }

    public static function getCIurl($conexion, $url) {
        $ciusuario = null;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM recuperacion_clave WHERE url = :url";
                $sentencia = $conexion -> prepare($sql);
                $sentencia -> bindParam(':url', $url, PDO::PARAM_STR);
                $sentencia -> execute();
                $resultado = $sentencia -> fetch();

                if (!empty($resultado)) {
                    $ciusuario = $resultado['ciusu'];
                } else {
                    $ciusuario = -1;
                }
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $ciusuario;
    }

    public static function usuarioExiste($conexion, $ciusu) {
        $usuarioExiste = true;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM recuperacion_clave WHERE ciusu = :ciusu";
                $sentencia = $conexion -> prepare($sql);
                $sentencia -> bindParam(':ciusu', $ciusu, PDO::PARAM_STR);
                $sentencia -> execute();
                $resultado = $sentencia -> fetchAll();

                if (count($resultado)) {
                    $usuarioExiste = true;
                } else {
                    $usuarioExiste = false;
                }
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $usuarioExiste;
    }

    public static function borrarPeticion($conexion, $ciusu) {
        if (isset($conexion)) {
            try {
                if (self :: usuarioExiste($conexion, $ciusu)) {
                    $sql = "DELETE FROM recuperacion_clave WHERE ciusu = :ciusu";
                    $sentencia = $conexion -> prepare($sql);
                    $sentencia -> bindParam(':ciusu', $ciusu, PDO::PARAM_STR);
                    $sentencia -> execute();
                }
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
    }
}