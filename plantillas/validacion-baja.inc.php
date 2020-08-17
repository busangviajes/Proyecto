<div class="bloquePrincipal">
    <div class="bloqueDatos">
        <label>Contraseña:</label>
        <input type="password" class="textoMod" name="clave">
        <span class="alerta"><?php $validador -> mostrarErrorClave(); $validador -> mostrarErrorClaveRegistrada(); ?></span>
        <label>Repetir contraseña:</label>
        <input type="password" class="textoMod" name="repClave">
        <span class="alerta"><?php $validador -> mostrarErrorRepClave(); ?></span>
    </div>
</div>
<div align="right" class="cajaBoton">
    <input type="submit" class="btn" name="confirmar" value="Confirmar">
</div>