<?php
if (!ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(SERVIDOR);

include_once 'db/repositorios/RepositorioEmpresa.inc.php';
include_once 'plantillas/usuarioExiste.inc.php';
include_once 'plantillas/header.inc.php';
include_once 'plantillas/menu-principal.inc.php';
?>
        <script>
            window.onload = function() {
                document.getElementById("inicio").style.boxShadow="inset 0px -2px 0px 0px #222222";
                $(".chb").change(function() {
                    $(".chb").not(this).prop('checked', false);
                });
            }

            $(window).resize(function() {
                var field = $(document.activeElement);
                if (field.is('.hasDatepicker')) {
                    field.blur();
                    field.datepicker('hide');
                }
            });

            $('#t3').focus(function() {
                $(this).datepicker('show');
            })
        </script>
        <script>
            $(function() {
                var origenes = new Array();
                var destinos = new Array();
                <?php
                Conexion :: abrirConexion();
                $viajesO = RepositorioViaje :: autocompletar(Conexion :: getConexion(), 'origen');
                $viajesD = RepositorioViaje :: autocompletar(Conexion :: getConexion(), 'destino');
                Conexion :: cerrarConexion();
                if (count($viajesO) > 0) {
                    for ($l = 0; $l < count($viajesO); $l++) {
                        ?>
                        origenes.push('<?php echo $viajesO[$l]?>');
                        <?php
                    }
                }

                if (count($viajesD) > 0) {
                    for ($l = 0; $l < count($viajesD); $l++) {
                        ?>
                        destinos.push('<?php echo $viajesD[$l]?>');
                        <?php
                    }
                }
                ?>
                $('#t1').autocomplete({
                    source: origenes
                })

                $('#t2').autocomplete({
                    source: destinos
                })
            });
        </script>
        <main>
            <div class="principal">
                <div class="presentacion general" id="pagInicio">
                    <div class="banner">
                        <h6>¿Hacia dónde irás hoy?</h6>
                        <form action="<?php echo RUTA_RESULTADOS ?>" method="POST">
                            <span id="busqueda">
                                <span id="txt1">
                                    <input type="text" class="txt" id="t1" name="t1" placeholder="Origen" onkeypress="return val(event);" onpaste="return false" autocomplete="off">
                                </span>
                                <span id="txt2">
                                    <input type="text" class="txt" id="t2" name="t2" placeholder="Destino" onkeypress="return val(event)" onpaste="return false" autocomplete="off">
                                </span>
                                <span id="txt3">
                                    <input type="text" class="txt" id="t3" name="t3" placeholder="Ida y vuelta" autocomplete="off" readonly>
                                    <input type="button" id="cruz" value="&#215;">
                                    <span class="tooltip">Si no deseas colocar fecha de Vuelta, haz clic fuera del calendario</span>
                                </span>
                                <span><button type="submit" id="btnBuscar" name="buscar"><i class="fas fa-search"></i></button></span>
                            </span>
                        </form>
                    </div>
                </div>
                <script>
                    $.datepicker.regional['es'] = {
                    closeText: 'Cerrar',
                    prevText: '< Ant',
                    nextText: 'Sig >',
                    currentText: 'Hoy',
                    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
                    dayNamesMin: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sáb'],
                    weekHeader: 'Sm',
                    dateFormat: 'dd/mm/yy',
                    firstDay: 1,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: '',
                    };
                    $.datepicker.setDefaults($.datepicker.regional['es']);
                    $n = 0;
                    $fecha1 = '';
                    var f1 = new Date();
                    var f2 = new Date();
                    $fecha2 = '';
                    $('#t3').datepicker({
                        onSelect: function(){
                            $(this).change();
                            if ($n == 0) {
                                $fecha1 = $(this).val();
                                var partes1 = $fecha1.split("/");
                                f1 = new Date(partes1[2], partes1[1] - 1, partes1[0]);
                                $fecha2 = '';
                                $n++;
                                $(this).data('datepicker').inline = true;
                            } else {
                                $fecha2 = $(this).val();
                                var partes2 = $fecha2.split("/");
                                f2 = new Date(partes2[2], partes2[1] - 1, partes2[0]);
                                if (f1 <= f2) {
                                    $(this).val($fecha1 + " - " + $fecha2);
                                    $(this).data('datepicker').inline = false;
                                }
                                $n = 0;
                            }
                        },
                        onClose: function() {
                            $(this).data('datepicker').inline = false;
                            $n = 0;
                        },
                        beforeShow : function(input,inst){
                            var offset = $(input).offset();
                            var height = $(input).height();
                            window.setTimeout(function () {
                                $(inst.dpDiv).css({ top: (offset.top + height) + 'px', left:offset.left + 'px' })
                            }, 1);
                        }
                    })

                    $('#t3').change(function() {
                        $('#cruz').css('display', 'block');
                    })

                    $('#cruz').click(function () {
                        $('#t3').val('');
                        $('#cruz').css('display', 'none');
                    });

                    // Validaciones

                    function val(e){
                        key = e.keyCode || e.which;
                        tecla = String.fromCharCode(key).toLowerCase();
                        letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
                        especiales = "8-37-39-46";

                        tecla_especial = false
                        for(var i in especiales){
                            if(key == especiales[i]){
                                tecla_especial = true;
                                break;
                            }
                        }

                        if(letras.indexOf(tecla)==-1 && !tecla_especial){
                            return false;
                        }
                    }
                </script>
                <div class="contenido">
                    <?php
                    Conexion :: abrirConexion();
                    $empresas = RepositorioEmpresa :: getEmpresas(Conexion :: getConexion());
                    if (count($empresas) > 0) { // Verifico si hay empresas
                        ?>
                        <h1>Compañias asociadas</h1>
                        <div id="agencias">
                            <?php
                            for ($i = 0; $i < count($empresas); $i++) {
                                ?>
                                <input type="checkbox" class="chb" name="chb<?php echo $i?>" id="chk<?php echo $i?>"><label class="btnAgencia" for="chk<?php echo $i?>" id="lbl<?php echo $i?>"><?php echo $empresas[$i] -> getNombre()?></label>
                                <?php
                                $telEmpresas = RepositorioEmpresaTel :: getTelefonos(Conexion :: getConexion(), $empresas[$i] -> getID());
                                $viajesEmpresas = RepositorioViaje :: getViajes(Conexion :: getConexion(), $empresas[$i] -> getID());
                                if (count($telEmpresas) > 0) { // Si hay empresas, verifico si tiene telefonos
                                    ?>
                                    <div id="a<?php echo $i?>">
                                        <label id="lbl">
                                            <?php
                                            echo $empresas[$i] -> getEmail() . " - ";
                                            for ($c = 0; $c < count($telEmpresas); $c++) {
                                                if ($c == 0) {
                                                    echo $telEmpresas[$c] -> getTelefono();
                                                } else {
                                                    echo ", " . $telEmpresas[$c] -> getTelefono();
                                                }
                                            }
                                            ?>
                                        </label>
                                        <div id="divTabla">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Fecha y hora (Ida)</th>
                                                        <th>Fecha y hora (Vuelta)</th>
                                                        <th>Origen</th>
                                                        <th>Destino</th>
                                                        <th>Asientos</th>
                                                        <th>Categoria</th>
                                                        <th>Tarifa</th>
                                                        <th>Wifi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($v = 0; $v < count($viajesEmpresas); $v++) {
                                                        $viaje = $viajesEmpresas[$v][0];
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $viaje -> getFechaHoraSalida()?></td>
                                                            <td><?php echo $viaje -> getFechaHoraLlegada()?></td>
                                                            <td><?php echo $viaje -> getOrigen()?></td>
                                                            <td><?php echo $viaje -> getDestino()?></td>
                                                            <td><?php echo $viaje -> getAsientos()?></td>
                                                            <td><?php echo $viaje -> getCategoria()?></td>
                                                            <td>$<?php echo $viaje -> getTarifa()?></td>
                                                            <td>
                                                                <?php
                                                                $wifi = $viaje -> getWifi();
                                                                if ($wifi > 0) {
                                                                    echo "Si";
                                                                } else {
                                                                    echo "No";
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <style>
                                        #principal #agencias #chk<?php echo $i?>:checked ~ #lbl<?php echo $i?> {
                                            background-color: #eee;
                                            border-bottom: none;
                                            text-shadow: #e0e0e0 1px 1px 0, 2px 2px 0px rgba(0,0,0,0.37);
                                        }
                                        #principal #agencias #chk<?php echo $i?>:checked ~ #a<?php echo $i?> {
                                            height: 250px;
                                        }
                                    </style>
                                <?php
                                }
                            }
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                    <hr>
                    <?php
                    Conexion :: cerrarConexion();
                    ?>
                    <h1>¿Cómo funciona?</h1>
                    <div id="pasos">
                        <div class="funcionamiento">
                            <i class="iconoF fas fa-search"></i>
                            <label for="iconoF fas fa-search"><h2>Busca</h2></label>
                            <p>Ingresa el lugar al que quieras viajar.</p>
                        </div>
                        <div class="funcionamiento">
                            <i class="iconoF far fa-hand-pointer"></i>
                            <label for="iconoF far fa-hand-pointer"><h2>Escoge</h2></label>
                            <p>Elige la opción que más se adecúe a tus necesidades</p>
                        </div>
                        <div class="funcionamiento">
                            <div id="asiento"></div>
                            <label for="asiento"><h2>Asigna</h2></label>
                            <p>Selecciona la cantidad de asientos que necesites</p>
                        </div>
                        <div class="funcionamiento">
                            <i class="iconoF fas fa-money-check-alt"></i>
                            <label for="iconoF fas fa-money-check-alt"><h2>Compra</h2></label>
                            <p>Realiza la compra mediante tarjeta de crédito</p>
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