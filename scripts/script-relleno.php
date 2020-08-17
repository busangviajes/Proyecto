<?php
Conexion :: abrirConexion();

//------------------------------------------------------------------------------
//---------------------------E N T I D A D E S----------------------------------
//------------------------------------------------------------------------------

/*-----------------------------
---------U S U A R I O---------
-------------------------------*/

$nombres = ["José", "Pedro", "Raúl", "María", "Luisa", "Adriana", "Angela", "Alan", "Kevin", "Juan", "Pablo", "Luís", "Rodolfo", "Matías", "Leandro", "Ángel", "Esteban", "Mario", "Oscar", "Andrea", "Natalia", "Santiago", "Mariana", "Lucía", "Carlos", "Carla", "Lucas", "Ana", "Abigail", "Camila", "Fernanda", "Laura", "Cristina", "Claudia"];
$apellidos = ["Pérez", "Rodriguez", "Hernández", "Martínez", "García", "Sánchez", "Ávila", "Rivera", "López", "Silva", "Fernández", "Ferrer", "Juárez", "Castillo", "Méndez", "Ramos", "Cardozo", "Vázquez", "Gómez", "Iglesias", "Ruiz", "Benítez", "Hernández", "Jimenez", "Moreno", "Paredes"];

$domEmail = ["gmail", "yahoo", "hotmail", "live", "outlook", "gmx"];

$rep = 1;

for ($usuarios = 0; $usuarios < 20; $usuarios++) {
    $ci = mt_rand(11111111, 66666666);
    while (RepositorioUsuario :: getDatosUsuario(Conexion :: getConexion(), $ci)) {
        $ci = mt_rand(11111111, 66666666);
    }
    $telefono = '090909090';
    $nombre = $nombres[ mt_rand(0, count($nombres) - 1) ];
    $apellido = $apellidos[ mt_rand(0, count($apellidos) -1) ];
    $fechaNac = fecha_aleatoria("d/m/Y");
    // Eliminar acentos
    $nomusuario = acentos($nombre) . " " . acentos($apellido);
    // Transformar texto en minúscula
    $nomApe = explode(" ", strtolower($nomusuario));
    // Formar nombre de usuario con primera letra del nombre, seguido del apellido
    $nomusuario = ($nomApe[0])[0] . $nomApe[1];
    if (RepositorioUsuario :: getUsuario(Conexion :: getConexion(), $nomusuario)) {
        $nomusuario = $nomusuario . $rep;
        $rep++;
    }
    $clave = password_hash('123456', PASSWORD_DEFAULT);
    $email = $nomusuario . '@' . $domEmail[ mt_rand(0, count($domEmail) - 1)] . '.com';
    $activo = true;

    $usuario = new Usuario($ci, $telefono, $nombre, $apellido, $fechaNac, $nomusuario, $clave, $email, $activo);
    RepositorioUsuario :: insertarUsuario(Conexion :: getConexion(), $usuario);
}

$leangj7 = new Usuario('56377421', '095344474', 'Leandro', 'Juárez', '15/05/1998', 'leangj7', password_hash('leangj468273', PASSWORD_DEFAULT), 'leangj7@gmail.com', true);
RepositorioUsuario :: insertarUsuario(Conexion :: getConexion(), $leangj7);

/*-----------------------------
---------E M P R E S A---------
-------------------------------*/

$nombreEmpresas = ["COPAY", "COTMI", "NOSSAR", "EXPRESO MINUANO", "NUÑEZ", "COT", "AGENCIA CENTRAL", "CUT", "TURIL", "TURISMAR", "INTERTUR", "EGA"];

$idEmp = 1;

shuffle($nombreEmpresas);
foreach ($nombreEmpresas as $e) {
    $email = 'contacto@' . strtolower(str_replace(' ', '', acentos($e))) . '.com';

    $empresa = new Empresa('', $e, $email);
    RepositorioEmpresa :: insertarEmpresa(Conexion :: getConexion(), $empresa);

    /*-----------------------------
    ---------E M P R E S A---------
    --------T E L E F O N O--------
    -------------------------------*/

    // Pueden tener hasta 3 telefonos
    for ($tels = 0; $tels < rand(1, 3); $tels++) {
        $telefono = na(8);
        while (RepositorioEmpresaTel :: telEmpresa(Conexion :: getConexion(), $telefono) > 0 || !preg_match('/^2.*/', $telefono)) {
            $telefono = na(8);
        }
        $empresaTel = new Empresa_Tel($idEmp, $telefono);
        RepositorioEmpresaTel :: insertarEmpresaTelefono(Conexion :: getConexion(), $empresaTel);
    }

    /*-----------------------------
    ---------E M P R E S A---------
    -----------V I A J E-----------
    -------------------------------*/

    $lugares = ["Montevideo", "Pan de Azucar", "Punta del Este", "Pando", "Maldonado", "Rocha", "Piriapolis", "Colonia", "San Carlos", "Aguas Dulces", "Durazno"];
    $cat = ["Común", "Semi-cama", "Cama"];

    $fechaHoraSalida = date("Y-m-d H:i:s");
    $fechaHoraLlegada = date("Y-m-d H:i:s", strtotime($fechaHoraSalida . "+ " . rand(1, 7) . " hour"));

    for ($v = 0; $v < rand(5, 7); $v++) {
        $origen = $lugares[ mt_rand(0, count($lugares) - 1) ];
        $destino = $lugares[ mt_rand(0, count($lugares) - 1) ];
        while ($destino == $origen) {
            $destino = $lugares[ mt_rand(0, count($lugares) - 1) ];
        }
        $asientos = rand(40, 50);
        while ($asientos % 2 != 0) {
            $asientos = rand(40, 50);
        }
        $categoria = $cat[ mt_rand(0, count($cat) - 1) ];
        switch ($categoria) {
            case 'Común':
                $tarifa = rand(100, 200);
            break;
            case 'Semi-cama':
                $tarifa = rand(250, 350);
            break;
            case 'Cama':
                $tarifa = rand(400, 500);
            break;
        }

        if (rand(0, 1)) {
            $wifi = true;
        } else {
            $wifi = false;
        }
        
        $viaje = new Viaje('', $fechaHoraSalida, $fechaHoraLlegada, $origen, $destino, $asientos, $categoria, $tarifa, $wifi, $idEmp);
        RepositorioViaje :: insertarViaje(Conexion :: getConexion(), $viaje);
        $asientosTotales[$idEmp][$v] = $asientos;

        if (rand(0, 1)) {
            // Aleatoriamente, sumamos dias a las fechas para el siguiente viaje
            $fechaHoraSalida = date("Y-m-d H:i:s", strtotime($fechaHoraSalida . "+ " . rand(1, 7) . " day"));
            $fechaHoraLlegada = date("Y-m-d H:i:s", strtotime($fechaHoraSalida . "+ " . rand(1, 7) . " day"));
        }
    }
    $idEmp++;
}

/*-----------------------------
-----------E L I G E-----------
-------------------------------*/

// Cantidad de empresas en la base de datos
$empresas = RepositorioEmpresa :: getEmpresas(Conexion :: getConexion());

// Un recorrido por los 20 usuarios
for ($u = 0; $u < 20; $u++) {
    $fechaHoraCompra = date("Y-m-d H:i:s");
    if (rand(0, 1)) {
        // Aleatoriamente, sumamos dias a las fechas
        $fechaHoraCompra = date("Y-m-d H:i:s", strtotime($fechaHoraCompra . "+ " . rand(1, 7) . " day"));
    }
    
    $ci = RepositorioUsuario :: buscarCiIndice(Conexion :: getConexion(), $u);

    // Algunos harán viajes y otros no
    if (rand(0, 1)) {
        for ($i = 0; $i < count($empresas); $i++) {

            // Eligen una empresa
            if (rand(0, 1)) {

                $viajes = RepositorioViaje :: getViajes(Conexion :: getConexion(), $empresas[$i] -> getID());
                for ($v = 0; $v < count($viajes); $v++){

                    // Seleccionan el viaje
                    if (rand(0, 1)) {

                        $viaje = $viajes[$v][0];

                        // Solo podra viajar si la fecha de ida corresponde a la fecha de compra
                        // y hay asientos disponibles
                        if ($fechaHoraCompra <= $viaje -> getFechaHoraSalida() && $viaje -> getAsientos() > 0) {

                            $c = 0; // Cantidad de viajes que puede elegir un usuario
                            $eligioComprar = 0;
                            while ($c < 4) {

                                if (rand(0, 1)) {

                                    if ($eligioComprar == 0) {
                                        $elige = new Elige($ci, $viaje -> getID(), $fechaHoraCompra);
                                        RepositorioElige :: insertarElige(Conexion :: getConexion(), $elige);
                                    }

                                    $a = na(2); // numero de asiento

                                    //     Ver si el numero esta dentro de la cantidad de asientos del viaje
                                    //     Ver si no fue elegido por otro usuario
                                    
                                    while ($a <= 0 || $a > $viaje -> getAsientos() || RepositorioEligeAsiento :: getAsiento(Conexion :: getConexion(), $viaje -> getID(), $a)) {
                                        $a = na(2);
                                    }
                                    
                                    $eligeAsiento = new Elige_Asiento($ci, $viaje -> getID(), $a);
                                    RepositorioEligeAsiento :: insertarEligeAsiento(Conexion :: getConexion(), $eligeAsiento);
                                    // Al elegir un asiento, se resta de la cantidad total disponible
                                    RepositorioViaje :: restarAsiento(Conexion :: getConexion(), $viaje -> getID());

                                    $eligioComprar++;
                                }
                                $c++;
                            }
                        }
                    }
                }
            }
        }
    }
}

// Volver a colocar la cantidad de asientos comprados en cada viaje
for ($i = 0; $i < count($empresas); $i++) {
    $viajes = RepositorioViaje :: getViajes(Conexion :: getConexion(), $empresas[$i] -> getID());
    for ($v = 0; $v < count($viajes); $v++) {
        $asientosActuales = $viajes[$v][0] -> getAsientos();
        $asientosViajeTotales = $asientosTotales[$i + 1][$v];
        if ($asientosActuales < $asientosViajeTotales) {
            RepositorioViaje :: actualizarAsientos(Conexion :: getConexion(), $viajes[$v][0] -> getID(), $asientosTotales[$i + 1][$v]);
        }
    }
}

Conexion :: cerrarConexion();

function acentos($str) {
    $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
    $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
    return str_replace($a, $b, $str);
}

function sa($longitud) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numero_caracteres = strlen($caracteres);
    $string_aleatorio = '';
    
    for ($i = 0; $i < $longitud; $i++) {
        $string_aleatorio .= $caracteres[rand(0, $numero_caracteres - 1)];
    }
    
    return $string_aleatorio;
}

function saMayuscula($longitud) {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numero_caracteres = strlen($caracteres);
    $string_aleatorio = '';
    
    for ($i = 0; $i < $longitud; $i++) {
        $string_aleatorio .= $caracteres[rand(0, $numero_caracteres - 1)];
    }
    
    return $string_aleatorio;
}

function na($longitud) {
    $caracteres = '0123456789';
    $numero_caracteres = strlen($caracteres);
    $string_aleatorio = '';
    
    for ($i = 0; $i < $longitud; $i++) {
        $string_aleatorio .= $caracteres[rand(0, $numero_caracteres - 1)];
    }
    
    return $string_aleatorio;
}

function fecha_aleatoria($formato = "d/m/Y", $limiteInferior = "01/01/1970", $limiteSuperior = "01/01/2002"){
	$milisegundosLimiteInferior = strtotime($limiteInferior);
	$milisegundosLimiteSuperior = strtotime($limiteSuperior);
	$milisegundosAleatorios = mt_rand($milisegundosLimiteInferior, $milisegundosLimiteSuperior);
    return date($formato, $milisegundosAleatorios);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>¡Base de datos cargada!</h1>
</body>
</html>