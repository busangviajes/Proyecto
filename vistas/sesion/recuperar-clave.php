<?php
if (ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(RUTA_INICIO);

if (isset($_POST['enviar'])) {
    include_once 'scripts/generar-url.inc.php';
}

include_once 'plantillas/header.inc.php';
?>
    <body id="recuperacion">
            <div class="wrapper">
                <div class="caja">
                    <img class="logo" src="<?php echo RUTA_IMG ?>logo.png" alt="Logo Busang">
                    <form action="<?php echo RUTA_RECUPERAR_CLAVE ?>" method="POST">
                        <h3>Recuperacion de contraseña</h3><br>
                        <p>Ingresa tu correo electrónico para recibir las instrucciones.</p>
                        <div class="contenedor" id="correo">
                            <div class="cajasTexto">
                                <input pattern="[^@\s]+@[^@\s]+\.[^@\s]+" title="correo@dominio.com" type="email" class="texto" name="email" placeholder="Correo electrónico" required autofocus>
                            </div>
                        </div>
                        <input type="submit" class="boton" name="enviar" value="Enviar">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>