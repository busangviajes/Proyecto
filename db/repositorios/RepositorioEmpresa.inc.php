<?php
include_once 'db/tablas/Empresa.inc.php';

class RepositorioEmpresa {
    public static function insertarEmpresa($conexion, $empresa) {
        $empresaInsertada = false;
        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO empresa (nombre, email) VALUES (:nombre, :email)";
                $sentencia = $conexion -> prepare($sql);

                $nomTemp = $empresa -> getNombre();
                $emailTemp = $empresa -> getEmail();

                $sentencia -> bindParam(':nombre', $nomTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':email', $emailTemp, PDO::PARAM_STR);

                $empresaInsertada = $sentencia -> execute();
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $empresaInsertada;
    }

    public static function getEmpresas($conexion) {
        $empresas = [];
        
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM empresa";
                $sentencia = $conexion -> prepare($sql);
                $sentencia -> execute();
                $resultado = $sentencia -> fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
    					$empresas[] = new Empresa(
                            $fila['id'], $fila['nombre'], $fila['email']
    					);
    				}
                }
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $empresas;
    }
}