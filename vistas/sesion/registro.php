<?php
include_once 'db/validaciones/validacionReg.inc.php';

if (ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(RUTA_INICIO);

Conexion :: abrirConexion();
if (isset($_POST['registrar'])) {
    $dia = $_POST['fechaNacDia'];
    if (preg_match('/^\d{1}$/', $_POST['fechaNacDia'])) {
        $dia = "0" . $_POST['fechaNacDia'];
    }
    $fecha = $dia . "/" . $_POST['fechaNacMes'] . "/" . $_POST['fechaNacAnio'];

    $validador = new validacionReg($_POST['cedula'], $_POST['telefono'], $_POST['nombre'], $_POST['apellido'],  $fecha, $_POST['usuario'], $_POST['clave'], $_POST['repClave'],
    $_POST['email'], Conexion :: getConexion());

    if ($validador -> registroValido()) {
        $usuario = new Usuario($validador -> getCI(), $validador -> getTelefono(), $validador -> getNombre(), $validador -> getApellido(), 
        $validador -> getFechaNac(), $validador -> getUsuario(), 
        password_hash($validador -> getClave(), PASSWORD_DEFAULT), 
        $validador -> getEmail(), true); // Cuando este pronto, el valor de activo (último) cambia a 0
        $usuarioInsertado = RepositorioUsuario :: insertarUsuario(Conexion :: getConexion(), $usuario);

        if ($usuarioInsertado) {
            Redireccion :: redirigir(RUTA_REGISTRO_CORRECTO); // Esta línea se eliminaría
        }
    }
}
Conexion :: cerrarConexion();

include_once 'plantillas/header.inc.php';
?>
    <body id="registro">
        <div class="wrapper">
            <div class="caja">
                <img class="logo" src="<?php echo RUTA_IMG?>logo.png" alt="Logo Busang">
                <form action="<?php echo RUTA_REGISTRO ?>" method="POST">
                    <?php
                    if (isset($_POST['registrar'])) {
                        include_once 'plantillas/validacion-registro.inc.php';
                    } else {
                        ?>
                        <div class="contenedor" id="textos">
                            <div class="cajasTexto">
                                <span id="nombre">
                                    <input type="text" class="texto" name="nombre" placeholder="Nombre" required autofocus>
                                </span>
                                <span id="apellido">
                                    <input type="text" class="texto" name="apellido" placeholder="Apellido">
                                </span>
                                <span id="cedula">
                                    <input type="text" class="texto" name="cedula" placeholder="Cédula" required>
                                </span>
                                <span class="textoFecha">Fecha de nacimiento</span>
                                <span id="fechaNac">
                                    <input type="number" class="texto" name="fechaNacDia" placeholder="Día" required>
                                    <span class="texto"><select id="fechaNacMes" name="fechaNacMes" required>
                                        <option hidden>Mes</option>
                                        <option value="01">Enero</option>
                                        <option value="02">Febrero</option>
                                        <option value="03">Marzo</option>
                                        <option value="04">Abril</option>
                                        <option value="05">Mayo</option>
                                        <option value="06">Junio</option>
                                        <option value="07">Julio</option>
                                        <option value="08">Agosto</option>
                                        <option value="09">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select></span>
                                    <input type="number" class="texto" name="fechaNacAnio" placeholder="Año" required>
                                </span>
                                <span id="telefono">
                                    <input type="text" class="texto" name="telefono" placeholder="Teléfono / Celular">
                                </span>
                                <span id="usuario">
                                    <input type="text" class="texto" name="usuario" placeholder="Usuario" required>
                                </span>
                                <span id="clave">
                                    <input type="password" class="texto" name="clave" placeholder="Contraseña" required>
                                </span>
                                <span id="repClave">
                                    <input type="password" class="texto" name="repClave" placeholder="Repetir contraseña" required>
                                </span>
                                <span id="email">
                                    <input type="email" class="texto" name="email" placeholder="Correo electrónico" required>
                                </span>
                            </div>
                            <div class="info">
                                <label>Al hacer clic en Registrarse, acepta los <a href="">Términos de uso y la Política de privacidad.</a></label>
                            </div>
                        </div>
                        <input type="submit" class="boton" name="registrar" value="Registrarse">
                        <?php
                    }
                    ?>
                    <div class="info">
                        <label>¿Ya tienes una cuenta? <a href="<?php  echo RUTA_INICIO_SESION ?>">Iniciar sesión</a></label>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>