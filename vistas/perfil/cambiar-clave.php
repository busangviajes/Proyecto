<?php
include_once 'db/validaciones/validacionClave.inc.php';
include_once 'plantillas/usuarioExiste.inc.php'; // Para obtener la ID del usuario y usarla en actualizarClave

if (!ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(SERVIDOR);

Conexion :: abrirConexion();

if (isset($_POST['guardar'])) {
    $validador = new validacionClave($ci, $_POST['claveAntigua'], $_POST['clave'], $_POST['repClave'], Conexion :: getConexion());
    if ($validador -> claveValida()) {
        $clave_cifrada = password_hash($_POST['clave'], PASSWORD_DEFAULT);
        $clave_actualizada = RepositorioUsuario :: actualizarClave(Conexion :: getConexion(), $ci, $clave_cifrada);
        
        if ($clave_actualizada) {
            Redireccion :: redirigir(RUTA_CAMBIAR_CLAVE);
        }
    }
}

Conexion :: cerrarConexion();

include_once 'plantillas/header.inc.php';
include_once 'plantillas/menu-principal.inc.php';
include_once 'plantillas/menu-secundario.inc.php';
?>
                    <script>
                    function myFunction(x) {
                        if (x.matches) {
                            $('#cambiarClave').removeClass('selector');
                            document.getElementById("cambiarClave").style.backgroundColor = "#333333";
                        } else {
                            $('#cambiarClave').toggleClass('selector');
                            document.getElementById("cambiarClave").style.backgroundColor = "#222222"
                        }
                    }

                    var x = window.matchMedia("(max-width: 700px)")
                    myFunction(x)
                    x.addListener(myFunction)
                    </script>
                    <div class="bloqueFondo">
                        <h3>Cambiar tu contraseña</h3>
                        <br>
                        <form action="<?php echo RUTA_CAMBIAR_CLAVE ?>" method="POST">
                            <?php
                            if (isset($_POST['guardar'])) {
                               include 'plantillas/validacion-clavePerfil.inc.php';
                            } else {
                                ?>
                                <div class="bloquePrincipal">
                                    <div class="bloqueDatos">
                                        <label>Antigua contraseña:</label>
                                        <input type="password" class="textoMod" name="claveAntigua">
                                        <span class="separadorPerfil"></span>
                                        <label>Nueva contraseña:</label>
                                        <input type="password" class="textoMod" name="clave">
                                        <label>Repetir contraseña:</label>
                                        <input type="password" class="textoMod" name="repClave">
                                    </div>
                                </div>
                                <div align="right" class="cajaBoton">
                                    <input type="submit" class="btn" name="guardar" value="Guardar">
                                </div>
                                <?php
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php
        include_once 'plantillas/footer.inc.php';
        ?>
    </body>
</html>