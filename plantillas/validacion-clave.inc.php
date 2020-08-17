<div class="contenedor" id="claves">
    <div class="cajasTexto">
        <span id="clave">
            <input type="password" class="texto" name="clave" placeholder="Nueva contraseña" required autofocus>
            <span class="alerta"><?php $validador -> mostrarErrorClave(); ?></span>
        </span>
        <span id="repClave">
            <input type="password" class="texto" name="repClave" placeholder="Repetir contraseña" required autofocus>
            <span class="alerta"><?php $validador -> mostrarErrorRepClave(); ?></span>
        </span>
    </div>
</div>
<input type="submit" class="boton" name="enviar" value="Enviar">