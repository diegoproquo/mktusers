<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <script src="<?= base_url() ?>public/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="<?= base_url() ?>public/images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/css/main.css">


    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<body>

    <div class="limiter vistaSecundaria" id="mainDiv">

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




    <!--===============================================================================================-->
    <script src="<?= base_url() ?>public/vendor/animsition/js/animsition.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?= base_url() ?>public/vendor/bootstrap/js/popper.js"></script>
    <script src="<?= base_url() ?>public/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?= base_url() ?>public/vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?= base_url() ?>public/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?= base_url() ?>public/vendor/daterangepicker/daterangepicker.js"></script>
    <!--===============================================================================================-->
    <script src="<?= base_url() ?>public/vendor/countdowntime/countdowntime.js"></script>
    <!--===============================================================================================-->
    <script src="<?= base_url() ?>public/js/main.js"></script>

</body>

</html>

<script>
    $(document).ready(function() {
        $('#mainDiv').fadeIn(500);


        if (portal.TERMINOS == "1") {
            document.getElementById("linkTerminos").addEventListener("click", function() {
                var link = document.createElement("a");
                link.href = portal.URL_TERMINOS;
                link.download = "terminos_y_condiciones.pdf";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        }

    });


    var datosPeticion = <?= json_encode($datosPeticion) ?>;

    function validarFormulario() {
        var checkboxTerminos = document.getElementById('checkboxTerminos');
        var errorTerminos = document.getElementById('errorTerminos');

        // Verificar si checkboxTerminos no es nulo y está marcado
        if (checkboxTerminos && !checkboxTerminos.checked) {
            // Verificar si errorTerminos no es nulo antes de acceder a sus propiedades
            if (errorTerminos) {
                errorTerminos.style.display = 'block';
            }
            return false;
        }

        deshabilitarBoton();
        return true;
    }

    // Función para deshabilitar el botón y mostrar un spinner
    function deshabilitarBoton() {
        var botonLogin = document.getElementById("botonLogin");
        botonLogin.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        setTimeout(function() {
            botonLogin.disabled = true;
        }, 100);
    }

    function CargarCssPortal(portal) {
        //Generamos los campos del formulario en funcion de las opciones seleccionadas
        var form = '<form action="<?= base_url() ?>Guest/login" method="post" onsubmit="return validarFormulario()">'
        if (portal.REGISTRO_EMAIL == "1") form += '<div class="wrap-input100 validate-input" id="divEmail" ><input class="input100" type="email" maxlength="50" name="email" placeholder="Email" id="inputEmail" required><span class="focus-input100" data-placeholder="&#xf15a;"></span></div>';
        if (portal.REGISTRO_NOMBRE == "1") form += '<div class="wrap-input100 validate-input" id="divNombre" ><input class="input100" type="text" maxlength="50" name="nombre" placeholder="Nombre" id="inputNombre" required><span class="focus-input100" data-placeholder="&#xf207;"></span></div>';
        if (portal.REGISTRO_APELLIDOS == "1") form += '<div class="wrap-input100 validate-input" id="divApellidos" ><input class="input100" type="text" maxlength="50" name="apellidos" placeholder="Apellidos" id="inputApellidos" required><span class="focus-input100" data-placeholder="&#xf207;"></span></div>';
        if (portal.TERMINOS == "1") form += '<div class="contact100-form-checkbox" id="divTerminos" ><input class="input-checkbox100" type="checkbox" id="checkboxTerminos" name="checkboxTerminos"/><label class="label-checkbox100" for="checkboxTerminos">He leído y acepto los <span id="linkTerminos" class="link">términos y condiciones</span></label><span  id="errorTerminos"  class="text-danger pl-3 mt-2" style="font-size:10px;display: none;">Por favor, acepta los términos y condiciones.</span></div>';
        form += '<input type="hidden" name="site_id" value="' + datosPeticion.site_id + '">';
        form += '<input type="hidden" name="ap_mac" value="' + datosPeticion.ap_mac + '">';
        form += '<input type="hidden" name="client_mac" value="' + datosPeticion.client_mac + '">';
        form += '<input type="hidden" name="ssid" value="' + datosPeticion.ssid + '">';
        form += '<input type="hidden" name="url" value="' + datosPeticion.url + '">';

        var previsualizar = <?= json_encode($previsualizar) ?>;
        if(previsualizar == true) form += '<div class="container-login100-form-btn"><button class="login100-form-btn" id="botonLogin" disabled>Login</button></div></form>';
        else form += '<div class="container-login100-form-btn"><button class="login100-form-btn" id="botonLogin" type="submit">Login</button></div></form>';
        $('#formDiv').append(form);

        if (portal.TERMINOS == "1") {
            //Listener para esconder el span de aviso de aceptar terminos y condiciones cuando se aceptan
            document.getElementById('checkboxTerminos').addEventListener('click', function() {
                var errorTerminos = document.getElementById('errorTerminos');
                errorTerminos.style.display = 'none';
            });

        }


        //Color recuadro de login
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

        if (portal.USAR_TEXTO == "1") {
            $('#divTexto').show();
            if (portal.TEXTO != "" && portal.TEXTO != null) $('#spanTexto').text(portal.TEXTO);
        } else {
            $('#divTexto').hide();
        }


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
        if (portal.URL_IMAGEN != "" && portal.URL_IMAGEN != null) {
            $('#imgLogo').attr('src', portal.URL_IMAGEN);
            $('#imgLogo').attr('width', tamaño);

        } else $('#divLogo').remove();



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