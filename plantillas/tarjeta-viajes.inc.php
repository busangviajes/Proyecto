<div id="resultados1">
    <div id="btnFlechaDer" class="flecha">
        <i id="derecha"></i>
    </div>
    <?php
    if (count($resultados1)) {
        ?>
        <p class="tituloTarjetas">Ida</p>
        <div id="tarjetaUno">
            <?php
            for ($r = 0; $r < count($resultados1); $r++) {
                Conexion :: abrirConexion();

                $asientosTotalesResultados1 = $resultados1[$r][0] -> getAsientos();
                // Conseguir numeros de los asientos ocupados en un viaje especifico
                $pasajesResultados1 = RepositorioEligeAsiento :: getAsientosViaje(Conexion :: getConexion(), $resultados1[$r][0] -> getID());

                $asientosOcupados = "";
                if (count($pasajesResultados1) > 0) {
                    for ($p = 0; $p < count($pasajesResultados1); $p++) {
                        if ($p == 0) {
                            $asientosOcupados .= $pasajesResultados1[$p][0];
                        } else {
                            $asientosOcupados .= "," . $pasajesResultados1[$p][0];
                        }
                    }

                    preg_match_all('/\,?(\d{2})\,?/', $asientosOcupados, $asientoOcupado);

                    foreach ($asientoOcupado[1] as $a) {
                        $asientosTotalesResultados1--;
                    }
                }

                $nom = RepositorioViaje :: getNom(Conexion :: getConexion(), $resultados1[$r][0] -> getIDemp());
                Conexion :: cerrarConexion();
                ?>
                <form action="<?php echo RUTA_COMPRA ?>" method="POST">
                    <div class="tablaTarjetas">
                        <div>
                            <div class="tarjeta">
                                <div class="info1">
                                    <div class="sale">
                                        <p class="peq">Sale:</p>
                                        <p class="gran"><span class="b"><?php echo $resultados1[$r][0] -> getOrigen()?></span></p>
                                        <p class="fecha-hora">
                                        <?php echo date("d/m", strtotime($resultados1[$r][0] -> getFechaHoraSalida())) . ' ' . date("H:m", strtotime($resultados1[$r][0] -> getFechaHoraSalida()))?> hs</p>
                                    </div>
                                    <div class="llega">
                                        <p class="peq">Llega</p>
                                        <p class="gran"><span class="b"><?php echo $resultados1[$r][0] -> getDestino()?></span></p>
                                        <p class="fecha-hora">
                                        <?php echo date("d/m", strtotime($resultados1[$r][0] -> getFechaHoraLlegada())) . ' ' . date("H:m", strtotime($resultados1[$r][0] -> getFechaHoraLlegada()))?> hs</p></p>
                                    </div>
                                </div>
                                <div class="info2">
                                    <p class="peq cat">
                                        <span>
                                            <?php
                                            $wifi1 = $resultados1[$r][0] -> getWifi();
                                            if ($wifi1 > 0) {
                                                ?>
                                                <img class="logo-wifi" src="<?php echo RUTA_IMG . "wifi.png" ?>" alt="Tiene WIFI">
                                                <?php
                                            }
                                            ?>
                                            <span class="b">
                                                <?php
                                                echo $resultados1[$r][0] -> getCategoria()
                                                ?>
                                            </span>
                                        </span>
                                    </p>
                                    <img class="logo-empresa" src="<?php echo RUTA_IMG . "agencias/logos/" . str_replace(' ', '-', $nom) . ".png" ?>" alt="LOGO">
                                    <div class="asientos">
                                        <label class="texto-asientos">Asientos disponibles:</label>
                                        <label class="numA"><span class="b"><?php echo $asientosTotalesResultados1 ?></span></label>
                                    </div>
                                    <?php
                                    $datosViajeIda = "[" . $resultados1[$r][0] -> getID() . "][" . $resultados1[$r][0] -> getOrigen() . "][" . date("d/m", strtotime($resultados1[$r][0] -> getFechaHoraSalida())) . "][" . 
                                    date("H:m", strtotime($resultados1[$r][0] -> getFechaHoraSalida())) . "][" . 
                                    $resultados1[$r][0] -> getDestino() . "][" . date("d/m", strtotime($resultados1[$r][0] -> getFechaHoraLlegada())) . "][" . 
                                    date("H:m", strtotime($resultados1[$r][0] -> getFechaHoraLlegada())) . "][" . 
                                    $resultados1[$r][0] -> getCategoria() . "][" . $resultados1[$r][0] -> getAsientos() . "][" . $resultados1[$r][0] -> getTarifa() . "][" . 
                                    $wifi1 ."]";
                                    ?>
                                    <a href="<?php echo RUTA_COMPRA ?>"><button type="submit" class="gran btnCompra" name="comprar" value="<?php echo $datosViajeIda?>">UYU $<?php echo $resultados1[$r][0] -> getTarifa()?></button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php
            }
            ?>
        </div>
        <?php
    } else {
        ?>
        <h3>No se han encontrado viajes</h3>
        <?php
    }
    ?>
</div>
<?php
if (count($resultados1)) {
    ?>
    <div id="resultados2">
        <div id="btnFlechaIzq" class="flecha">
            <i id="izquierda"></i>
        </div>
        <?php
        // Si se hizo una busqueda indicando viaje de vuelta
        if (!is_null($resultados2)) {
            ?>
            <style>
                #principal .contenido #tablaViajes #lista #resultados1 {
                    border-right: 1px solid #243150;
                }
                
                #principal .contenido #tablaViajes #lista #resultados2 {
                    display: initial;
                }

                .tarjeta {
                    width: 225px;
                    grid-template-columns: auto;
                }

                #principal .resultados #bloqueBusquedaAvanzada > div:not(#opciones-orden) {
                    display: grid;
                    grid-template-columns: repeat(2, auto);
                }
                
                @media (max-width: 1020px) {
                    #principal .contenido .busquedaAvanzada .opciones {
                        grid-template-columns: auto;
                    }
                }

                @media (max-width: 1080px) {
                    #tarjetaUno, #tarjetaDos {
                        grid-template-columns: auto;
                    }
                }

                @media (max-width: 580px) {
                    #principal .contenido #tablaViajes {
                        overflow-x: hidden;
                    }

                    .flecha {
                        visibility: visible;
                    }

                    #principal .contenido #tablaViajes #lista #resultados2 {
                        display: none;
                    }
                }
            </style>
            <?php
            // Si la busqueda no viene vacia
            if (count($resultados2)) {
                ?>
                <p class="tituloTarjetas">Vuelta</p>
                <div id="tarjetaDos">
                    <?php
                    for ($r = 0; $r < count($resultados2); $r++) {
                        Conexion :: abrirConexion();
                        $asientosTotalesResultados2 = $resultados2[$r][0] -> getAsientos();
                        $pasajesResultados2 = RepositorioEligeAsiento :: getAsientosViaje(Conexion :: getConexion(), $resultados2[$r][0] -> getID());

                        $asientosOcupados = "";
                        if (count($pasajesResultados2) > 0) {
                            for ($p = 0; $p < count($pasajesResultados2); $p++) {
                                if ($p == 0) {
                                    $asientosOcupados .= $pasajesResultados2[$p][0];
                                } else {
                                    $asientosOcupados .= "," . $pasajesResultados2[$p][0];
                                }
                            }

                            preg_match_all('/\,?(\d{2})\,?/', $asientosOcupados, $asientoOcupado);

                            foreach ($asientoOcupado[1] as $a) {
                                $asientosTotalesResultados1--;
                            }
                        }

                        $nom = RepositorioViaje :: getNom(Conexion :: getConexion(), $resultados2[$r][0] -> getIDemp());
                        Conexion :: cerrarConexion();
                        ?>
                        <form action="<?php echo RUTA_COMPRA ?>" method="POST">
                            <div class="tablaTarjetas">
                                <div>
                                    <div class="tarjeta">
                                        <div class="info1">
                                            <div class="sale">
                                                <p class="peq">Sale:</p>
                                                <p class="gran"><span class="b"><?php echo $resultados2[$r][0] -> getOrigen()?></span></p>
                                                <p class="fecha-hora">
                                                <?php echo date("d/m", strtotime($resultados2[$r][0] -> getFechaHoraSalida())) . ' ' . date("H:m", strtotime($resultados2[$r][0] -> getFechaHoraSalida()))?> hs</p>
                                            </div>
                                            <div class="llega">
                                                <p class="peq">Llega</p>
                                                <p class="gran"><span class="b"><?php echo $resultados2[$r][0] -> getDestino()?></span></p>
                                                <p class="fecha-hora">
                                                <?php echo date("d/m", strtotime($resultados2[$r][0] -> getFechaHoraLlegada())) . ' ' . date("H:m", strtotime($resultados2[$r][0] -> getFechaHoraLlegada()))?> hs</p></p>
                                            </div>
                                        </div>
                                        <div class="info2">
                                            <p class="peq cat">
                                                <span>
                                                    <?php
                                                    $wifi2 = $resultados2[$r][0] -> getWifi();
                                                    if ($wifi2 > 0) {
                                                        ?>
                                                        <img class="logo-wifi" src="<?php echo RUTA_IMG . "wifi.png" ?>" alt="Tiene WIFI">
                                                        <?php
                                                    }
                                                    ?>
                                                    <span class="b">
                                                        <?php
                                                        echo $resultados2[$r][0] -> getCategoria()
                                                        ?>
                                                    </span>
                                                </span>
                                            </p>
                                            <img class="logo-empresa" src="<?php echo RUTA_IMG . "agencias/logos/" . str_replace(' ', '-', $nom) . ".png" ?>" alt="LOGO">
                                            <div class="asientos">
                                                <label class="texto-asientos">Asientos disponibles:</label>
                                                <label class="numA"><span class="b"><?php echo $asientosTotalesResultados2?></span></label>
                                            </div>
                                            <?php
                                            $datosViajeVuelta = "[" . $resultados2[$r][0] -> getID() . "][" . $resultados2[$r][0] -> getOrigen() . "][" . date("d/m", strtotime($resultados2[$r][0] -> getFechaHoraSalida())) . "][" . 
                                            date("H:m", strtotime($resultados2[$r][0] -> getFechaHoraSalida())) . "][" . 
                                            $resultados2[$r][0] -> getDestino() . "][" . date("d/m", strtotime($resultados2[$r][0] -> getFechaHoraLlegada())) . "][" . 
                                            date("H:m", strtotime($resultados2[$r][0] -> getFechaHoraLlegada())) . "][" . 
                                            $resultados2[$r][0] -> getCategoria() . "][" . $resultados2[$r][0] -> getAsientos() . "][" . $resultados2[$r][0] -> getTarifa() . "][" . 
                                            $wifi2 ."]";
                                            ?>
                                            <a href="<?php echo RUTA_COMPRA ?>"><button type="submit" class="gran btnCompra" name="comprar" value="<?php echo $datosViajeVuelta?>">UYU $<?php echo $resultados2[$r][0] -> getTarifa()?></button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } else {
                ?>
                <p class="tituloTarjetas">Vuelta</p>
                <h3 style="margin-left: 10px">No se han encontrado viajes de regreso</h3>
                <?php
            }
        }
        ?>
    </div>
    <?php
}