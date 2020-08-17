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

$email = $validador -> getEmail();
    
Conexion :: abrirConexion();

$usuario = RepositorioUsuario :: getUsuarioEmail(Conexion :: getConexion(), $email);
$nomUsu = $usuario -> getNombre();
$string_aleatorio = sa(10);

$url = hash('sha256', $string_aleatorio . $nomUsu);

Redireccion :: redirigir(RUTA_REGISTRO_CORRECTO);

Conexion :: cerrarConexion();