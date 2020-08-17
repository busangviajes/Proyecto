<?php
include_once 'db/tablas/Empresa_Tel.inc.php';

class RepositorioEmpresaTel {
    public static function insertarEmpresaTelefono($conexion, $empTel) {
        $telefonoInsertado = false;
        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO empresa_tel (id, telefono) VALUES (:id, :telefono)";
                $sentencia = $conexion -> prepare($sql);

                $idTemp = $empTel -> getID();
                $telTemp = $empTel -> getTelefono();

                $sentencia -> bindParam(':id', $idTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':telefono', $telTemp, PDO::PARAM_STR);

                $telefonoInsertado = $sentencia -> execute();
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $telefonoInsertado;
    }

    public static function telEmpresa($conexion, $telefono) {
        $tel = null;

        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM empresa_tel WHERE telefono = :telefono";
                $sentencia = $conexion -> prepare($sql);
                $sentencia -> bindParam(':telefono', $telefono, PDO::PARAM_STR);
                $sentencia -> execute();
                $resultado = $sentencia -> fetch();

                if (!empty($resultado)) {
                    $tel = $resultado['telefono'];
                } else {
                    $tel = -1;
                }
            } catch (PDOException  $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $tel;
    }

    public static function getTelefonos($conexion, $id) {
        $telefonos = [];
        
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM empresa_tel WHERE id = :id";
                $sentencia = $conexion -> prepare($sql);

                $sentencia -> bindParam(':id' , $id, PDO::PARAM_STR);
                $sentencia -> execute();
                $resultado = $sentencia -> fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $telefonos[] = new Empresa_Tel(
                            $fila['id'], $fila['telefono']
                        );
                    }
                } else {
                    $telefonos = -1;
                }
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $telefonos;
    }
}