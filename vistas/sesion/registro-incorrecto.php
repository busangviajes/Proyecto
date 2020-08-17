<?php
if (ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(RUTA_INICIO);

include_once 'plantillas/header.inc.php';
?>
    <body id="registro-incorrecto">
        <div class="wrapper">
            <div class="caja">
                <img class="logo" src="<?php echo RUTA_IMG ?>logo.png" alt="Logo Busang">
                <h2>Ha ocurrido un error</h2><br>
                <p>Puedes volver e intentar registrarte nuevamente</p>
                <br>
                <a href="<?php echo RUTA_REGISTRO ?>"><input type="button" class="boton" value="Volver al registro"></a>
            </div>
        </div>
    </body>
</html>