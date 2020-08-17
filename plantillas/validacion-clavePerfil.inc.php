<div class="bloquePrincipal">
    <div class="bloqueDatos">
        <label>Antigua contraseña:</label>
        <input type="password" class="textoMod" name="claveAntigua">
        <span class="alerta"><?php $validador -> mostrarErrorClaveAntigua(); ?></span>
        <span class="separadorPerfil"></span>
        <label>Nueva contraseña:</label>
        <input type="password" class="textoMod" name="clave">
        <span class="alerta"><?php $validador -> mostrarErrorClave(); ?></span>
        <label>Repetir contraseña:</label>
        <input type="password" class="textoMod" name="repClave">
        <span class="alerta"><?php $validador -> mostrarErrorRepClave(); ?></span>
    </div>
</div>
<div align="right" class="cajaBoton">
    <input type="submit" class="btn" name="guardar" value="Guardar">
</div>