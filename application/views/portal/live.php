<div class="vistaSecundaria" id="mainDiv">

    <div class="container-login100" id="divImagenFondo">
        <div class="opacidad"></div> <!-- Capa de tintado -->

        <div class="wrap-login100">
            <div class="login100-form validate-form">


                <span class="login100-form-logo" id="divLogo" style="height:150px">
                    <img id="imgLogo" width="" src="" />
                </span>

                <span class="login100-form-title p-b-20 p-t-15" style="text-transform: uppercase;" id="spanTitulo">
                    Log in
                </span>

                <span class="login100-form-title p-b-24 textoPortal" id="divTexto">
                    <a id="spanTexto"> Bienvenido </a>
                </span>

                <?php if ($this->session->flashdata('errorPortal')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $this->session->flashdata('errorPortal'); ?>
                    </div>
                <?php endif; ?>

                <div id="formDiv">
                    <form>
                        <div class="wrap-input100 validate-input" id="divEmail">
                            <input class="input100" type="email" name="email" placeholder="Email" id="inputEmail" required><span class="focus-input100" data-placeholder="&#xf15a;"></span>
                        </div>
                        <div class="wrap-input100 validate-input" id="divNombre">
                            <input class="input100" type="text" name="nombre" placeholder="Nombre" id="inputNombre" required><span class="focus-input100" data-placeholder="&#xf207;"></span>
                        </div>
                        <div class="wrap-input100 validate-input" id="divApellidos">
                            <input class="input100" type="text" name="apellidos" placeholder="Apellidos" id="inputApellidos" required><span class="focus-input100" data-placeholder="&#xf207;"></span>
                        </div>
                        <div class="contact100-form-checkbox" id="divTerminos">
                            <input class="input-checkbox100" type="checkbox" id="checkboxTerminosLive" name="checkboxTerminosLive" />
                            <label class="label-checkbox100" for="checkboxTerminosLive">He leído y acepto los <span id="linkTerminos" class="link">términos y condiciones</span></label>
                            <span id="errorTerminos" class="text-danger pl-3 mt-2" style="font-size:10px;display: none;">Por favor, acepta los términos y condiciones.</span>
                        </div>

                        '<div class="container-login100-form-btn"><button class="login100-form-btn" id="botonLogin" type="submit">Login</button></div>
                    </form>

                </div>

                <div class="text-center mt-4">
                    <div class="spinner-border" role="status" id="spinnerCargando" style="display:none">
                        <span class="sr-only">Cargando...</span>
                    </div>
                </div>

                <div class="text-center p-t-30">
                    <a class="txt1" style="color:white" href="https://proquo.es" target="_blank">
                        Powered by Proquo
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    $(document).ready(function() {

        // LISTENERS PERSONALIZACION
        // Muestra u oculta el TITULO y deshabilita el input del titulo
        $('#checkboxTitulo').change(function() {
            if (this.checked) {
                $('#inputTitulo').prop('disabled', false);
                $('#spanTitulo').show();
            } else {
                $('#inputTitulo').prop('disabled', true);
                $('#spanTitulo').hide();
            }
        });

        // Cambia el texto del TITULO
        $('#inputTitulo').on('input', function() {
            var texto = $(this).val();
            $('#spanTitulo').text(texto);
        });

        // Muestra u oculta el TEXTO INFORMATIIVO
        $('#checkboxTexto').change(function() {
            if (this.checked) {
                $('#inputTexto').prop('disabled', false);
                $('#divTexto').show();
            } else {
                $('#inputTexto').prop('disabled', true);
                $('#divTexto').hide();
            }
        });

        // Cambia el texto del TITULO
        $('#inputTexto').on('input', function() {
            var texto = $(this).val();
            $('#spanTexto').text(texto);
        });

        // Cambia el texto del BOTON
        $('#inputTextoBoton').on('input', function() {
            var texto = $(this).val();
            $('#botonLogin').text(texto);
        });

        // Cambia el tamaño del logo
        $('#inputTamañoLogo').on('input', function() {
            var valor = parseInt($(this).val()); // Convertir a int
            $('#valorTamaño').text(valor);
            var tamaño = 120 + valor; // Sumar 120 al tamaño para aplicar desfase
            $('#imgLogo').attr('width', tamaño);
        });

        // Cambia el radio de las esquinas
        $('#inputRadioEsquinas').on('input', function() {
            var valor = parseInt($(this).val()); // Convertir a int
            $('#valorRadioEsquinas').text(valor);
            $('.wrap-login100').css('border-radius', valor + 'px');
        });

        // Color primario e inicializar el input
        $('#inputColor').spectrum({
            showInput: true,
            showAlpha: true,
            preferredFormat: "hex",
            cancelText: "Cancelar",
            chooseText: "Seleccionar",
            change: function(color) {
                var colorSeleccionado =  DevolverColorRgba('#inputColor');
                var colorSecundario = DevolverColorRgba('#inputColorSecundario');
                console.log(colorSeleccionado);
                document.querySelector('.wrap-login100').style.background = 'linear-gradient(to bottom,' + colorSeleccionado + ', ' + colorSecundario + '';
            }
        });


        // Color secundario e inicializar el input
        $('#inputColorSecundario').spectrum({
            showInput: true,
            showAlpha: true,
            preferredFormat: "hex",
            cancelText: "Cancelar",
            chooseText: "Seleccionar",
            change: function(color) {
                var colorSeleccionado = DevolverColorRgba('#inputColorSecundario');
                var colorPrincipal = DevolverColorRgba('#inputColor');
                document.querySelector('.wrap-login100').style.background = 'linear-gradient(to bottom,' + colorPrincipal + ', ' + colorSeleccionado + '';
            }
        });

        // Color terciario e inicializar el input
        $('#inputColorTerciario').spectrum({
            showInput: true,
            preferredFormat: "hex",
            cancelText: "Cancelar",
            chooseText: "Seleccionar",
            change: function(color) {
                var colorSeleccionado = color.toHexString();
                document.getElementById('botonLogin').style.background = colorSeleccionado;
                $('#divImagenFondo').css({'background-color': colorSeleccionado});
            }
        });



        // LISTENERS IMÁGENES
        // Mostrar u ocultar registro de email
        $('#checkboxImagen').change(function() {
            if (this.checked) {
                $('#divImagenFondo').css({'background-image': 'url(' + urlFondo + ')'});
                $('#inputImagenFondo').prop('disabled', false);
            } else {
                $('#inputImagenFondo').prop('disabled', true);
                $('#divImagenFondo').css({'background-image': ''});
                var colorTerciario = DevolverColorRgba('#inputColorTerciario');
                $('#divImagenFondo').css({'background-color': colorTerciario});
            }
        });
        // Gestionar el input del logo
        $('#inputImagen').change(function(event) {
            var archivo = event.target.files[0]; // Obtiene el archivo seleccionado
            if (archivo) {
                var lector = new FileReader();
                lector.onload = function(e) {
                    urlLogo = e.target.result;
                    $('#divLogo').show();
                    $('#imgLogo').attr('src', e.target.result); // Establece la imagen existente con la URL del archivo seleccionado
                };
                lector.readAsDataURL(archivo); // Lee el archivo como una URL de datos
            }
        });
        // Gestionar el input de la imagen de fondo
        $('#inputImagenFondo').change(function(event) {
            var archivo = event.target.files[0]; // Obtiene el archivo seleccionado
            if (archivo) {
                var lector = new FileReader();
                lector.onload = function(e) {
                    urlFondo = e.target.result;
                    $('#divImagenFondo').css({'background-image': 'url(' + urlFondo + ')'});
                    urlFondo = e.target.result;
                };
                lector.readAsDataURL(archivo);
            }
        });
        // Cambia la opacidad del fondo
        $('#inputOpacidad').on('input', function() {
            $('#valorOpacidad').text($(this).val());
            var opacidad = parseFloat($(this).val()) / 10;
            $('.opacidad').css({
                'background-color': 'rgba(0, 0, 0, ' + opacidad + ')'
            });

        });


        // LISTENERS REGISTRO Y TERMINOS
        // Mostrar u ocultar registro de email
        $('#checkboxEmail').change(function() {
            if (this.checked) $('#divEmail').show();
            else $('#divEmail').hide();
        });
        // Mostrar u ocultar registro de nombre
        $('#checkboxNombre').change(function() {
            if (this.checked) $('#divNombre').show();
            else $('#divNombre').hide();
        });
        // Mostrar u ocultar registro de apellidos
        $('#checkboxApellidos').change(function() {
            if (this.checked) $('#divApellidos').show();
            else $('#divApellidos').hide();
        });
        // Mostrar u ocultar terminos y condiciones
        $('#checkboxTerminos').change(function() {
            if (this.checked) $('#divTerminos').show();
            else $('#divTerminos').hide();
        });


    });
</script>


<script>
    function CargarCssPortal(portal) {

        // Escondemos campos del formulario
        if (portal.REGISTRO_EMAIL == "0") $('#divEmail').hide();
        if (portal.REGISTRO_NOMBRE == "0") $('#divNombre').hide();
        if (portal.REGISTRO_APELLIDOS == "0") $('#divApellidos').hide();
        if (portal.TERMINOS == "0") $('#divTerminos').hide();


        //Color recuadro de login
        console.log(portal.COLOR);
        document.querySelector('.wrap-login100').style.background = 'linear-gradient(to bottom,' + portal.COLOR + ',' + portal.COLOR_SECUNDARIO + ')';

        // Radio de las esquinas
        $('.wrap-login100').css('border-radius', portal.RADIO_ESQUINAS + 'px');

        //Color boton login
        document.getElementById('botonLogin').style.background = portal.COLOR_TERCIARIO;


        // Textos
        if (portal.BOTON_TEXTO != "" && portal.BOTON_TEXTO != null) $('#botonLogin').text(portal.BOTON_TEXTO);

        if (portal.USAR_TITULO == "1") {
            $('#spanTitulo').show();
            if (portal.TITULO != "" && portal.TITULO != null) $('#spanTitulo').text(portal.TITULO);
        } else {
            $('#spanTitulo').hide();
        }

        if (portal.TEXTO != "" && portal.TEXTO != null) $('#spanTexto').text(portal.TEXTO);
        if (portal.USAR_TEXTO == "0") $('#divTexto').hide();


        // Circulo del logo
        var tamaño = 160 + parseInt(portal.TAMANO_LOGO);
        $('#divLogo').css({
            'width': tamaño + 'px'
        });
        $('#divLogo').css({
            'height': tamaño + 'px'
        });


        // Imagen del logo

        var tamaño = 100 + parseInt(portal.TAMANO_LOGO);
        $('#imgLogo').attr('src', "");
        if (portal.URL_IMAGEN != "" && portal.URL_IMAGEN != null) {
            $('#imgLogo').attr('src', portal.URL_IMAGEN);
            $('#imgLogo').attr('width', tamaño);

        } else $('#divLogo').hide();


        //Fondo
        if (portal.USAR_IMAGEN == "1") {
            if (portal.URL_IMAGEN_FONDO != "" && portal.URL_IMAGEN_FONDO != null) {
                var opacidad = parseFloat(portal.OPACIDAD_FONDO) / 10;
                $('.opacidad').css({
                    'background-color': 'rgba(0, 0, 0, ' + opacidad + ')'
                });
                $('#divImagenFondo').css({
                    'background-image': 'url(' + portal.URL_IMAGEN_FONDO + ')'
                });
            } else {
                $('#divImagenFondo').css({
                    'background-image': ''
                });
            }
        } else {
            $('#divImagenFondo').css({
                'background-image': ''
            });
            $('#divImagenFondo').css({
                'background-color': portal.COLOR_TERCIARIO
            });
        }
    }

    var portal = <?php echo json_encode($portal); ?>;
    CargarCssPortal(portal);
</script>