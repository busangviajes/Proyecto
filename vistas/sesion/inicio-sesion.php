<?php
include_once 'db/validaciones/validacionISesion.inc.php';

if (ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(RUTA_INICIO);

Conexion :: abrirConexion();
if (isset($_POST['enviar'])) {
    $validador = new validacionISesion($_POST['usuario'], $_POST['clave'], Conexion :: getConexion());
    if ($validador -> getError() === '' && !is_null($validador -> getUsuario())) {
        if (RepositorioUsuario :: getUsuario(Conexion :: getConexion(), $_POST['usuario']) -> getActivo() > 0) {  
            $validador = new validacionISesion($_POST['usuario'], $_POST['clave'], Conexion :: getConexion());
            if ($validador -> getError() === '' && !is_null($validador -> getUsuario())) {
                ControlSesion :: iniciarSesion($validador -> getUsuario() -> getCI(), $validador -> getUsuario() -> getUsuario());
                Redireccion :: redirigir(RUTA_INICIO);
            }
        }
    }
}
Conexion :: cerrarConexion();

include_once 'plantillas/header.inc.php';
?>
    <script>
        window.onload = function() {
            $('#ocultar').css('display', 'none');
            $('.ojo').click(function () {
                if ($('#txtClave').is(":password")) {
                    $('#txtClave').attr('type', 'text');
                    $('#mostrar').css('display', 'none');
                    $('#ocultar').css('display', 'block');
                } else {
                    $('#txtClave').attr('type', 'password');
                    $('#ocultar').css('display', 'none');
                    $('#mostrar').css('display', 'block');
                }
            })
        }
    </script>
    <body id="inicioSesion">
        <div class="wrapper">
            <div class="caja">
                <img class="logo" src="<?php echo RUTA_IMG ?>logo.png" alt="Logo Busang">
                <form name="formIngreso" action="<?php echo RUTA_INICIO_SESION ?>" method="POST">
                    <div class="contenedor" id="texto-recordar">
                        <div class="cajasTexto">
                            <span id="usuario">
                                <input type="text" class="texto" name="usuario" placeholder="Usuario"
                                <?php
                                if (isset($_POST['enviar']) &&  isset($_POST['usuario']) && !empty($_POST['usuario'])) {
                                    echo 'value="' . $_POST['usuario'] . '"';
                                }
                                ?>
                                required autofocus>
                            </span>
                            <span id="clave">
                                <input type="password" class="texto" id="txtClave" name="clave" placeholder="Contraseña" required>
                                <i id="mostrar" class="ojo fas fa-eye"></i>
                                <i id="ocultar" class="ojo fas fa-eye-slash"></i>
                            </span>
                        </div>
                        <!-- <span id="guardar">
                            <input type="checkbox" id="recordar">
                            <label for="recordar">Recuérdame</label>
                        </span> -->
                    </div>
                    <div class="alerta">
                        <?php
                        if (isset($_POST['enviar'])) {
                            $validador -> mostrarError();
                        }
                        ?>
                    </div>
                    <input type="submit" class="boton" name="enviar" value="Ingresar">
                    <div class="info">
                        <label><a href="<?php echo RUTA_RECUPERAR_CLAVE ?>">¿Olvidaste tu contraseña?</a></label><br>
                        <label>¿No tienes una cuenta? <a href="<?php echo RUTA_REGISTRO ?>">Registrate</a></label>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>