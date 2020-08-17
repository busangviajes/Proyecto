<?php
if (ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(RUTA_INICIO);

include_once 'plantillas/header.inc.php';
?>
    <body id="registro-correcto">
        <div class="wrapper">
            <div class="caja">
                <img class="logo" src="<?php echo RUTA_IMG ?>logo.png" alt="Logo Busang">
                <h2>¡Bienvenido!</h2><br>
                <p>Gracias por registrarte en Busang</p>
                <!-- <p>En un momento recibirás un mensaje <br>de confirmación en tu correo electrónico</p> -->
                <p>Ahora puedes iniciar sesión</p>
                <br>
                <a href="<?php echo RUTA_INICIO ?>"><input type="button" class="boton" value="Volver al inicio"></a>
            </div>
        </div>
    </body>
</html>