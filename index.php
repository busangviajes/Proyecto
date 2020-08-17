<?php
include_once 'db/config.inc.php';
include_once 'db/Conexion.inc.php';
include_once 'db/Redireccion.inc.php';
include_once 'db/ControlSesion.inc.php';

// Tablas
include_once 'db/tablas/Elige.inc.php';
include_once 'db/tablas/Elige_Asiento.inc.php';
include_once 'db/tablas/Empresa_Tel.inc.php';
include_once 'db/tablas/Empresa.inc.php';
include_once 'db/tablas/Usuario.inc.php';
include_once 'db/tablas/Viaje.inc.php';


// Repositorios
include_once 'db/repositorios/RepositorioElige.inc.php';
include_once 'db/repositorios/RepositorioEligeAsiento.inc.php';
include_once 'db/repositorios/RepositorioEmpresa.inc.php';
include_once 'db/repositorios/RepositorioEmpresaTel.inc.php';
include_once 'db/repositorios/RepositorioRecuperacionClave.inc.php';
include_once 'db/repositorios/RepositorioUsuario.inc.php';
include_once 'db/repositorios/RepositorioViaje.inc.php';

$componentes_url = parse_url($_SERVER['REQUEST_URI']);

$ruta = $componentes_url['path'];

$partes_ruta = explode('/', $ruta);
$partes_ruta = array_filter($partes_ruta);
$partes_ruta = array_slice($partes_ruta, 0);

$ruta_elegida = 'vistas/404.php';

if ($partes_ruta[0] == 'busangv3') {
    if (count($partes_ruta) == 1) {
        $ruta_elegida = 'vistas/sesion/bienvenida.php';
    } else if (count($partes_ruta) == 2) {
        switch ($partes_ruta[1]) {
            case 'informacion':
                $ruta_elegida = 'info.php';
            break;
            // SESION
            case 'ingresar':
                $ruta_elegida = 'vistas/sesion/inicio-sesion.php';
            break;
            case 'registro';
                $ruta_elegida = 'vistas/sesion/registro.php';
            break;
            case 'gracias-por-registrarte':
                $ruta_elegida = 'vistas/sesion/registro-correcto.php';
            break;
            case 'error-registro':
                $ruta_elegida = 'vistas/sesion/registro-incorrecto.php';
            break;
            case 'recuperar-clave':
                $ruta_elegida = 'vistas/sesion/recuperar-clave.php';
            break;
            case 'clave-correcta':
                $ruta_elegida = 'vistas/sesion/clave-correcta.php';
            break;
            // PRINCIPAL
            case 'inicio':
                $ruta_elegida = 'vistas/principal/inicio.php';
            break;
            case 'acerca-de':
                $ruta_elegida = 'vistas/principal/acerca-de.php';
            break;
            case 'faq':
                $ruta_elegida = 'vistas/principal/faq.php';
            break;
            case 'contacto':
                $ruta_elegida = 'vistas/principal/contacto.php';
            break;
            case 'resultados':
                $ruta_elegida = 'vistas/principal/resultados.php';
            break;
            case 'compra':
                $ruta_elegida = "vistas/principal/compra.php";
            break;
            // PERFIL
            case 'perfil':
                $ruta_elegida = 'vistas/perfil/datos-personales.php';
            break;
            case 'modificar-perfil':
                $ruta_elegida = 'vistas/perfil/modificar-perfil.php';
            break;
            case 'viajes':
                $ruta_elegida = 'vistas/perfil/viajes.php';
            break;
            case 'salir':
                $ruta_elegida = 'vistas/perfil/cerrar-sesion.php';
            break;
            case 'cambiar-clave':
                $ruta_elegida = 'vistas/perfil/cambiar-clave.php';
            break;
            case 'eliminar-cuenta':
                $ruta_elegida = 'vistas/perfil/eliminar-cuenta.php';
            break;
            case 'relleno':
                $ruta_elegida = 'scripts/script-relleno.php';
            break;
        }
    } else if (count($partes_ruta) == 3) {
        if ($partes_ruta[1] == 'nueva-clave') {
            $url_personal = $partes_ruta[2];
            Conexion :: abrirConexion();
            
            if (RepositorioRecuperacionClave :: getCIurl(Conexion :: getConexion(), $url_personal) > 0) {
                $ruta_elegida = 'vistas/sesion/nueva-clave.php';
            }

            Conexion :: cerrarConexion();
        }
    }
}
include_once $ruta_elegida;