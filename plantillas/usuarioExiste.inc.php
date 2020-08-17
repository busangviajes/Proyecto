<?php
if (ControlSesion :: sesionIniciada()) {
    Conexion :: abrirConexion();
    $ci = $_SESSION['ciusuario'];
    $usuario = RepositorioUsuario :: getDatosUsuario(Conexion :: getConexion(), $ci);
    if (is_null(RepositorioUsuario :: getDatosUsuario(Conexion :: getConexion(), $ci))) {
        if (file_exists(DIRECTORIO_RAIZ . "/subidas/" . $ci)) {
            unlink(DIRECTORIO_RAIZ . "/subidas/" . $ci);
        }
        ControlSesion :: cerrarSesion();
        Redireccion :: redirigir(SERVIDOR);
    }
}
Conexion :: cerrarConexion();