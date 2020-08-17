<?php

function sa($longitud) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numero_caracteres = strlen($caracteres);
    $string_aleatorio = '';
    
    for ($i = 0; $i < $longitud; $i++) {
        $string_aleatorio .= $caracteres[rand(0, $numero_caracteres - 1)];
    }
    
    return $string_aleatorio;
}

if (isset($_POST['enviar'])) {
    $email = $_POST['email'];
	
    Conexion :: abrirConexion();

    if (!is_null(RepositorioUsuario :: getUsuarioEmail(Conexion :: getConexion(), $email))) {
        $usuario = RepositorioUsuario :: getUsuarioEmail(Conexion :: getConexion(), $email);
        if (!RepositorioRecuperacionClave :: usuarioExiste(Conexion :: getConexion(), $usuario -> getCI())) {
            $nomUsu = $usuario -> getNombre();
            $string_aleatorio = sa(10);
        
            $url = hash('sha256', $string_aleatorio . $nomUsu);
        
            $peticion_generada = RepositorioRecuperacionClave :: generarPeticion(Conexion :: getConexion(), $usuario -> getCI(), $url);

            if ($peticion_generada) {
                $mensaje = "
                <html>
                    <body>
                        <p>Haz clic en el botón para poder cambiar tu contraseña</p>
                        <a href='" . RUTA_NUEVA_CLAVE . "/" . $url . "'><button type='button'>Cambiar clave</button></a>
                        <br>
                        <p>Si lo prefieres, puedes ingresar al siguiente enlace:</p>
                        <p>" . RUTA_NUEVA_CLAVE . "/" . $url . "</p>
                        <br>
                        <br>
                        <p>Este enlace será valido por una semana después del reclamo.</p>
                    </body>
                </html>";

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                mail($email, "Recuperación de contraseña", $mensaje, $headers);
            }
        }
    }
    Redireccion :: redirigir(SERVIDOR);
}
Conexion :: cerrarConexion();