<?php
if (!ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(SERVIDOR);

include_once 'plantillas/usuarioExiste.inc.php';

$email = $usuario -> getEmail();
$nomApe = $usuario -> getNombre() . " " . $usuario -> getApellido();
$edad = $usuario -> edadUsuario($usuario -> getFechaNac());

if (isset($_POST['enviar'])) { 
    $header = 'From: ' . $email . " \r\n";
    $header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
    $header .= "Mime-Version: 1.0 \r\n";
    $header .= "Content-Type: text/plain";

    $mensaje = "Mensaje enviado por " . $nomApe . ",\r\n";
    $mensaje .= "Correo electrónico: " . $email . " \r\n";
    $mensaje .= "\r\n";
    $mensaje .= $_POST['mensaje'] . " \r\n";
    $mensaje .= "\r\n";
    $mensaje .= "Enviado el: " . date('d/m/Y', time());

    $para = 'leangj7@gmail.com';
    $asunto = '(' . $edad . " años) - " . $_POST['asunto'];

    mail($para, $asunto, utf8_decode($mensaje), $header);
    Redireccion :: redirigir(RUTA_CONTACTO);
}

include_once 'plantillas/header.inc.php';
include_once 'plantillas/menu-principal.inc.php';
?>
	<script>
	    window.onload = function() {
	        document.getElementById("contacto").style.boxShadow="inset 0px -2px 0px 0px #222222";
	    }
	</script>
        <main>
            <div class="principal">
                <div class="presentacion general" id="pagContacto">
                    <div class="banner">
                        <h1>Contacto</h1>
                    </div>
                </div>
                <div class="contenido">
                    <div id="seccionContacto">
                        <div id="info">
                            <p>Si tienes alguna consulta acerca de nuestro servicio, puedes contactarnos por este medio.</p><br>
                            <p>Estamos ubicados en: </p>
                            <p>Barrio: <br>Dirección: <br>Montevideo, Uruguay</p><br>
                            <p>Puedes escribirnos por medio de Whatsapp: <br>090909090</p>
                        </div>
                        <div id="formContacto">
                            <form action="<?php echo RUTA_CONTACTO ?>" method="POST">
                                <label>Asunto: </label>
                                <input type="text" name="asunto" autofocus required>
                                <label>Mensaje: </label>
                                <textarea name="mensaje" rows="7" required></textarea>
                                <span id="btn">
                                    <input type="submit" class="boton" name="enviar" value="Enviar">
                                </span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php
        include_once 'plantillas/footer.inc.php';
        ?>
    </body>
</html>