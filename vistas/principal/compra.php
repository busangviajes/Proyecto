<?php
if (!ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(SERVIDOR);

include_once 'db/repositorios/RepositorioEmpresa.inc.php';
include_once 'plantillas/usuarioExiste.inc.php';
include_once 'plantillas/header.inc.php';
include_once 'plantillas/menu-principal.inc.php';

if (isset($_POST['comprar'])) {
    preg_match_all('/\[(.*?)\]/', $_POST['comprar'], $salida);
    
    $idViaje = $salida[1][0];
    $origen = $salida[1][1];
    $fechaOrigenSalida = $salida[1][2];
    $horaOrigenSalida = $salida[1][3];
    $destino = $salida[1][4];
    $fechaDestinoLlegada = $salida[1][5];
    $horaDestinoLlegada = $salida[1][6];
    $categoria = $salida[1][7];
    $asientos = $salida[1][8];
    $precio = $salida[1][9];
    $wifi = $salida[1][10];
}

Conexion :: abrirConexion();

$usuarioComprador = RepositorioUsuario :: getDatosUsuario(Conexion :: getConexion(), $ci);
$nombreComprador = $usuarioComprador -> getNombre();
$apellidoComprador = $usuarioComprador -> getApellido();

$pasajes = RepositorioEligeAsiento :: getAsientosViaje(Conexion :: getConexion(), $idViaje);
Conexion :: cerrarConexion();

$asientosOcupados = "";

if (count($pasajes) > 0) {
    for ($p = 0; $p < count($pasajes); $p++) {
        if ($p == 0) {
            $asientosOcupados .= $pasajes[$p][0];
        } else {
            $asientosOcupados .= "," . $pasajes[$p][0];
        }
    }

    preg_match_all('/\,?(\d{2})\,?/', $asientosOcupados, $asientoOcupado);
}
?>
        <main id="compra">
            <div id="contenedorPrincipal">
                <div id="seleccionAsientos">
                    <div id="seleccionCantidadPasajes">
                        <p>¿Cuántos pasajes quiere comprar?</p>
                        <select name="cantidad">
                            <option value="">Sólo para mi</option>
                            <option value="">Añadir personas</option>
                        </select>
                    </div>
                    <!-- 
                        Indicar numero de asientos
                        Indicar par (2 asientos de cada lado) o impar (2 asientos del lado der y 1 del izq)
                        Recorrer for colocando asientos
                     -->
                     <div id="marcoBus">
                        <div class="contenedorAsientos" id="asientosIzq"></div>
                        <div class="contenedorAsientos" id="asientosDer"></div>
                        <script>
                            var contenedorAsientosIzq = document.getElementById("asientosIzq");
                            var contenedorAsientosDer = document.getElementById("asientosDer");
                            let cantidadAsientos = "<?=$asientos?>";

                            let par = 0;
                            for (let numAsiento = 1; numAsiento <= cantidadAsientos; numAsiento++) {
                                var asiento = document.createElement("button");
                                asiento.className = 'asiento disponible';
                                <?php
                                if (!empty($asientoOcupado[1])) {
                                    foreach($asientoOcupado[1] as $a) {
                                        ?>
                                        var asientoOcupado = "<?=$a?>";
                                        if (numAsiento == asientoOcupado) {
                                            asiento.className = 'asiento ocupado';
                                        }
                                        <?php
                                    }
                                }
                                ?>
                                if (numAsiento < 10) {
                                    let primerosNumAsiento = "0" + numAsiento;
                                    asiento.textContent = primerosNumAsiento;
                                    asiento.value = primerosNumAsiento;
                                } else {
                                    asiento.textContent = numAsiento;
                                    asiento.value = numAsiento;
                                }
                                
                                if (par < 2) {
                                    contenedorAsientosIzq.appendChild(asiento);
                                }
                                else if (par >= 2 && par < 4) {
                                    contenedorAsientosDer.appendChild(asiento);
                                }
                                par++;
                                if (par == 4) {
                                    par = 0;
                                }
                            }
                        </script>
                     </div>
                </div>
                <div id="informacionPago">
                    <!-- Al hacer clic en un asiento en multiples:
                    - Ir añadiendo personas en la lista según se haga clic en un asiento
                    - Ir mostrando de a 3 y si hay más personas, poder correrse a un lado
                    - Mostrar TOTAL siempre abajo de todo 
                    - Dar opcion de comprar pasaje de vuelta y sumar al monto total (volver a la busqueda y mandar los datos al cuadro de busqueda para encontrar viajes de vuelta)-->
                    <h4><u>Información de viaje</u></h4>
                    <?php
                    if ($wifi) {
                        ?>
                        <br>
                        <p>Coche con <b>WiFi</b></p>
                        <?php
                    }
                    ?>
                    <br>
                    <p>Salida de: <b><i><?php echo $origen?></i></b> el día <b><i><?php echo $fechaOrigenSalida?></i></b> a las <b><i><?php echo $horaOrigenSalida?></i></b></p>
                    <p>Llegada a: <b><i><?php echo $destino?></i></b> el día <b><i><?php echo $fechaDestinoLlegada?></i></b> a las <b><i><?php echo $horaDestinoLlegada?></i></b></p>
                    <div id="contenidoPasajes"></div>
                    <hr class="lineaPunteada">  <!-- En caso de acompañantes -->
                    <!-- <p>Pasaje nº2</p>
                    <p><label>Mayor de edad: </label><input type="checkbox" name="" id=""></p>
                    <p><label>Cédula: </label><input type="text"></p>
                    <p>Asiento: </p>
                    <hr class="lineaPunteada">-->
                    <div id="montoTotal"></div>
                </div>
                <script>
                    $(document).ready(function(){
                        $("#contenidoPasajes").html("<hr class='lineaPunteada'><i>Esperando selección de lugares...</i>");
                    })

                    let numerosSeleccionados = new Array();
                    let seleccion = 0;
                    let cantidadClics = 0;

                    let precioViaje = "<?=$precio?>";
                    let cantidadMultiplo = 0;
                    let precioTotal = 0;

                    var asientos = document.querySelectorAll("button.asiento");
                    
                    $("select[name='cantidad']").change(function() {
                        if($(this).children("option:selected").index() > 0) {
                            seleccion = 1;
                            asientos.forEach(elem => {
                                if (!elem.classList.contains("seleccionado")) {
                                    elem.disabled = false;
                                    elem.classList.remove("no-seleccion");
                                }
                            })
                        } else {
                            seleccion = 0;
                            asientos.forEach(elem => {
                                if (elem.classList.contains("disponible")) {
                                    elem.classList.remove("seleccionado");
                                }
                            })
                            cantidadMultiplo = 0;
                            numerosSeleccionados.splice(0, numerosSeleccionados.length);
                        }
                        precioTotal = precioViaje * cantidadMultiplo;

                        if (numerosSeleccionados.length > 0) {
                            $('#montoTotal').html("TOTAL: UYU $" + precioTotal);
                        } else {
                            $("#contenidoPasajes").html("<hr class='lineaPunteada'><i>Esperando selección de lugares...</i>");
                            $("#montoTotal").empty();
                        }
                    })

                    $(".asiento").click(function(){
                        
                        if (!$(this).hasClass("ocupado")) {
                            if (!$(this).hasClass("no-seleccion")) {
                                if ($(this).hasClass("seleccionado")) {
                                    $(this).removeClass("seleccionado");
                                } else {
                                    $(this).addClass("seleccionado");
                                }
                            }
                            
                            if (seleccion == 0) {
                                if (cantidadClics == 0) { // Seleccionar asiento único
                                    asientos.forEach(elem => {
                                        if (!elem.classList.contains("seleccionado")) {
                                            elem.disabled = true;
                                            elem.classList.add("no-seleccion");
                                        }

                                        cantidadClics++;
                                    })
                                } else {
                                    asientos.forEach(elem => { // Deseleccionar asiento único
                                        if (!elem.classList.contains("seleccionado")) {
                                            elem.disabled = false;
                                            elem.classList.remove("no-seleccion");
                                        }
                                    })
                                    cantidadClics = 0;
                                }
                            } else {
                                cantidadClics = 0;
                            }

                            if ($(this).hasClass("seleccionado")) {
                                cantidadMultiplo++;
                                numerosSeleccionados.push($(this).val());
                            } else {
                                cantidadMultiplo--;
                                numerosSeleccionados.splice(numerosSeleccionados.indexOf($(this).val()), 1);
                            }

                            precioTotal = precioViaje * cantidadMultiplo;

                            // En asiento poner un select por si la persona se equivoca y deselecciona el primer asiento
                            // De esta forma puede volver a poner el asiento o cambiarlo por otro
                            // En las opciones del select solo deben estar las opciones seleccionadas
                            // Una vez que seleccione el asiento para el titular, este debe pasar al principio del array
                            // Los numeros deben colocarse segun el orden que se hayan seleccionado para cada persona
                            if (numerosSeleccionados.length > 0) {
                                var opcionesAsientos = "<>"
                                $("#contenidoPasajes").html("<hr class='lineaPunteada'><p>Pasajero: <?php echo $nombreComprador ?><?php if ($apellidoComprador != "") echo " " . $apellidoComprador ?></p><p>Cédula: <?php echo $ci ?></p><p>Asiento: " + numerosSeleccionados[0] + "</p>");
                                numerosSeleccionados.forEach(function(numero, n) {
                                    if (n > 0 && numerosSeleccionados.length > 1) {
                                        $("#contenidoPasajes").append("<hr class='lineaPunteada'><label>Pasajero: </label><input type='text' placeholder='* Nombre y apellido'><label>Cédula: </label><input type='text' placeholder='* 12345678'><p>Asiento: " + numerosSeleccionados[n] + "</p>");
                                    } 
                                })
                                $('#montoTotal').html("TOTAL: UYU $" + precioTotal);
                            } else {
                                $("#contenidoPasajes").html("<hr class='lineaPunteada'><i>Esperando selección de lugares...</i>");
                                $("#montoTotal").empty();
                            }
                        }
                    })
                </script>
            </div>
        </main>
        <?php
        include_once 'plantillas/footer.inc.php';
        ?>
    </body>
</html>