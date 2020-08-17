<?php
include_once 'db/tablas/Elige_Asiento.inc.php';

class RepositorioEligeAsiento {
    public static function insertarEligeAsiento($conexion, $eligeAsiento) {
        $eligeAsientoInsertado = false;
        if (isset($conexion)) {
            try {
                
                $conexion -> beginTransaction();
                // Hacer consulta antes de insertar
                // Verificar uno por uno si los asientos elegidos para el viaje con idViaje no estan registrados por otro usuario
                // Si estan elegidos, eliminar al usuario de la tabla elige
                $sql = "INSERT INTO elige_asiento (ciusu, idviaje, num_asiento) VALUES (:ciusu, :idviaje, :num_asiento)";
                $sentencia = $conexion -> prepare($sql);

                $ciUsuTemp = $eligeAsiento -> getCIusu();
                $idViajeTemp = $eligeAsiento -> getIDviaje();
                $numAsientoTemp = $eligeAsiento -> getNumAsiento();

                $sentencia -> bindParam(':ciusu', $ciUsuTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':idviaje', $idViajeTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':num_asiento', $numAsientoTemp, PDO::PARAM_STR);

                $eligeAsientoInsertado = $sentencia -> execute();

                $conexion -> commit();
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
                // Si al momento de comprar, hubo otro usuario que compro el mismo asiento en el mismo viaje, el sistema debe dar una alerta de que la compra no se pudo llevar a cabo y
                // Permitirle al usuario volver al viaje para seleccionar nuevamente los asientos
                $conexion -> rollBack();
            }
        }
        return $eligeAsientoInsertado;
    }

    // Buscar si un asiento y viaje en especifico esta registrado/ocupado
    public static function getAsiento($conexion, $idViaje, $asiento) {
        $registrado = false;

        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM elige_asiento WHERE idviaje = $idViaje AND num_asiento = $asiento";
                $sentencia = $conexion -> prepare($sql);

                $sentencia -> execute();
                $resultado = $sentencia -> fetch();

                if (!empty($resultado)) {
                    $registrado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $registrado;
    }

    // Obtener todos los asientos de un viaje
    public static function getAsientosViaje($conexion, $idviaje) {
        $asiento = [];

        if (isset($conexion)) {
            try {
                $sql = "SELECT num_asiento FROM elige_asiento WHERE idviaje = $idviaje";
                $sentencia = $conexion -> prepare($sql);
                $sentencia -> execute();
                $resultado = $sentencia -> fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $asiento[] = array($fila['num_asiento']);
                    }
                }
            } catch (PDOException  $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $asiento;
    }
}