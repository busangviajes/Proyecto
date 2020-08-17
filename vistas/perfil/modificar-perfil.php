<?php
include_once 'db/validaciones/validacionImg.inc.php';
include_once 'db/validaciones/validacionMod.inc.php';
include_once 'plantillas/usuarioExiste.inc.php';

if (!ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(SERVIDOR);

if (isset($_POST['guardar'])) {
    Conexion :: abrirConexion();
    $validadorImg = new validacionImg(($_FILES['img']['tmp_name']), $usuario);
    $fecha = $_POST['fechaNacDia'] . "/" . $_POST['fechaNacMes'] . "/" . $_POST['fechaNacAnio'];
    $validadorMod = new validacionMod($ci, $_POST['telefono'], $_POST['nombre'], $_POST['apellido'], $fecha, $_POST['email'], Conexion :: getConexion());

    if ($validadorMod -> modificacionValida() && $validadorImg -> imagenValida()){
        $usuarioModificado = RepositorioUsuario :: modificarUsuario(Conexion :: getConexion(), $validadorMod -> getTelefono(), $validadorMod -> getNombre(), $validadorMod -> getApellido(), $validadorMod -> getFechaNac(), $validadorMod -> getEmail(), $id);    

        if ($usuarioModificado)
            Redireccion :: redirigir(RUTA_PERFIL);
    }
    Conexion :: cerrarConexion();
}

include_once 'plantillas/header-cache.inc.php';
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
                        <h3>Modificar datos</h3>
                        <br>
                        <form action="<?php echo RUTA_MODIFICAR_PERFIL ?>" method="POST" enctype="multipart/form-data">
                            <?php
                            if (isset($_POST['guardar'])) {
                                include 'plantillas/validacion-modificar.inc.php';
                            } else {
                                ?>
                                <div class="bloquePrincipal">
                                    <div id="imagen">
                                        <!-- ---------------- -->
                                        <!-- Cuadro de imagen -->
                                        <!-- ---------------- -->
                                        <?php
                                        if (file_exists(DIRECTORIO_RAIZ . "/subidas/" .$usuario -> getCI())) {
                                            ?>
                                            <div>
                                                <img id="imgCuadro" src="<?php echo SERVIDOR . '/subidas/' . $usuario -> getCI(); ?>" alt="Imagen de perfil">
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div>
                                                <img id="noImgCuadro">
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <div id="btnImagen"><label id="lblImg" for="img">Cambiar foto</label></div>
                                        <input id="img" type="file" name="img" style="display: none">
                                        <!-- ----------------- -->
                                        <!-- Nombre de archivo -->
                                        <!-- ----------------- -->
                                        <label for="img" id="archivoSubido" style="font-size: 12px"></label>
                                    </div>
                                    <!-- ----------------- -->
                                    <!-- -Campos de texto- -->
                                    <!-- ----------------- -->
                                    <div class="bloqueDatos">
                                        <label><span>Nombre:</span></label>
                                        <input type="text" name="nombre" class="textoMod" value="<?php echo $usuario -> getNombre(); ?>">
                                        <label><span>Apellido:</span></label>
                                        <input type="text" name="apellido" class="textoMod" value="<?php echo $usuario -> getApellido(); ?>"></label>
                                        <label><span>Fecha de nacimiento:</span></label>
                                        <?php $fecha = explode("/", $usuario -> getFechaNac());?>
                                        <span id="fechaNac">
                                            <input type="number" class="textoMod" name="fechaNacDia" min="1" max="31" placeholder="Día" value="<?php echo $fecha[0] ?>">
                                            <span class="textoMod" id="contenedorSelect"><select class="textoMod" id="fechaNacMes" name="fechaNacMes" >
                                                <option default hidden>Mes</option>
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
                                            <script>
                                                document.getElementById("fechaNacMes").selectedIndex = "<?php echo $fecha[1] ?>";
                                            </script>
                                            <input type="number" class="textoMod" name="fechaNacAnio" min="1900" max="<?php echo date("Y") ?>" placeholder="Año" value="<?php echo $fecha[2] ?>">
                                        </span>
                                        <label><span>Teléfono:</span></label>
                                        <input type="text" name="telefono" class="textoMod" value="<?php echo $usuario -> getTelefono(); ?>"></label>
                                        <label><span>Email:</span></label>
                                        <input type="text" name="email" class="textoMod" value="<?php echo $usuario -> getEmail(); ?>"></label>
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