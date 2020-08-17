<?php
include_once 'db/Conexion.inc.php';
include_once 'db/repositorios/RepositorioViaje.inc.php';
include_once 'db/repositorios/RepositorioEligeAsiento.inc.php';

$busqueda = '';
$sql1 = '';
$sql2 = '';
$resultados1 = [];
$resultados2 = [];

if(isset($_POST['sentencia']) && isset($_POST['busqueda'])) {
    $busqueda = $_POST['busqueda'];
    $ord = ''; // Orden de elementos por: Fecha, 
    preg_match_all('/\[.*?\]/', $_POST['sentencia'], $salida);
    foreach($salida[0] as $s) {
        if (preg_match('/1/', $s)) {
            $sql1 .= $s;
        }
        if (preg_match('/2/', $s)) {
            $sql2 .= $s;
        } else {
            $ord = $s;
        }
    }
    
    $pal = explode('#', $busqueda);
    $origen = $pal[0];
    $destino = $pal[1];
    $fecha = $pal[2];
    Conexion :: abrirConexion();
    $resultados1 = RepositorioViaje :: buscar(Conexion :: getConexion(), $busqueda, $sql1 . $ord);
    
    if (count($resultados1)) {
        for ($a = 0; $a < count($resultados1); $a++) {
            $f1 = $resultados1[$a][0] -> getFechaHoraLlegada();
            
            if (preg_match('/\-/', $fecha) || ((!empty($origen) && !empty($destino)))) {
                // Si indico fecha de vuelta o coloco un origen y destino
                $resultados2 = RepositorioViaje :: buscar(Conexion :: getConexion(), $busqueda . '#vuelta', $sql2 . $ord);
                
                $cantidadResultados2 = count($resultados2);
                for ($b = 0; $b < $cantidadResultados2; $b++) {
                    $f2 = $resultados2[$b][0] -> getFechaHoraSalida();
                    if ($f1 > $f2) { // Si la fecha de llegada del origen es mayor a la fecha de salida del destino, no tiene sentido mostrarlo
                        unset($resultados2[$b]);
                    }
                }
                $resultados2 = array_merge($resultados2);
            } else {
                // Si no indico fecha de vuelta, no es necesario mostrar la segunda tabla
                $resultados2 = null;
            }
        }
    }
    Conexion :: cerrarConexion();
    include_once 'plantillas/tarjeta-viajes.inc.php';
} else {
    $resultados1 = [];
    $resultados2 = [];
}