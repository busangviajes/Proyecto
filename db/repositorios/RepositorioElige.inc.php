<?php
include_once 'db/tablas/Elige.inc.php';

class RepositorioElige {
    public static function insertarElige($conexion, $elige) {
        $eligeInsertado = false;
        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO elige (ciusu, idviaje, fechaHoraCompra) VALUES (:ciusu, :idviaje, :fechaHoraCompra)";
                $sentencia = $conexion -> prepare($sql);

                $ciUsuTemp = $elige -> getCIusu();
                $idViajeTemp = $elige -> getIDviaje();
                $fechaHoraCompraTemp = $elige -> getFechaHoraCompra();

                $sentencia -> bindParam(':ciusu', $ciUsuTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':idviaje', $idViajeTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':fechaHoraCompra', $fechaHoraCompraTemp, PDO::PARAM_STR);

                $eligeInsertado = $sentencia -> execute();
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $eligeInsertado;
    }

    public static function getViajes($conexion, $ciusu) {
        $viajes = [];
        
        if (isset($conexion)) {
            try {
                $sql = "SELECT v.*, e.fechaHoraCompra, em.nombre FROM elige e INNER JOIN viaje v ON e.idviaje = v.id INNER JOIN empresa em ON v.idemp = em.id WHERE ciusu = :ciusu ORDER BY v.fechaHoraSalida DESC";
                $sentencia = $conexion -> prepare($sql);

                $sentencia -> bindParam(':ciusu' , $ciusu, PDO::PARAM_STR);
                $sentencia -> execute();
                $resultado = $sentencia -> fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $viajes[] = array(
                            new Viaje(
                                $fila['id'], $fila['fechaHoraSalida'], $fila['fechaHoraLlegada'], $fila['origen'], $fila['destino'], $fila['asientos'], $fila['categoria'], $fila['tarifa'], $fila['wifi'], $fila['idemp']
                            ),
                            $fila['fechaHoraCompra'], $fila['nombre']
                        );
    				}
                }
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $viajes;
    }

}