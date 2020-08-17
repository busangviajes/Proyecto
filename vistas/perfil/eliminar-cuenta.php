<?php
include_once 'db/validaciones/validacionBaja.inc.php';
include_once 'plantillas/usuarioExiste.inc.php';

if (!ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(SERVIDOR);

Conexion :: abrirConexion();

if (isset($_POST['confirmar'])) {
    $validador = new validacionBaja($id, $_POST['clave'], $_POST['repClave'], Conexion :: getConexion());
    if ($validador -> claveValida()) {
        $usuarioEliminado = RepositorioUsuario :: eliminarUsuario(Conexion :: getConexion(), $ci);
        ControlSesion :: cerrarSesion();
        Redireccion :: redirigir(SERVIDOR);
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
                            $('#eliminarCuenta').removeClass('selector');
                            document.getElementById("eliminarCuenta").style.backgroundColor = "#333333";
                        } else {
                            $('#eliminarCuenta').toggleClass('selector');
                            document.getElementById("eliminarCuenta").style.backgroundColor = "#222222"
                        }
                    }

                    var x = window.matchMedia("(max-width: 700px)")
                    myFunction(x)
                    x.addListener(myFunction)
                    </script>
                    <div class="bloqueFondo">
                        <h3>Darse de baja</h3>
                        <br>
                        <form action="<?php echo RUTA_ELIMINAR_CUENTA ?>" method="POST">
                            <?php
                            if (isset($_POST['confirmar'])) {
                               include 'plantillas/validacion-baja.inc.php';
                            } else {
                                ?>
                                <div class="bloquePrincipal">
                                    <div class="bloqueDatos">
                                        <label>Contraseña:</label>
                                        <input type="password" class="textoMod" name="clave">
                                        <label>Repetir contraseña:</label>
                                        <input type="password" class="textoMod" name="repClave">
                                    </div>
                                </div>
                                <div align="right" class="cajaBoton">
                                    <input type="submit" class="btn" name="confirmar" value="Confirmar">
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