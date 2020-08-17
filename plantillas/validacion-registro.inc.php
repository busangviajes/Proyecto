<div class="contenedor" id="textos">
    <div class="cajasTexto">
        <span id="nombre">
            <input type="text" class="texto" name="nombre" placeholder="Nombre" <?php $validador -> mostrarNombre() ?>>
            <span class="alerta"><?php $validador -> mostrarErrorNombre(); ?></span>
        </span>
        <span id="apellido">
            <input type="text" class="texto" name="apellido" placeholder="Apellido" <?php $validador -> mostrarApellido() ?>>
            <span class="alerta"><?php $validador -> mostrarErrorApellido(); ?></span>
        </span>
        <span id="cedula">
            <input type="text" class="texto" name="cedula" placeholder="Cédula" <?php $validador -> mostrarCI() ?>>
            <span class="alerta"><?php $validador -> mostrarErrorCI(); ?></span>
        </span>
        <span class="textoFecha">Fecha de nacimiento</span>
        <span id="fechaNac">
            <input type="number" class="texto" name="fechaNacDia" min="1" max="31" placeholder="Día" <?php $validador -> mostrarFechaNacDia() ?>>
            <span class="texto"><select class="texto" id="fechaNacMes" name="fechaNacMes">
                <option hidden>Mes</option>
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
                <?php $validador -> mostrarFechaNacMes() ?>
            </script>
            <input type="number" class="texto" name="fechaNacAnio" min="1900" max="<?php echo date("Y") ?>"  placeholder="Año" <?php $validador -> mostrarFechaNacAnio() ?>>
            <span class="alerta"><?php $validador -> mostrarErrorFechaNac(); ?></span>
        </span>
        <span id="telefono">
            <input type="text" class="texto" name="telefono" placeholder="Teléfono / Celular" <?php $validador -> mostrarTelefono() ?>>
            <span class="alerta"><?php $validador -> mostrarErrorTelefono(); ?></span>
        </span>
        <span id="usuario">
            <input type="text" class="texto" name="usuario" placeholder="Usuario" <?php $validador -> mostrarUsuario() ?>>
            <span class="alerta"><?php $validador -> mostrarErrorUsuario(); ?></span>
        </span>
        <span id="clave">
            <input type="password" class="texto" name="clave" placeholder="Contraseña" <?php $validador -> mostrarClave() ?>>
            <span class="alerta"><?php $validador -> mostrarErrorClave(); ?></span>
        </span>
        <span id="repClave">
            <input type="password" class="texto" name="repClave" placeholder="Repetir contraseña" <?php $validador -> mostrarClave() ?>>
            <span class="alerta"><?php $validador -> mostrarErrorRepClave(); ?></span>
        </span>
        <span id="email">
            <input type="email" class="texto" name="email"placeholder="Correo electrónico" <?php $validador -> mostrarEmail() ?>>
            <span class="alerta"><?php $validador -> mostrarErrorEmail(); ?></span>
        </span>
    </div>
    <div class="info">
        <label>Al hacer clic en Registrarse, acepta los <a href="">Términos de uso y la Política de privacidad.</a></label>
    </div>
</div>
<input type="submit" class="boton" name="registrar" value="Registrarse">