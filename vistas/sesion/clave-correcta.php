<?php
if (ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(RUTA_INICIO);

include_once 'plantillas/header.inc.php';
?>
    <body id="recuperacion">
            <div class="wrapper">
                <div class="caja">
                    <img class="logo" src="<?php echo RUTA_IMG ?>logo.png" alt="Logo Busang">
                    <h3>Recuperacion de contraseña</h3><br>
                    <p>Haz actualizado tu contraseña correctamente.</p>
                    <p>Ahora puedes iniciar sesión.</p>
                    <a href="<?php echo RUTA_INICIO_SESION ?>"><input type="button" class="boton" value="Iniciar sesión"></a>
                </div>
            </div>
        </div>
    </body>
</html>