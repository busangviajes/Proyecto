<?php
if (!ControlSesion :: sesionIniciada())
    Redireccion :: redirigir(SERVIDOR);

include_once 'plantillas/usuarioExiste.inc.php';
include_once 'plantillas/header.inc.php';
include_once 'plantillas/menu-principal.inc.php';
?>
	<script>
	    window.onload = function() {
	        document.getElementById("acercade").style.boxShadow="inset 0px -2px 0px 0px #222222";
	    }
	</script>
        <main>
            <div class="principal">
                <div class="presentacion general" id="pagAcercade">
                    <div class="banner">
                        <h1>Acerca de nosotros</h1>
                    </div>
                </div>
                <div class="contenido">
                    <section>
                        <h1>Misión</h1>
                        <article>
                            <p>
                                Brindarle al cliente la satisfacción de viajar en bus de forma más eficiente, sin necesidad de perder tiempo en colas, pudiendo asi comprar el pasaje desde tu casa y mostrarlo desde tu celular al subir al omnibus.
                            </p>
                        </article>
                    </section>
                    <hr>
                    <section>
                        <h1>Visión</h1>
                        <article>
                            <p>
                                Ser la empresa lider en el mercado uruguayo en ventas online de pasajes de bus.
                            </p>
                        </article>
                    </section>
                </div>
            </div>
        </main>
        <?php
        include_once 'plantillas/footer.inc.php';
        ?>
    </body>
</html>