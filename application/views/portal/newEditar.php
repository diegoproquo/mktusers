<div class="container-fluid">

    <div style="width:88%; margin: 0 auto;">
        <h1 class="mt-4">Portal cautivo</h1>
        <div class="breadcrumb mb-1" style="position: relative;">
            <li class="breadcrumb-item">Proquo MKT</li>
            <li class="breadcrumb-item active">Usuarios</li>
            <div class="alert alert-info" role="alert" id="alertCambios" style="position: absolute; top: 10px; right: 10px; font-size: 14px; padding: 0.25rem 0.5rem; display:none;">
                Tiene cambios sin guardar
            </div>
        </div>
    </div>


    <div class="mainDiv">
        <div class="content_pagina">

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-tab="#tabPersonalizacion" href="#">Personalización</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" data-tab="#tabImagenes" href="#">Imágenes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-tab="#tabRegistro" href="#">Registro</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-tab="#tabRedireccion" href="#">Redirección</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-tab="#tabLimites" href="#">Límites</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-tab="#tabTerminos" href="#">Términos y condiciones</a>
                </li>
            </ul>

            <div class="row">
                <div class="col-md-6">
                    <div class="tab-content">
                        <div id="tabPersonalizacion" class="tab-pane active">
                            <h3 class="mb-3">Personalización del portal</h3>
                            <label class="mb-4 ps-2">Personalice los ajustes visuales de su portal</label>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="inputTitulo" class="form-label">Título</label>
                                            <input class="form-check-input ml-2" type="checkbox" id="checkboxTitulo" />
                                            <input type="text" class="form-control" id="inputTitulo">
                                            <span class="help-block">Por defecto: LOG IN </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="inputTexto" class="form-label"> Texto informativo</label>
                                            <input class="form-check-input ml-2" type="checkbox" id="checkboxTexto" />
                                            <textarea rows="1" type="text" class="form-control" id="inputTexto"> </textarea>
                                            <span class="help-block">Por defecto: Bienvenido </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="inputTextoBoton" class="form-label">Texto botón login</label>
                                            <input type="text" class="form-control" id="inputTextoBoton">
                                            <span class="help-block">Por defecto: Login </span>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="inputColor" class="form-label">Color principal</label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control color-picker" id="inputColor" value="#ffffff">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputColorSecundario" class="form-label">Color secundario</label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control color-picker" id="inputColorSecundario" value="#ffffff">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputColorTerciario" class="form-label">Color terciario</label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control color-picker" id="inputColorTerciario" value="#ffffff">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputTamañoLogo" class="form-label mt-4">Tamaño del logo: <span id="valorTamaño"></span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputRadioEsquinas" class="form-label mt-4">Radio de las esquinas: <span id="valorRadioEsquinas"></span></label>
                                        </div>


                                        <div class="col-md-6">
                                            <input type="range" id="inputTamañoLogo" min="0" max="100" value="50" style="max-width:75%">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="range" id="inputRadioEsquinas" min="0" max="100" value="50" style="max-width:75%">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div id="tabImagenes" class="tab-pane">
                            <h3 class="mb-3">Selección de imágenes</h3>
                            <label class="mb-4 ps-2">Puede seleccionar imágenes personalizadas que se mostrarán en la página de login.</label>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="row">
                                        <div class="col-md-11">
                                            <label for="inputImagen" class="form-label">Logo</label>
                                            <input type="file" class="form-control" id="inputImagen" accept=".jpg,.png,.jpeg">
                                        </div>
                                        <div class="col-md-1 pt-2">
                                            <button class="btn btn-secondary mt-4" onclick="EliminarInput(0)">Cancelar</button>
                                        </div>
                                        <span class="help-block mb-4">Dimensiones máximas: 900px/900px</span>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-11">
                                            <label for="inputImagenFondo" class="form-label">Imagen de fondo</label>
                                            <input class="form-check-input  ml-2" type="checkbox" id="checkboxImagen" checked="true" />
                                            <input type="file" class="form-control" id="inputImagenFondo" accept=".jpg,.png,.jpeg">
                                        </div>
                                        <div class="col-md-1 pt-2">
                                            <button class="btn btn-secondary mt-4" onclick="EliminarInput(1)">Cancelar</button>
                                        </div>
                                        <span class="help-block mb-4">Tamaño máximo: 5MB</span>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputImagenFondo" class="form-label">Opacidad del fondo: <span id="valorOpacidad"></span></label>
                                            <p></p>
                                            <input type="range" id="inputOpacidad" min="0" max="10" value="5" style="max-width:80%">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div id="tabRegistro" class="tab-pane">
                            <h3 class="mb-3">Registro del cliente</h3>
                            <label class="mb-4 ps-2">Ajuste los campos necesarios para completar el registro en la red</label>
                            <div class="row mb-3 pl-4">
                                <div class="col-md-6">
                                    <input class="form-check-input" type="checkbox" id="checkboxEmail" />
                                    <label class="ms-2" for="checkboxEmail">Email</label>
                                </div>
                            </div>
                            <div class="row mb-3 pl-4">
                                <div class="col-md-6">
                                    <input class="form-check-input" type="checkbox" id="checkboxNombre" />
                                    <label class="ms-2" for="checkboxNombre">Nombre</label>
                                </div>
                            </div>
                            <div class="row pl-4">
                                <div class="col-md-6">
                                    <input class="form-check-input" type="checkbox" id="checkboxApellidos" />
                                    <label class="ms-2" for="checkboxApellidos">Apellidos</label>
                                </div>
                            </div>

                        </div>

                        <div id="tabRedireccion" class="tab-pane">
                            <h3 class="mb-3">Página de redirección</h3>
                            <label class="mb-4 ps-2">Especifique la página a la que se redirigirá después del login</label>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="inputRedireccion" class="form-label">URL</label>
                                    <input placeholder="Ej: http://proquo.es/" type="text" class="form-control" id="inputRedireccion">
                                    <span class="help-block">Debido a limitaciones del sistema operativo Android, esta función solo está disponible para dispositvos iOS. Se recomienda usar redirecciones http en lugar de https.</span>
                                </div>
                            </div>
                        </div>

                        <div id="tabLimites" class="tab-pane">
                            <h3 class="mb-3">Límites de sesión</h3>
                            <label class="mb-4 ps-2">Ajuste el tiempo máximo que el cliente podrá permanecer conectado antes de tener que registrarse de nuevo</label>

                            <div class="row mb-3 ms-4">
                                <input class="form-check-input" type="checkbox" id="checkboxLimiteTiempo" />
                                <label class="ms-2" for="checkboxLimiteTiempo">Habilitar límite de tiempo</label>
                            </div>
                            <div class="row mb-3">
                                <label class="form-label">Duración</label>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="inputTiempo" min="1" value="30" required>
                                        <select class="form-select ms-3" id="inputUnidadTiempo">
                                            <option value="minutos">Minutos</option>
                                            <option value="horas">Horas</option>
                                        </select>
                                    </div>
                                    <span class="help-block mb-4">Por defecto: 8 horas</span>
                                </div>
                            </div>
                        </div>



                        <div id="tabTerminos" class="tab-pane">
                            <h3 class="mb-3">Términos y condiciones</h3>
                            <label class="mb-4 ps-2">Escoja si el usuario debe aceptar los términos y condiciones para poder registrarse en la red</label>
                            <div class="row mb-3 pl-4">
                                <div class="col-md-12">
                                    <input class="form-check-input" type="checkbox" id="checkboxTerminos" />
                                    <label class="ms-2" for="checkboxTerminos">Aceptar términos y condiciones</label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="inputTerminos" class="form-label">Archivo PDF</label>
                                    <input type="file" class="form-control" id="inputTerminos" accept=".pdf">
                                    <span class="help-block">Tamaño máximo 10MB <a class="help-block ms-3" id="spanTerminos"> </a></span>

                                </div>
                            </div>
                        </div>

                    </div><!-- Fin del tab content-->

                    <div class="footer_pagina text-end mt-auto">
                        <div class="row justify-content-end">
                            <div class="col-md-12">
                                <button class="btn btn-info" onclick="PrevisualizarPortal()">Previsualizar</button>
                                <button class="btn btn-primary font-weight-bold" onclick="GuardarPortal()">Guardar</button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-6 pt-5">
                    <div style="transform: scale(0.6); transform-origin: top;">
                        <?php $this->load->view('portal/live'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        //Las variables "guardado" sirven para almacenar la url guardado en BD
        var urlLogoGuardado;
        var urlFondoGuardado;
        //Estas otras son para almacenar temporalmente el valor de los inputs de las imagenes, para mostrarlas en el live
        var urlLogo;
        var urlFondo;

        $(document).ready(function() {

            // Capturar el clic en cada pestaña
            $('.nav-tabs a').click(function(e) {
                e.preventDefault();
                $('.tab-content .tab-pane').hide();

                var tabId = $(this).data('tab');
                $(tabId).fadeIn(300);
                $('.nav-tabs a').removeClass('active');
                $(this).addClass('active');
            });
            $('.nav-tabs a:first').trigger('click');

            var portal = <?php echo json_encode($portal); ?>;
            urlLogoGuardado = portal.URL_IMAGEN;
            urlFondoGuardado = portal.URL_IMAGEN_FONDO;
            urlLogo = portal.URL_IMAGEN;
            urlFondo = portal.URL_IMAGEN_FONDO;
            RellenarDatosPortal(portal);


            // INICIO CONTROL DE INPUT DE LIMITE DE TIEMPO, PARA QUE NO INTRODUZCAN VALORES RAROS.
            // Las horas solo podran tener un decimal y ha de ser .5
            $('#inputUnidadTiempo').change(function() {
                // Obtener el valor seleccionado
                var unidadTiempo = $(this).val();

                // Restablecer las restricciones del inputTiempo
                $('#inputTiempo').prop('step', 1);
                $('#inputTiempo').prop('min', 1);

                // Aplicar las restricciones según la opción seleccionada
                if (unidadTiempo === 'minutos') {
                    $('#inputTiempo').prop('step', 1);
                    $('#inputTiempo').prop('min', 1);
                    $('#inputTiempo').prop('max', 60);

                    // Eliminar decimales si los hay
                    var valor = $('#inputTiempo').val();
                    $('#inputTiempo').val(Math.floor(valor));
                } else if (unidadTiempo === 'horas') {
                    // Si se selecciona horas, permitir intervalos de 0.5 entre 1 y 24
                    if ($('#inputTiempo').val() > 24) $('#inputTiempo').val(1);
                    $('#inputTiempo').prop('step', 0.5);
                    $('#inputTiempo').prop('min', 0.5);
                    $('#inputTiempo').prop('max', 24);
                }
            });
            $('#inputTiempo').on('input', function() {
                var valor = $(this).val();
                var unidadTiempo = $('#inputUnidadTiempo').val();

                // Verificar si la entrada es válida según la unidad de tiempo seleccionada
                if (unidadTiempo === 'minutos') {
                    // Permitir solo valores enteros entre 1 y 60
                    if (isNaN(valor) || valor < 1 || valor > 60 || valor % 1 !== 0) {
                        $(this).val(''); // Limpiar el valor si no es válido
                    }
                } else if (unidadTiempo === 'horas') {
                    // Permitir solo un decimal y que sea 5 entre 0.5 y 24
                    if (isNaN(valor) || valor < 0.5 || valor > 24) {
                        $(this).val(''); // Limpiar el valor si no es válido
                    } else {
                        // Obtener la parte decimal del valor
                        var decimalPart = parseFloat((valor % 1).toFixed(1));

                        // Permitir solo un decimal y que sea 5
                        if (decimalPart !== 0.5 && decimalPart !== 0) {
                            $(this).val(''); // Limpiar el valor si no cumple la condición
                        }
                    }
                }
            }); // FIN CONTROL INPUTS LIMITE TIEMPO


            // Listener para poner el "Tiene cambios sin guardar"
            var inputs = document.querySelectorAll('input');
            inputs.forEach(function(input) {
                input.addEventListener('input', function() {
                    $('#alertCambios').show();
                });
            });


        });


        function GuardarPortal() {
            // Crear un nuevo objeto FormData
            var formData = new FormData();

            formData.append('site_id', site_id);
            // Personalizacion
            formData.append('titulo', $('#inputTitulo').val());
            formData.append('usar_titulo', $('#checkboxTitulo').prop('checked') ? 1 : 0);
            formData.append('texto', $('#inputTexto').val());
            formData.append('usar_texto', $('#checkboxTexto').prop('checked') ? 1 : 0);
            formData.append('textoBoton', $('#inputTextoBoton').val());
            formData.append('color', DevolverColorRgba('#inputColor'));
            formData.append('colorSecundario', DevolverColorRgba('#inputColorSecundario'));
            formData.append('colorTerciario', $('#inputColorTerciario').val());
            formData.append('tamano_logo', $('#inputTamañoLogo').val());
            formData.append('radio_esquinas', $('#inputRadioEsquinas').val());


            //Imagenes
            formData.append('imagenFondo', $('#inputImagenFondo')[0].files[0]); // Archivo de la imagen de fondo
            formData.append('terminosArchivo', $('#inputTerminos')[0].files[0]); // Archivo de términos
            formData.append('opacidad_fondo', $('#inputOpacidad').val());
            formData.append('usar_imagen', $('#checkboxImagen').prop('checked') ? 1 : 0);

            // Registro
            formData.append('checkboxEmail', $('#checkboxEmail').prop('checked') ? 1 : 0);
            formData.append('checkboxNombre', $('#checkboxNombre').prop('checked') ? 1 : 0);
            formData.append('checkboxApellidos', $('#checkboxApellidos').prop('checked') ? 1 : 0);

            // Redireccion
            formData.append('redireccion', $('#inputRedireccion').val());

            // Limites
            formData.append('limiteSesion', $('#checkboxLimiteTiempo').prop('checked') ? 1 : 0);
            var tiempo = $('#inputTiempo').val();
            if ($('#inputUnidadTiempo').val() == "horas") {
                tiempo *= 60; // Convertir horas a minutos
            }
            formData.append('limite_sesion_minutos', tiempo);

            // Terminos y condiciones
            formData.append('terminos', $('#checkboxTerminos').prop('checked') ? 1 : 0);
            formData.append('imagen', $('#inputImagen')[0].files[0]); // Archivo del logo



            // Realizar la solicitud Ajax
            $.ajax({
                url: '<?= base_url() ?>/Portal/GuardarEditar',
                type: 'POST',
                data: formData,
                processData: false, // No procesar los datos
                contentType: false, // No configurar el tipo de contenido
                success: function(response) {
                    var response = JSON.parse(response);

                    $('#alertCambios').hide();

                    var error = 0;
                    if (response[1] != "") {
                        MostrarAlertError("El logo no cumple con los requisitos");
                        error = 1;
                    }
                    if (response[2] != "") {
                        MostrarAlertError("La imagen de fondo no cumple con los requisitos");
                        error = 1;
                    }
                    if (response[3] != "") {
                        MostrarAlertError("El archivo de términos y condiciones no cumple con los requisitos");
                        error = 1;
                    }

                    //La función está en el header
                    if (error == 0) MostrarAlertCorrecto("Datos guardados correctamente");


                    urlLogoGuardado = response[0]['URL_IMAGEN'];
                    urlFondoGuardado = response[0]['URL_IMAGEN_FONDO'];
                    urlLogo = response[0]['URL_IMAGEN'];
                    urlFondo = response[0]['URL_IMAGEN_FONDO'];

                },
                error: function(xhr, status, error) {
                    MostrarAlertError("Algo no ha ido como se esperaba, no se han guardado los datos");
                    console.error('Error al guardar los datos:', error);
                }
            });
        }

        function EliminarInput(opcion) {
            if (opcion == 0) { //  Logo
                $('#inputImagen').val('');
                $('#imgLogo').attr('src', urlLogoGuardado);
                if (urlLogoGuardado == "" || urlLogoGuardado == null) $('#divLogo').hide();
                urlLogo = urlLogoGuardado;
            } else if (opcion == 1) { //Imagen de fondo
                $('#inputImagenFondo').val('');
                $('#divImagenFondo').css({
                    'background-image': 'url(' + urlFondoGuardado + ')'
                });
                urlFondo = urlFondoGuardado;
            }
        }

        function PrevisualizarPortal() {
            window.open('<?= base_url() ?>Portal/show/?site=' + site_id);
        }

        function RellenarDatosPortal(portal) {

            //Personalizacion
            $('#inputTitulo').val(portal['TITULO']);
            $('#checkboxTitulo').prop('checked', portal['USAR_TITULO'] == "1");
            if (portal.USAR_TITULO == "0") $('#inputTitulo').prop('disabled', true);
            $('#inputTexto').val(portal['TEXTO']);
            if (portal.USAR_TEXTO == "0") $('#inputTexto').prop('disabled', true);
            $('#checkboxTexto').prop('checked', portal['USAR_TEXTO'] == "1");
            $('#inputTextoBoton').val(portal['BOTON_TEXTO']);
            if (portal['URL_IMAGEN'] != null) $('#logo').attr('src', portal['URL_IMAGEN']);
            if (portal['URL_IMAGEN_FONDO'] != null) $('#imagenFondo').attr('src', portal['URL_IMAGEN_FONDO']);
            $('#inputColor').spectrum('set', portal['COLOR']);
            $('#inputColorSecundario').spectrum('set', portal['COLOR_SECUNDARIO']);
            $('#inputColorTerciario').spectrum('set', portal['COLOR_TERCIARIO']);
            $('#inputTamañoLogo').val(portal['TAMANO_LOGO']);
            $('#valorTamaño').text(portal['TAMANO_LOGO']);
            $('#inputRadioEsquinas').val(portal['RADIO_ESQUINAS']);
            $('#valorRadioEsquinas').text(portal['RADIO_ESQUINAS']);


            //Imagenes
            $('#checkboxImagen').prop('checked', portal['USAR_IMAGEN'] == "1");
            $('#inputOpacidad').val(portal['OPACIDAD_FONDO']);
            $('#valorOpacidad').text(portal['OPACIDAD_FONDO']);
            $('#checkboxEmail').prop('checked', portal['USAR_IMAGEN'] == "1");

            // Registro
            $('#checkboxEmail').prop('checked', portal['REGISTRO_EMAIL'] == "1");
            $('#checkboxNombre').prop('checked', portal['REGISTRO_NOMBRE'] == "1");
            $('#checkboxApellidos').prop('checked', portal['REGISTRO_APELLIDOS'] == "1");

            // Redireccion
            $('#inputRedireccion').val(portal['REDIRECCION']);

            // Limites
            $('#checkboxLimiteTiempo').prop('checked', portal['LIMITE_SESION'] == "1");
            var minutos = portal.LIMITE_SESION_MINUTOS;
            if (minutos > 60) {
                var horas = minutos / 60;
                $('#inputTiempo').val(horas);
                $('#inputUnidadTiempo').val("horas");
            } else {
                $('#inputTiempo').val(portal['LIMITE_SESION_MINUTOS']);
                $('#inputUnidadTiempo').val("minutos");
            }

            // Terminos
            $('#checkboxTerminos').prop('checked', portal['TERMINOS'] == "1");

            if (portal.URL_TERMINOS != null) {
                var nombreArchivo = portal.URL_TERMINOS.substring(portal.URL_TERMINOS.lastIndexOf('/') + 1);
                $('#spanTerminos').text("Archivo seleccionado: " + nombreArchivo);
            }
        }

        function DevolverColorRgba(inputId) {
            var spectrum = $(inputId).spectrum('get');
            var color = 'rgba(' + spectrum._r + ', ' + spectrum._g + ', ' + spectrum._b + ', ' + spectrum._a + ')';
            return color;
        }
    </script>