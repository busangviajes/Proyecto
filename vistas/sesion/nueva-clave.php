<?php
include_once 'db/validaciones/validacionClave.inc.php';

Conexion :: abrirConexion();

if (RepositorioRecuperacionClave :: getCIurl(Conexion :: getConexion(), $url_personal) > 0) {
    $ciusuario = RepositorioRecuperacionClave :: getCIurl(Conexion :: getConexion(), $url_personal);

    if (isset($_POST['enviar'])) {
        $validador = new validacionClave($_POST['clave'], $_POST['repClave']);
        if ($validador -> claveValida()) {
            $clave_cifrada = password_hash($_POST['clave'], PASSWORD_DEFAULT);
            $clave_actualizada = RepositorioUsuario :: actualizarClave(Conexion :: getConexion(), $ciusuario, $clave_cifrada);
            
            if ($clave_actualizada) {
                $eliminarClave = RepositorioRecuperacionClave :: borrarPeticion(Conexion :: getConexion(), $ciusuario);
                Redireccion :: redirigir(RUTA_CLAVE_CORRECTA);
            }
        }
    }
}
Conexion :: cerrarConexion();

include_once 'plantillas/header.inc.php';
?>
    <body id="recuperacion">
        <div class="wrapper">
            <div class="caja">
                <img class="logo" src="<?php echo RUTA_IMG ?>logo.png" alt="Logo Busang">
                <form action="<?php echo RUTA_NUEVA_CLAVE . "/" . $url_personal; ?>" method="POST">
                    <h3>Recuperacion de contraseña</h3><br>
                    <?php
                    if (isset($_POST['enviar'])) {
                        include 'plantillas/validacion-clave.inc.php';
                    } else {
                        ?>
                        <div class="contenedor" id="claves">
                            <div class="cajasTexto">
                                <span id="clave">
                                    <input type="password" class="texto" name="clave" placeholder="Nueva contraseña" required autofocus>
                                </span>
                                <span id="repClave">
                                    <input type="password" class="texto" name="repClave" placeholder="Repetir contraseña" required autofocus>
                                </span>
                            </div>
                        </div>
                        <input type="submit" class="boton" name="enviar" value="Enviar">
                        <?php
                    }
                    ?>
                </form>
            </div>
        </div>
    </body>
</html>