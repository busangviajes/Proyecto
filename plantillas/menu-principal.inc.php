    <script>
        $(document).ready(function(){
            $("input[type='checkbox']").prop('checked', false);
            $('#chkMenuPerfil').click(function() {
                if ($('#chkMenuPerfil').is(':checked')) {
                    $('#chkOverlay').prop('checked', true);
                } else {
                    $('#chkOverlay').prop('checked', false);
                }
            });

            $("#chkMenuPrincipal").click(function() {
                if ($('#chkMenuPrincipal').is(':checked')) {
                    $('#chkOverlay').prop('checked', true);
                } else {
                    $('#chkOverlay').prop('checked', false);
                }
            });

            $('#overlay').click(function() {
                $('#chkMenuPrincipal').prop('checked', false);
                $('#chkMenuPerfil').prop('checked', false);
                $('#chkOverlay').prop('checked', false);
            });

            $(document).on('click',function(e){
                if (e.target.id == 'barra' || e.target.id == 'cabecera') {
                    $('#chkMenuPrincipal').prop('checked', false);
                    $('#chkMenuPerfil').prop('checked', false);
                    $('#chkOverlay').prop('checked', false);
                }
            });
        });
    </script>
    <body id="principal">
        <header>
            <div id="barra">
                <div id="cabecera">
                <a href="<?php echo RUTA_INICIO ?>"><img src="<?php echo RUTA_IMG?>logo-texto.png" alt="Logo BUSANG" id="logo" height="50px"></a>
                    <div id="menu_imgPerfil">
                        <nav id="menuPrincipal">
                            <input type="checkbox" name="chkMenuPrincipal" id="chkMenuPrincipal" onclick="document.getElementById('chkMenuPerfil').checked = false;">
                            <label for="chkMenuPrincipal" id="btnMenuPrincipal">
                                <span></span>
                                <span></span>
                                <span></span>
                            </label>
                            <ul>
                                <li id="inicio"><a href="<?php echo RUTA_INICIO ?>">Inicio</a></li>
                                <li id="acercade"><a href="<?php echo RUTA_ACERCA_DE ?>">Acerca de</a></li>
                                <li id="faq"><a href="<?php echo RUTA_FAQ ?>">FAQ</a></li>
                                <li id="contacto"><a href="<?php echo RUTA_CONTACTO ?>">Contacto</a></li>
                            </ul>
                        </nav>
                        <input type="checkbox" name="chkMenuPerfil" id="chkMenuPerfil" onclick="document.getElementById('chkMenuPrincipal').checked = false;">
                        <label for="chkMenuPerfil">
                            <?php
                                if (file_exists(DIRECTORIO_RAIZ . "/subidas/" . $usuario -> getCI())) {
                                    ?>
                                    <div>
                                        <img class="imgPerfil" src="<?php echo SERVIDOR . '/subidas/' . $usuario -> getCI(); ?>" alt="Imagen de perfil">
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div>
                                        <img class="imgPerfil" src="<?php echo RUTA_IMG ?>user.png" alt="Imagen de perfil">
                                    </div>
                                    <?php
                                }
                            ?>
                        </label>
                        <nav id="menuPerfil">
                            <ul>
                                <div id="datosPerfil"><b>
                                    <?php echo $_SESSION['nomusuario']; ?></b>
                                </div>
                                <li><a href="<?php echo RUTA_PERFIL ?>"><i class="fas fa-user-circle"></i><label for="fas fa-user-circle">&nbsp; Perfil</label></a></li>
                                <li><a href="<?php echo RUTA_VIAJES ?>"><i class="fas fa-route"></i><label for="fas fa-route">&nbsp; Viajes</label></a></li>
                                <li><a href="<?php echo RUTA_CONTACTO ?>"><i class="fas fa-phone-alt"></i><label for="fas fa-phone-alt">&nbsp; Contacto</label></a></li>
                                <li><a href="<?php echo RUTA_CERRAR_SESION ?>"><i class="fas fa-sign-out-alt"></i><label for="fas fa-route">&nbsp; Cerrar sesión</label></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <input type="checkbox" name="chkOverlay" id="chkOverlay">
        <div id="overlay"><label for="chkOverlay"></label></div>