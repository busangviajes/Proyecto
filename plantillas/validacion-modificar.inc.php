<div class="bloquePrincipal">
    <div id="imagen">
        <!-- ---------------- -->
        <!-- Cuadro de imagen -->
        <!-- ---------------- -->
        <?php
        if (file_exists(DIRECTORIO_RAIZ . "/subidas/" . $usuario -> getCI())) {
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
        <!-- ---------------- -->
        <!-- -----Alerta----- -->
        <!-- ---------------- -->
        <span id="mensaje">
            <?php
            if (isset($_POST['guardar'])) {
                $validadorImg -> mostrarAviso();
            }
            ?>
        </span>
        <div id="btnImagen"><label id="lblImg" for="img">Cambiar foto</label></div>
        <input id="img" type="file" name="img" style="display: none">
        <label for="img" id="archivoSubido" style="font-size: 12px"></label>
    </div>
    <div class="bloqueDatos">
        <label><span>Nombre:</span></label>
        <input type="text" name="nombre" class="textoMod" <?php $validadorMod -> mostrarNombre() ?>>
        <?php $validadorMod -> mostrarErrorNombre(); ?>
        <label><span>Apellido:</span></label>
        <input type="text" name="apellido" class="textoMod" <?php $validadorMod -> mostrarApellido() ?>>
        <?php $validadorMod -> mostrarErrorApellido(); ?>
        <label><span>Fecha de nacimiento:</span></label>
        <span id="fechaNac">
            <input type="number" class="textoMod" name="fechaNacDia" min="1" max="31" placeholder="Día" <?php $validadorMod -> mostrarFechaNacDia() ?>>
            <span class="textoMod"><select class="textoMod" id="fechaNacMes" name="fechaNacMes" >
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
                <?php $validadorMod -> mostrarFechaNacMes() ?>
            </script>
            <input type="number" class="textoMod" name="fechaNacAnio" min="1900" max="<?php echo date("Y") ?>" placeholder="Año" <?php $validadorMod -> mostrarFechaNacAnio() ?>>
        </span>
        <?php $validadorMod -> mostrarErrorFechaNac(); ?>
        <label><span>Teléfono:</span></label>
        <input type="text" name="telefono" class="textoMod" <?php $validadorMod -> mostrarTelefono() ?>>
        <?php $validadorMod -> mostrarErrorTelefono(); ?>
        <label><span>Email:</span></label>
        <input type="text" name="email" class="textoMod" <?php $validadorMod -> mostrarEmail() ?>>
        <?php $validadorMod -> mostrarErrorEmail(); ?>
    </div>
</div>