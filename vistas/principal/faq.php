<?php
if (!ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(SERVIDOR);

include_once 'plantillas/usuarioExiste.inc.php';
include_once 'plantillas/header.inc.php';
include_once 'plantillas/menu-principal.inc.php';
?>
	<script>
	    window.onload = function() {
	        document.getElementById("faq").style.boxShadow="inset 0px -2px 0px 0px #222222";
	    }
	</script>
        <main>
            <div class="principal">
                <div class="presentacion general" id="pagFaq">
                    <div class="banner">
                        <h1>Preguntas frecuentes</h1>
                    </div>
                </div>
                <div class="contenido">
                    <section>
                        <h1 class="subrayado">¿Por qué me piden los datos al ingresar?</h1>
                        <article>
                            <p>Los datos pedidos al inicio son necesarios a la hora de registrar su pasaje, todos los datos son sumamente privados y la empresa <b>NO LOS UTILIZARÁ</b> para venderlos, serán guardados y sumamente confidenciales, le pedimos que ninguna persona esté cerca cuando ingrese utilizando su pin al ingresar.</p>
                        </article>
                        <hr class="lineaFAQ">
                    </section>
                    <section>
                        <h1 class="subrayado">Si soy extranjero, ¿Puedo usarla igual?</h1>
                        <article>
                            <p>Por supuesto, cualquier persona puede utilizar la plataforma mediante su telefono movil o su laptop/PC, fuera y dentro de su casa.</p>
                        </article>
                        <hr class="lineaFAQ">
                    </section>
                    <section>
                        <h1 class="subrayado">Si tengo algún problema, ¿Dónde puedo ir?</h1>
                        <article>
                            <p>Usted puede contactarse a través de contacto@busang.com.uy o enviarnos un correo desde la plataforma dejandonos saber su asunto y su mensaje, a la brevedad será respondido.</p>
                        </article>
                        <hr class="lineaFAQ">
                    </section>
                </div>
            </div>
        </main>
        <?php
        include_once 'plantillas/footer.inc.php';
        ?>
    </body>
</html>