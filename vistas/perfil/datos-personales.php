<?php
if (!ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(SERVIDOR);

include_once 'plantillas/usuarioExiste.inc.php';
include_once 'plantillas/header.inc.php';
include_once 'plantillas/menu-principal.inc.php';
include_once 'plantillas/menu-secundario.inc.php';
?>
                    <script>
                    function myFunction(x) {
                        if (x.matches) {
                            $('#datosPersonales').removeClass('selector');
                            document.getElementById("datosPersonales").style.backgroundColor = "#333333";
                        } else {
                            $('#datosPersonales').toggleClass('selector');
                            document.getElementById("datosPersonales").style.backgroundColor = "#222222"
                        }
                    }

                    var x = window.matchMedia("(max-width: 700px)")
                    myFunction(x)
                    x.addListener(myFunction)
                    </script>
                    <div class="bloqueFondo">
                        <h3>Información personal</h3>
                        <br>
                        <div class="bloquePrincipal">
                            <div id="imagen">
                                <!-- ---------------- -->
                                <!-- Cuadro de imagen -->
                                <!-- ---------------- -->
                                <?php
                                if (file_exists(DIRECTORIO_RAIZ . "/subidas/" . $usuario -> getCI())) {
                                    ?>
                                        <img id="imgCuadro" src="<?php echo SERVIDOR . '/subidas/' . $usuario -> getCI(); ?>" alt="Imagen de perfil" style="margin: 0">
                                    <?php
                                } else {
                                    ?>
                                        <img id="noImgCuadro" style="margin: 0">
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="bloqueDatos">
                                <label><span>Nombre y apellido: </span><?php echo $usuario -> getNombre(); ?> <?php echo $usuario -> getApellido(); ?></label>
                                <label><span>Edad: </span><?php echo $usuario -> edadUsuario($usuario -> getFechaNac()); ?></label>
                                <label><span>Teléfono: </span><?php echo $usuario -> getTelefono(); ?></label>
                                <label><span>Email: </span><?php echo $usuario -> getEmail(); ?></label>
                            </div>
                        </div>
                        <div align="right" class="cajaBoton">
                            <a href="<?php echo RUTA_MODIFICAR_PERFIL ?>"><input type="button" class="btn" name="modificar" value="Modificar"></a>
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