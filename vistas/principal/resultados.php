<?php
if (!ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(SERVIDOR);

include_once 'db/repositorios/RepositorioEmpresa.inc.php';
include_once 'plantillas/usuarioExiste.inc.php';
include_once 'plantillas/header.inc.php';
include_once 'plantillas/menu-principal.inc.php';

$busqueda = null;
$resultados1 = [];
$resultados2 = [];

if (isset($_POST['buscar'])) {
    if (isset($_POST['t1']) && isset($_POST['t2']) && isset($_POST['t3'])) {
        $busqueda = $_POST['t1'] . "#" . $_POST['t2'] . "#" . $_POST['t3']; // Origen#Destino#Fecha
        
        Conexion :: abrirConexion();
        $resultados1 = RepositorioViaje :: buscar(Conexion :: getConexion(), $busqueda, " Todas ORDER BY fechaHoraSalida");
        if (count($resultados1)) {
            for ($a = 0; $a < count($resultados1); $a++) {
                $f1 = $resultados1[$a][0] -> getFechaHoraLlegada();

                if (preg_match('/\-/', $_POST['t3']) || ((!empty($_POST['t1']) && !empty($_POST['t2'])))) {
                    // Si indico fecha de vuelta o coloco un origen y destino
                    $resultados2 = RepositorioViaje :: buscar(Conexion :: getConexion(), $busqueda . "#vuelta", " Todas ORDER BY fechaHoraSalida");
                    
                    $cantidadResultados2 = count($resultados2);
                    for ($b = 0; $b < $cantidadResultados2; $b++) {
                        $f2 = $resultados2[$b][0] -> getFechaHoraSalida();
                        if ($f1 > $f2) { // Si la fecha de llegada del origen es mayor a la fecha de salida del destino, no tiene sentido mostrarlo
                            unset($resultados2[$b]);
                        }
                    }
                    $resultados2 = array_merge($resultados2);
                } else {
                    // Si no indico fecha de vuelta, no es necesario mostrar la segunda tabla
                    $resultados2 = null;
                }
            }
        }
        Conexion :: cerrarConexion();
    }
} else {
    $resultados1 = [];
    $resultados2 = [];
}
?>
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
        <main class="resultados">
            <div class="principal" id="pagResultados">
                <div class="presentacion">
                    <div class="banner">
                        <form action="<?php echo RUTA_RESULTADOS ?>" method="POST">
                            <span id="busqueda">
                                <span id="txt1"><input type="text" class="txt" id="t1" name="t1" placeholder="Origen" 
                                <?php
                                if (isset($_POST['t1'])) {
                                    echo "value='" . $_POST['t1'] . "'";
                                } else {
                                    echo "value=''";
                                }
                                ?> onkeypress="return val(event)" onpaste="return false" autocomplete="off" autocomplete="nope"></span>
                                <span id="txt2"><input type="text" class="txt" id="t2" name="t2" placeholder="Destino" 
                                <?php
                                if (isset($_POST['t2'])) {
                                    echo "value='" . $_POST['t2'] . "'";
                                } else {
                                    echo "value=''";
                                }
                                ?> onkeypress="return val(event)" onpaste="return false" autocomplete="off" autocomplete="nope"></span>
                                <span id="txt3">
                                    <input type="text" class="txt" id="t3" name="t3" placeholder="Ida y vuelta" 
                                    <?php
                                    if (isset($_POST['t3'])) {
                                        echo "value='" . $_POST['t3'] . "'";
                                    } else {
                                        echo "value=''";
                                    }
                                    ?> autocomplete="off" autocomplete="nope" readonly>
                                    <input type="button" id="cruz" value="&#215;">
                                </span>
                                <span><button type="submit" id="btnBuscar" name="buscar"><i class="fas fa-search"></i></button></span>
                            </span>
                        </form>
                    </div>
                    <div class="contenido">
                        <!-- Solo mostrar busqueda avanzada si se encontraron resultados -->
                        <?php
                        if (count($resultados1)) {
                            ?>
                                <div id="bloqueBusquedaAvanzada">
                                    <p id="avz">Búsqueda avanzada</p>
                                    <div>
                                        <div class="busquedaAvanzada">
                                            <?php
                                            if (!is_null($resultados2) && count($resultados2)) {
                                                ?>
                                                <p class="tipoBusqueda">Fecha de ida</p>
                                                <?php
                                            }
                                            Conexion :: abrirConexion();
                                            $empresas = RepositorioEmpresa :: getEmpresas(Conexion :: getConexion());
                                            Conexion :: cerrarConexion();
                                            ?>
                                            <p>Mostrar solo:</p>
                                            <div class="opciones">
                                                <div>
                                                    <label>Categoría:</label>
                                                    <div>
                                                        <p><input type="checkbox" name="cat1" class="comun" id="co1"><label for="co1">Común</label></p>
                                                        <p><input type="checkbox" name="cat1" class="semi-cama" id="sc1"><label for="sc1">Semi-cama</label></p>
                                                        <p><input type="checkbox" name="cat1" class="cama" id="ca1"><label for="ca1">Cama</label></p>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label>Horario de salida:</label>
                                                    <div>
                                                        <p><input type="checkbox" name="fsalida1" class="mañana" id="ms1"><label for="ms1">Mañana</label></p>
                                                        <p><input type="checkbox" name="fsalida1" class="tarde" id="ts1"><label for="ts1">Tarde</label></p>
                                                        <p><input type="checkbox" name="fsalida1" class="noche" id="ns1"><label for="ns1">Noche</label></p>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label>Horario de llegada:</label>
                                                    <div>
                                                        <p><input type="checkbox" name="fllegada1" class="mañana" id="ml1"><label for="ml1">Mañana</label></p>
                                                        <p><input type="checkbox" name="fllegada1" class="tarde" id="tl1"><label for="tl1">Tarde</label></p>
                                                        <p><input type="checkbox" name="fllegada1" class="noche" id="nl1"><label for="nl1">Noche</label></p>
                                                    </div>
                                                </div>
                                                <label>Compañia:</label>
                                                <div>
                                                    <select name="companiaIda" class="compania" id="companiaIda">
                                                        <option value="opcIda0">Todas</option>
                                                        <?php
                                                        for ($i = 0; $i < count($empresas); $i++) {
                                                            ?>
                                                            <option value="opcIda<?php echo $i + 1?>"><?php echo $empresas[$i] -> getNombre()?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        if (!is_null($resultados2) && count($resultados2)) {
                                            ?>
                                            <div class="busquedaAvanzada" style="background-color: #EFEFEF">
                                                <p class="tipoBusqueda">Fecha de vuelta</p>
                                                <p>Mostrar solo:</p>
                                                <div class="opciones">
                                                    <div>
                                                        <label>Categoría:</label>
                                                        <div>
                                                            <p><input type="checkbox" name="cat2" class="comun" id="co2"><label for="co2">Común</label></p>
                                                            <p><input type="checkbox" name="cat2" class="semi-cama" id="sc2"><label for="sc2">Semi-cama</label></p>
                                                            <p><input type="checkbox" name="cat2" class="cama" id="ca2"><label for="ca2">Cama</label></p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label>Horario de salida:</label>
                                                        <div>
                                                            <p><input type="checkbox" name="fsalida2" class="mañana" id="ms2"><label for="ms2">Mañana</label></p>
                                                            <p><input type="checkbox" name="fsalida2" class="tarde" id="ts2"><label for="ts2">Tarde</label></p>
                                                            <p><input type="checkbox" name="fsalida2" class="noche" id="ns2"><label for="ns2">Noche</label></p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label>Horario de llegada:</label>
                                                        <div>
                                                            <p><input type="checkbox" name="fllegada2" class="mañana" id="ml2"><label for="ml2">Mañana</label></p>
                                                            <p><input type="checkbox" name="fllegada2" class="tarde" id="tl2"><label for="tl2">Tarde</label></p>
                                                            <p><input type="checkbox" name="fllegada2" class="noche" id="nl2"><label for="nl2">Noche</label></p>
                                                        </div>
                                                    </div>
                                                    <label>Compañia:</label>
                                                    <div>
                                                        <select name="companiaVuelta" class="compania" id="companiaVuelta">
                                                            <option value="opcVuelta0">Todas</option>
                                                            <?php
                                                            for ($i = 0; $i < count($empresas); $i++) {
                                                                ?>
                                                                <option value="opcVuelta<?php echo $i + 1?>"><?php echo $empresas[$i] -> getNombre()?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div id="opciones-orden">
                                            <p>Ordenar por: </p>
                                            <span><input type="radio" name="ord" class="fechaHoraSalida" id="fs" checked><label for="fs">Fecha de salida</label></span>
                                            <span><input type="radio" name="ord" class="fechaHoraLlegada" id="fl"><label for="fl">Fecha de llegada</label></span>
                                            <span><input type="radio" name="ord" class="tarifa" id="ta"><label for="ta">Tarifa</label></span>
                                        </div>
                                </div>
                            <?php
                        } else {
                            ?>
                            <hr style="width: 40%; background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(255, 255, 255, 0.75), rgba(0, 0, 0, 0));">
                            <?php
                        }
                        ?>
                        <div id="tablaViajes">
                            <div id="lista">
                                <?php
                                include_once 'plantillas/tarjeta-viajes.inc.php'
                                ?>
                            </div>
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
                        yearSuffix: ''
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

                        $(document).ready(function (){
                            if ($('#t3').val() != '') {
                                $('#cruz').css('display', 'block');
                            }
                        });

                        $('#t3').change(function() {
                            $('#cruz').css('display', 'block');
                        })

                        $('#cruz').click(function () {
                            $('#t3').val('');
                            $('#cruz').css('display', 'none');
                        });

                        var cat1 = '';
                        var fsalida1 = '';
                        var fllegada1 = '';
                        var sentencias1 = '';
                        var compania1 = '';

                        // Fecha de vuelta
                        var cat2 = '';
                        var fsalida2 = '';
                        var fllegada2 = '';
                        var sentencias2 = '';
                        var compania2 = '';

                        var ord = '';
                        
                        $(document).ready(function (){
                            var categorias1 = new Array();
                            $("input[name='cat1']").click(function() {
                                if ($(this).is(':checked')) {
                                    var clase = $(this).attr('class');
                                    categorias1.push(clase);
                                } else {
                                    var clase = $(this).attr('class');
                                    categorias1.splice(categorias1.indexOf(clase), 1);
                                }
                                if (categorias1.length != 0) {
                                    cat1 = '[CAT1 - ' + categorias1.toString() + ']';
                                } else {
                                    cat1 = '';
                                }
                            })
                            var fechaSalida1 = new Array();
                            $("input[name='fsalida1']").click(function() {
                                if ($(this).is(':checked')) {
                                    var clase = $(this).attr('class');
                                    fechaSalida1.push(clase);
                                } else {
                                    var clase = $(this).attr('class');
                                    fechaSalida1.splice(fechaSalida1.indexOf(clase), 1);
                                }
                                if (fechaSalida1.length != 0) {
                                    fsalida1 = '[FSALIDA1 - ' + fechaSalida1.toString() + ']';
                                } else {
                                    fsalida1 = '';
                                }
                            })
                            var fechaLlegada1 = new Array();
                            $("input[name='fllegada1']").click(function() {
                                if ($(this).is(':checked')) {
                                    var clase = $(this).attr('class');
                                    fechaLlegada1.push(clase);
                                } else {
                                    var clase = $(this).attr('class');
                                    fechaLlegada1.splice(fechaLlegada1.indexOf(clase), 1);
                                }
                                if (fechaLlegada1.length != 0) {
                                    fllegada1 = '[FLLEGADA1 - ' + fechaLlegada1.toString() + ']';
                                } else {
                                    fllegada1 = '';
                                }
                            })

                            // F E C H A    D E    V U E L T A
                            var categorias2 = new Array();
                            $("input[name='cat2']").click(function() {
                                if ($(this).is(':checked')) {
                                    var clase = $(this).attr('class');
                                    categorias2.push(clase);
                                } else {
                                    var clase = $(this).attr('class');
                                    categorias2.splice(categorias2.indexOf(clase), 1);
                                }
                                if (categorias2.length != 0) {
                                    cat2 = '[CAT2 - ' + categorias2.toString() + ']';
                                } else {
                                    cat2 = '';
                                }
                            })
                            var fechaSalida2 = new Array();
                            $("input[name='fsalida2']").click(function() {
                                if ($(this).is(':checked')) {
                                    var clase = $(this).attr('class');
                                    fechaSalida2.push(clase);
                                } else {
                                    var clase = $(this).attr('class');
                                    fechaSalida2.splice(fechaSalida2.indexOf(clase), 1);
                                }
                                if (fechaSalida2.length != 0) {
                                    fsalida2 = '[FSALIDA2 - ' + fechaSalida2.toString() + ']';
                                } else {
                                    fsalida2 = '';
                                }
                            })
                            var fechaLlegada2 = new Array();
                            $("input[name='fllegada2']").click(function() {
                                if ($(this).is(':checked')) {
                                    var clase = $(this).attr('class');
                                    fechaLlegada2.push(clase);
                                } else {
                                    var clase = $(this).attr('class');
                                    fechaLlegada2.splice(fechaLlegada2.indexOf(clase), 1);
                                }
                                if (fechaLlegada2.length != 0) {
                                    fllegada2 = '[FLLEGADA2 - ' + fechaLlegada2.toString() + ']';
                                } else {
                                    fllegada2 = '';
                                }
                            })

                            $("select[name='companiaIda']").change(function() {
                                compania1 = '[COMP1 - ' + $(this).children("option:selected").text() + ']';
                            })

                            compania1 = '[COMP1 - Todas]';

                            $("select[name='companiaVuelta']").change(function() {
                                compania2 = '[COMP2 - ' + $(this).children("option:selected").text() + ']';
                            })

                            compania2 = '[COMP2 - Todas]';

                            // O R D E N A R    L I S T A
                            $("input[name='ord']").click(function() {
                                if ($(this).is(':checked')) {
                                    var clase = $(this).attr('class');
                                } else {
                                    var clase = '';
                                }
                                ord = '[ORD - ' + clase + ']';
                            })
                            // Ordenar automaticamente por el radio seleccionado (Al seleccionar otra cosa)
                            ord = '[ORD - ' + $("input[name='ord']").attr('class') + ']';
                            
                            $(document).on('click',function(e){
                                if ($(e.target).is($('input[name="cat1"]')) || $(e.target).is($('input[name="fsalida1"]')) || 
                                $(e.target).is($('input[name="fllegada1"]')) || $(e.target).is($('input[name="cat2"]')) || 
                                $(e.target).is($('input[name="fsalida2"]')) || $(e.target).is($('input[name="fllegada2"]')) || 
                                $(e.target).is($('select[name="companiaIda"]')) || $(e.target).is($('select[name="companiaVuelta"]')) || 
                                $(e.target).is($('input[name="ord"]'))) {
                                    // Ordenar automaticamente por el radio seleccionado
                                    var busqueda = $('#t1').val() + "#" + $('#t2').val() + "#" + $('#t3').val();
                                    var sentencia = cat1 + fsalida1 + fllegada1 + compania1 + cat2 + fsalida2 + fllegada2 + compania2 + ord;
                                    $.post(
                                        "busqueda-avanzada.php",
                                        { "sentencia":sentencia, "busqueda":busqueda },
                                        function(respuesta){
                                            $('#lista').html(respuesta);
                                        }
                                    )
                                }
                            })
                        })
                    </script>
                </div>
            </div>
        </main>
        <?php
        include_once 'plantillas/footer.inc.php';
        ?>
    </body>
</html>