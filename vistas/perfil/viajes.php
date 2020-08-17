<?php
if (!ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(SERVIDOR);

include_once 'plantillas/usuarioExiste.inc.php';
include_once 'plantillas/header.inc.php';
include_once 'plantillas/menu-principal.inc.php';
?>
        <main>
            <div class="principal">
                <div class="presentacion general" id="pagViajes">
                    <div class="banner">
                        <h1>Viajes</h1>
                    </div>
                </div>
            </div>
            <div class="contenido">
                <?php
                Conexion :: abrirConexion();
                $viajes = RepositorioElige :: getViajes(Conexion :: getConexion(), $ci);
                if (count($viajes) > 0) {
                    ?>
                    <h1>Viajes realizados</h1>
                    <div id="viajes">
                        <div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th>Fecha y hora</th>
                                        <th>Origen</th>
                                        <th>Destino</th>
                                        <th></th> <!-- I M A G E N     R E P R E S E N T A T I V A -->
                                        <th>Tarifa</th>
                                        <th>Recibo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                for ($i = 0; $i < count($viajes); $i++) {
                                    $viaje = $viajes[$i][0];
                                    ?>
                                    <tr>
                                        <td><?php echo $viajes[$i][3]?></td>
                                        <td><?php echo $viajes[$i][2]?></td>
                                        <td><?php echo $viaje -> getOrigen()?></td>
                                        <td><?php echo $viaje -> getDestino()?></td>
                                        <td style="background-image: url('<?php echo RUTA_IMG . "agencias/tablas/". str_replace(' ', '', $viajes[$i][3]) . ".jpg" ?>'); background-size: cover; height: 60px;"></td>
                                        <td>$<?php echo $viaje -> getTarifa()?></td>
                                        <td><a href=""><img class="descargar" src="<?php echo RUTA_IMG . "descargar.png" ?>" alt="Descargar"></a></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php
                } else {
                    ?>
                    <h1>Aún no has hecho ningún viaje</h1>
                    <?php
                }
                Conexion :: cerrarConexion();
                ?>
            </div>
        </main>
        <?php
        include_once 'plantillas/footer.inc.php';
        ?>
    </body>
</html>