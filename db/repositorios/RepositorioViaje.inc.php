<?php
include_once 'db/tablas/Viaje.inc.php';

class RepositorioViaje {
    public static function insertarViaje($conexion, $viaje) {
        $viajeInsertado = false;
        if (isset($conexion)) {
            try {
                $sql = "INSERT INTO viaje (fechaHoraSalida, fechaHoraLlegada, origen, destino, asientos, categoria, tarifa, wifi, idemp) VALUES (:fechaHoraSalida, :fechaHoraLlegada, :origen, :destino, :asientos, :categoria, :tarifa, :wifi, :idemp)";
                $sentencia = $conexion -> prepare($sql);

                $fechaHoraSalidaTemp = $viaje -> getFechaHoraSalida();
                $fechaHoraLlegadaTemp = $viaje -> getFechaHoraLlegada();
                $origenTemp = $viaje -> getOrigen();
                $destinoTemp = $viaje -> getDestino();
                $asientosTemp = $viaje -> getAsientos();
                $categoriaTemp = $viaje -> getCategoria();
                $tarifaTemp = $viaje -> getTarifa();
                $wifiTemp = $viaje -> getWifi();
                $idEmpTemp = $viaje -> getIDemp();
                
                $sentencia -> bindParam(':fechaHoraSalida', $fechaHoraSalidaTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':fechaHoraLlegada', $fechaHoraLlegadaTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':origen', $origenTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':destino', $destinoTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':asientos', $asientosTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':categoria', $categoriaTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':tarifa', $tarifaTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':wifi', $wifiTemp, PDO::PARAM_STR);
                $sentencia -> bindParam(':idemp', $idEmpTemp, PDO::PARAM_STR);

                $viajeInsertado = $sentencia -> execute();
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $viajeInsertado;
    }

    public static function getNom($conexion, $id) {
        $nombre = null;

        if (isset($conexion)) {
            try {
                $sql = "SELECT nombre FROM viaje v INNER JOIN empresa e ON v.idemp = e.id WHERE e.id = :id GROUP BY nombre";
                $sentencia = $conexion -> prepare($sql);
                $sentencia -> bindParam(':id' , $id, PDO::PARAM_STR);
                $sentencia -> execute();
                $resultado = $sentencia -> fetch();

                if (!empty($resultado)) {
                    $nombre = $resultado['nombre'];
                } else {
                    $nombre = -1;
                }
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $nombre;
    }

    public static function getViajes($conexion, $id) {
        $viajes = [];
        
        if (isset($conexion)) {
            try {
                $sql = "SELECT v.*, e.nombre FROM viaje v INNER JOIN empresa e ON v.idemp = e.id WHERE e.id = :id";
                $sentencia = $conexion -> prepare($sql);
                $sentencia -> bindParam(':id' , $id, PDO::PARAM_STR);
                $sentencia -> execute();
                $resultado = $sentencia -> fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
    					$viajes[] = array(
                            new Viaje(
    						    $fila['id'], $fila['fechaHoraSalida'], $fila['fechaHoraLlegada'], $fila['origen'], $fila['destino'], $fila['asientos'], $fila['categoria'], $fila['tarifa'], $fila['wifi'], $fila['idemp']
                            ),
                            $fila['nombre']
                        );
    				}
                } else {
                    $viajes = -1;
                }
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $viajes;
    }

    // Esta funcion solo es para el script relleno
    // Cuando la aplicación salga, cada uno vera si hay lugar disponible o no
    public static function restarAsiento($conexion, $id) {
        if (isset($conexion)) {
            try {
                $conexion -> beginTransaction();

                $sql = "UPDATE viaje SET asientos = asientos - 1 WHERE id = :id";
                $sentencia = $conexion -> prepare($sql);
                $sentencia -> bindParam(':id', $id, PDO::PARAM_STR);
                $sentencia -> execute();

                $conexion -> commit();
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
                $conexion -> rollBack();
            }
        }
    }

    public static function actualizarAsientos($conexion, $id, $asientos) {
        if (isset($conexion)) {
            try {
                $conexion -> beginTransaction();

                $sql = "UPDATE viaje SET asientos = $asientos WHERE id = :id";
                $sentencia = $conexion -> prepare($sql);
                $sentencia -> bindParam(':id', $id, PDO::PARAM_STR);
                $sentencia -> execute();

                $conexion -> commit();
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
                $conexion -> rollBack();
            }
        }
    }

    // BUSQUEDAS

    public static function buscar($conexion, $busqueda, $extra) {
        $viajes = [];

        $pal = explode('#', $busqueda);
        $origen = '%' . $pal[0] . '%';
        $destino = '%' . $pal[1] . '%';
        
        if ($pal[2] != '') { // Si se colocó fecha
            if (preg_match('/\-/', $pal[2])) { // Si contiene: ' - ' es que hay fecha de Ida y Vuelta
                $fecha = explode(' - ', $pal[2]);
                $fecha1 = str_replace('-', '', date('Y-m-d', strtotime(str_replace('/', '-', $fecha[0]))));
                $fecha2 = str_replace('-', '', date('Y-m-d', strtotime(str_replace('/', '-', $fecha[1]))));

                // Fecha más 1 dia
                $fecha1m = str_replace('-', '', date('Y-m-d', strtotime('+1 day', strtotime($fecha1))));
                $fecha2m = str_replace('-', '', date('Y-m-d', strtotime('+1 day', strtotime($fecha2))));
            } else {
                $fecha1 = str_replace('-', '', date('Y-m-d', strtotime(str_replace('/', '-', $pal[2]))));
                $fecha2 = '';
                
                // Fecha más 1 dia
                $fecha1m = str_replace('-', '', date('Y-m-d', strtotime('+1 day', strtotime($fecha1))));
            }
        } else {
            $fecha1 = '';
            $fecha2 = '';
        }
        
        if (isset($conexion)) {
            try {
                $sql = "SELECT v.*, e.nombre FROM viaje v INNER JOIN empresa e ON v.idemp = e.id WHERE origen LIKE :origen AND destino LIKE :destino";
                if ($fecha1 != '') {
                    if (preg_match('/\#vuelta/', $busqueda)) { // Si se coloca fecha de Vuelta, se invierten los lugares y la fecha de salida debe ser mayor o igual a la de llegada
                        $or = $origen;
                        $origen = $destino;
                        $destino = $or;
                        $sqlfecha = " AND (fechaHoraSalida >= :fecha2 AND fechaHoraSalida < :fecha2m)";
                    } else {
                        $sqlfecha = " AND (fechaHoraSalida >= :fecha1 AND fechaHoraSalida < :fecha1m)";
                    }
                } else {
                    if (preg_match('/\#vuelta/', $busqueda)) { // Si se coloca fecha de Vuelta, se invierten los lugares y la fecha de salida debe ser mayor o igual a la de llegada
                        $or = $origen;
                        $origen = $destino;
                        $destino = $or;
                    }
                    $sqlfecha = "";
                }

                $sql .= $sqlfecha;
                if ($extra != '') {
                    if ($extra == " Todas ORDER BY fechaHoraSalida") { // Si la busqueda se hizo desde el inicio
                        $sql .= " ORDER BY fechaHoraSalida";
                    } else { // Si la busqueda se hizo desde la busqueda avanzada
                        // Convertimos la sentencia en consultas
                        $extra = self :: avanzada($conexion, $extra);
                        $sql .= $extra;
                    }
                }

                $sentencia  = $conexion -> prepare($sql);
                $sentencia -> bindParam(':origen',  $origen, PDO::PARAM_STR);
                $sentencia -> bindParam(':destino',  $destino, PDO::PARAM_STR);
                if ($fecha1 != '' && !preg_match('/\#vuelta/', $busqueda)) {
                    $sentencia -> bindParam(':fecha1',  $fecha1, PDO::PARAM_STR);
                    $sentencia -> bindParam(':fecha1m',  $fecha1m, PDO::PARAM_STR);
                }
                if ($fecha2 != '' && preg_match('/\#vuelta/', $busqueda)) {
                    $sentencia -> bindParam(':fecha2',  $fecha2, PDO::PARAM_STR);
                    $sentencia -> bindParam(':fecha2m',  $fecha2m, PDO::PARAM_STR);
                }
                
                $sentencia -> execute();
                $resultado = $sentencia -> fetchAll();
                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $viajes[] = array(
                            new Viaje(
    						    $fila['id'], $fila['fechaHoraSalida'], $fila['fechaHoraLlegada'], $fila['origen'], $fila['destino'], $fila['asientos'], $fila['categoria'], $fila['tarifa'], $fila['wifi'], $fila['idemp']
                            ),
                            $fila['nombre']
                        );
    				}
                }
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $viajes;
    }

    public static function avanzada($conexion, $sentencia) {
        $cat = ''; // Categorias (Común, semicama, cama)
        $fsalida = ''; // Fecha de salida (Mañana, tarde, noche)
        $fllegada = ''; // Fecha de llegada (Mañana, tarde, noche)
        $compania = ''; // Compañia preferente
        $ord = ''; // Orden de elementos por: Fecha, 
        preg_match_all('/\[(.*?)\]/', $sentencia , $salida);
        foreach($salida[1] as $s) {
            if (preg_match('/(CAT)/', $s)) {
                $cat = $s;
            } else if (preg_match('/(FSALIDA)/', $s)) {
                $fsalida = $s;
            } else if (preg_match('/(FLLEGADA)/', $s)) {
                $fllegada = $s;
            } else if (preg_match('/(COMP)/', $s)) {
                $compania = $s;
            } else if (preg_match('/(ORD)/', $s)) {
                $ord = $s;
            }
        }
        $sql = '';
        $n = 0;

        // Categorías
        if ($cat != '') {
            $cat = explode(' - ', $cat);
            if (preg_match('/\,/', $cat[1])) { // Si hay mas de una categoria
                $cat = explode(',', $cat[1]);
                foreach ($cat as $c) {
                    if ($n > 0) {
                        $sql .= " OR categoria = '$c'";
                    } else {
                        $sql .= " AND (categoria = '$c'";
                    }
                    $n++;
                    if ($n == count($cat))
                        $sql .= ")";
                }
                $n = 0;
            } else {
                $sql .= " AND categoria = '$cat[1]'";
            }
        }

        $horario1 = '';
        $horario2 = '';
        // Fecha de ida
        if ($fsalida != '') {
            $fsalida = explode(' - ', $fsalida);
            if (preg_match('/\,/', $fsalida[1])) { // Si hay mas de una fecha de salida
                $fsalida = explode(',', $fsalida[1]);
                foreach ($fsalida as $fI) {
                    if ($fI == 'mañana') {
                        $horario1 = '00:00:00';
                        $horario2 = '12:59:59';
                    } else if ($fI == 'tarde') {
                        $horario1 = '13:00:00';
                        $horario2 = '18:59:59';
                    } else if ($fI == 'noche') {
                        $horario1 = '19:00:00';
                        $horario2 = '23:59:59';
                    }
                    if ($n > 0) {
                        $sql .= " OR (CAST(fechaHoraSalida as time) >= '$horario1' AND CAST(fechaHoraSalida as time) < '$horario2')";
                    } else {
                        $sql .= " AND ((CAST(fechaHoraSalida as time) >= '$horario1' AND CAST(fechaHoraSalida as time) < '$horario2')";
                    }
                    $n++;
                    if ($n == count($fsalida))
                        $sql .= ")";
                }
                $n = 0;
            } else {
                if ($fsalida[1] == 'mañana') {
                    $horario1 = '00:00:00';
                    $horario2 = '12:59:59';
                } else if ($fsalida[1] == 'tarde') {
                    $horario1 = '13:00:00';
                    $horario2 = '18:59:59';
                } else if ($fsalida[1] == 'noche') {
                    $horario1 = '19:00:00';
                    $horario2 = '23:59:59';
                }
                $sql .= " AND (CAST(fechaHoraSalida as time) >= '$horario1' AND CAST(fechaHoraSalida as time) < '$horario2')";
            }
        }

        // Fecha de llegada
        if ($fllegada != '') {
            $fllegada = explode(' - ', $fllegada);
            if (preg_match('/\,/', $fllegada[1])) { // Si hay mas de una fecha de llegada
                $fllegada = explode(',', $fllegada[1]);
                foreach ($fllegada as $fV) {
                    if ($fV == 'mañana') {
                        $horario1 = '00:00:00';
                        $horario2 = '12:59:59';
                    } else if ($fV == 'tarde') {
                        $horario1 = '13:00:00';
                        $horario2 = '18:59:59';
                    } else if ($fV == 'noche') {
                        $horario1 = '19:00:00';
                        $horario2 = '23:59:59';
                    }
                    if ($n > 0) {
                        $sql .= " OR (CAST(fechaHoraLlegada as time) >= '$horario1' AND CAST(fechaHoraLlegada as time) < '$horario2')";
                    } else {
                        $sql .= " AND ((CAST(fechaHoraLlegada as time) >= '$horario1' AND CAST(fechaHoraLlegada as time) < '$horario2')";
                    }
                    $n++;
                    if ($n == count($fllegada))
                        $sql .= ")";
                }
                $n = 0;
            } else {
                if ($fllegada[1] == 'mañana') {
                    $horario1 = '00:00:00';
                    $horario2 = '12:59:59';
                } else if ($fllegada[1] == 'tarde') {
                    $horario1 = '13:00:00';
                    $horario2 = '18:59:59';
                } else if ($fllegada[1] == 'noche') {
                    $horario1 = '19:00:00';
                    $horario2 = '23:59:59';
                }
                $sql .= " AND (CAST(fechaHoraLlegada as time) >= '$horario1' AND CAST(fechaHoraLlegada as time) < '$horario2')";
            }
        }

        // Compañia
        if (!preg_match('/(Todas)/', $compania)) {
            $compania = explode(' - ', $compania);
            $sql .= " AND e.nombre = '$compania[1]'";
        }

        // Orden
        if ($ord != '') {
            $ord = explode(' - ', $ord);
            $sql .= " ORDER BY " . $ord[1];
        }

        if (isset($conexion)) {
            try {
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        
        return $sql;
    }

    public static function autocompletar($conexion, $columna) {
        $viajes = [];

        if (isset($conexion)) {
            try {
                $sql = "SELECT $columna FROM viaje GROUP BY $columna ORDER BY $columna";
                $sentencia  = $conexion -> prepare($sql);

                $sentencia -> execute();
                $resultado = $sentencia -> fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
    					$viajes[] = $fila[$columna];
    				}
                }
            } catch (PDOException $ex) {
                print 'ERROR ' . $ex -> getMessage() . '<br>';
            }
        }
        return $viajes;
    }
}