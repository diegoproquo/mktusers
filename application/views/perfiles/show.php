<div class="container-fluid px-4" style="width: 85%;">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-gray-800">Perfiles</h1>
        <div class="container mt-5">
        </div>
    </div>

    <p class="mb-4"> Esta es la ventana para gestionar los perfiles de usuario. Un perfil es una serie de reglas que afectarán al comportamiento de los usuarios cuando
                        hagan uso de la red. Es posible ajustar diferentes parámetros como la velocidad de conexión o el número de usuarios simultáneos entre otros.
                        </p>

    <div class="mainDiv">
        <div style="text-align: center;">
            <div id="divTabla" style="width: 100%; display: inline-block; text-align: left;">
                <?php
                bootstrapTablePersonalizadaCheckbox($columns, $data, "datatablePerfiles", "Perfiles", "3,5,10,11,12", false, false, false);
                ?>
            </div>
        </div>

        <div class="footer_pagina">

        </div>
    </div>
</div>


<div class="modal fade" id="modalPerfiles" tabindex="-1" role="dialog" aria-labelledby="modalPerfilesTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPerfilesTitulo">Nuevo perfil</h5>
                <button id="btnCerrarModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label>Nombre</label>
                        <input id="inputNombre" type="text" class="form-control" />
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12">
                        <label>Usuarios simultáneos
                            <a type="button" class="fas fa-info-circle" data-toggle="popover" title="Usuarios simultáneos" data-bs-content="Limita cuántos dispositivos puede haber conectados con un mismo usuario. Valor por defecto: sin límite."></a>
                        </label>
                        <input id="inputSharedUsers" type="number" class="form-control" />
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <label>Rate Upload <a type="button" class="fas fa-info-circle" data-toggle="popover" title="Rate Upload (en Mbps)" data-bs-content="Establece un límite de velocidad para la carga de datos. Valor por defecto: sin límite."></a> </label>
                        <input id="inputRateUpload" type="number" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label>Rate Download <a type="button" class="fas fa-info-circle" data-toggle="popover" title="Rate Download (en Mbps)" data-bs-content="Establece un límite de velocidad para la descarga de datos. Valor por defecto: sin límite."></a> </label>
                        <input id="inputRateDownload" type="number" class="form-control" />
                    </div>
                </div>


                <div class="row mt-2">
                    <div class="col-md-6">
                        <label>Cookie Timeout <a type="button" class="fas fa-info-circle" data-toggle="popover" title="Cookie Timeout (en días)" data-bs-content="La cookie de sesión permite que un usuario se conecte sin tener que iniciar sesión. Es posible establecer un límite de tiempo hasta que la cookie expire. Valor por defecto: 3 días."></a></label>
                        <input class="form-check-input  ml-2" type="checkbox" id="checkboxMacCookie" />
                        <input id="inputCookieTimeout" type="number" class="form-control" min="1" max="30" disabled />

                    </div>
                    <div class="col-md-6">
                        <label>Keepalive timeout <a type="button" class="fas fa-info-circle" data-toggle="popover" title="Keepalive Timeout (en minutos)" data-bs-content="Este valor establece cuánto tiempo permanecerá inactivo un dispositivo antes de que sea desconectado del WiFi. Valor por defecto: 2 minutos."></a></label>
                        <input id="inputKeepaliveTimeout" type="number" class="form-control" min="1" max="24" />
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="GuardarEditar()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<script>
    var idPerfil = -1;

    $(document).ready(function() {

        // Inicializar los popover
        $('[data-toggle="popover"]').popover({
            trigger: 'hover'
        });

        var conexionMKT = <?= json_encode($conexionMKT) ?>;
        if (conexionMKT == false) MostrarAlertErrorMKT();

        // ñadir los botones a la datatable
        $('.fixed-table-toolbar').append('<div class="btn-group" id="btnGrupo" role="group">' +
            '<button id="btnNuevoPerfil" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalPerfiles" style="margin-left:20px"><i class="fas fa-plus"></i> Nuevo</button>' +
            '<button id="btnEliminarPerfil" disabled class="btn btn-sm btn-danger ms-1" onclick="EliminarPerfil()"><i class="fas fa-minus"></i> Eliminar</button>' +
            '</div>');

        $('#btnGrupo').after('<button id="btnEditarPerfil" disabled class="btn btn-sm btn-info ms-5" data-toggle="modal" data-target="#modalPerfiles" onclick="ClicEditarPerfil()"><i class="fas fa-pen"></i> Editar</button>');

        $('#btnNuevoPerfil').on('click', function() {
            LimpiarDatosModal();
            $('#modalPerfilesTitulo').text('Nuevo perfil');
            idPerfil = -1;
        });

        // Controla cuando se deshabilita el boton de eliminar
        var $table = $('#datatablePerfiles')
        var $btnEliminarPerfil = $('#btnEliminarPerfil')
        var $btnEditarPerfil = $('#btnEditarPerfil')
        $(function() {
            $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function() {
                var selections = $table.bootstrapTable('getSelections');
                var numSelections = selections.length;
                $btnEliminarPerfil.prop('disabled', numSelections !== 1); // Habilitar solo si hay exactamente una fila seleccionada
                if (numSelections > 0) $btnEliminarPerfil.prop('disabled', selections[0]['name'] == "default"); // Deshabilita si es el perfil por defecto
                $btnEditarPerfil.prop('disabled', numSelections !== 1);
            })

        });


        // Listener para mostrar o esconder inputs de cookie timeout en función del checkbox
        $('#checkboxMacCookie').change(function() {
            if ($(this).is(':checked')) {
                $('#inputCookieTimeout').prop('disabled', false);
            } else {
                $('#inputCookieTimeout').prop('disabled', true);
            }
        });


        // lIstener para controlar el input de los usuarios simultaneos
        var inputSharedUsers = document.getElementById('inputSharedUsers');
        inputSharedUsers.addEventListener('input', function() {
            var value = parseInt(inputSharedUsers.value);
            if (value < 1) {
                inputSharedUsers.value = 1;
            } else if (value > 5) {
                inputSharedUsers.value = 5;
            }
        });

        // lIstener para controlar el input de rate upload
        var inputRateUpload = document.getElementById('inputRateUpload');
        inputRateUpload.addEventListener('input', function() {
            var value = parseInt(inputRateUpload.value);
            if (value < 1) {
                inputRateUpload.value = 1;
            } else if (value > 300) {
                inputRateUpload.value = 300;
            }
        });

        // lIstener para controlar el input de la rate download
        var inputRateDownload = document.getElementById('inputRateDownload');
        inputRateDownload.addEventListener('input', function() {
            var value = parseInt(inputRateDownload.value);
            if (value < 1) {
                inputRateDownload.value = 1;
            } else if (value > 300) {
                inputRateDownload.value = 300;
            }
        });

        // lIstener para controlar el input de la cookie de sesion
        var inputCookieTimeout = document.getElementById('inputCookieTimeout');
        inputCookieTimeout.addEventListener('input', function() {
            var value = parseInt(inputCookieTimeout.value);
            if (value < 1) {
                inputCookieTimeout.value = 1;
            } else if (value > 365) {
                inputCookieTimeout.value = 365;
            }
        });

        // lIstener para controlar el input de keepalive timeout
        var inputKeepaliveTimeout = document.getElementById('inputKeepaliveTimeout');
        inputKeepaliveTimeout.addEventListener('input', function() {
            var value = parseInt(inputKeepaliveTimeout.value);
            if (value < 1) {
                inputKeepaliveTimeout.value = 1;
            } else if (value > 1440) {
                inputKeepaliveTimeout.value = 1440;
            }
        });

    });

    function GuardarEditar() {
        var datos = {};

        datos['id'] = idPerfil;
        datos['nombre'] = $('#inputNombre').val();

        if ($('#inputRateUpload').val() == "" || $('#inputRateDownload').val() == "") var rate = null;
        else var rate = $('#inputRateUpload').val() + 'M/' + $('#inputRateDownload').val() + 'M';
        datos['rate'] = rate;

        datos['sharedUsers'] = $('#inputSharedUsers').val();

        var cookie = $('#checkboxMacCookie').prop('checked') ? 'true' : 'false';
        datos['macCookie'] = cookie;

        if (cookie == 'false') datos['macCookieTimeout'] = null;
        else {
            if ($('#inputCookieTimeout').val() == "") datos['macCookieTimeout'] = '3d';
            else datos['macCookieTimeout'] = $('#inputCookieTimeout').val() + 'd';
        }

        if ($('#inputKeepaliveTimeout').val() != "") datos['keepaliveTimeout'] = $('#inputKeepaliveTimeout').val() + 'm';
        else datos['keepaliveTimeout'] = "";

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Perfiles/GuardarEditar',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                if (response[0] == true) {
                    RecargarTabla('datatablePerfiles', response[1]);
                    if (response[2] == "") MostrarAlertCorrecto("Datos guardados correctamente");
                    else MostrarAlertError(response[2]);
                } else {
                    $('#modalPerfiles').modal('hide');
                    MostrarAlertErrorMKT();
                }
                DeshabilitarBotones();
                $('#btnCerrarModal').click();
                LimpiarDatosModal();
                idPerfil = -1;
            },
            error: function(error) {
                console.log("error");
                console.log(error);
                MostrarAlertError("Algo no ha ido según lo esperado");

            }
        });

    }

    function EliminarPerfil(id) {
        var borrar = prompt('Introduzca "1234" para borrar el perfil');
        if (borrar != "1234") {
            return;
        } else {

            rows = ObtenerFilasCheckeadas('datatablePerfiles');

            var datos = {
                perfiles: rows
            };

            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/Perfiles/EliminarPerfil',
                dataType: 'json',
                data: JSON.stringify(datos),
                success: function(response) {
                    if (response[0] == true) {
                        RecargarTabla('datatablePerfiles', response[1]);
                        MostrarAlertCorrecto("Perfil eliminado correctamente");
                        DeshabilitarBotones();
                    } else {
                        MostrarAlertErrorMKT();
                    }
                },
                error: function(error) {
                    console.log("error");
                    console.log(error);
                    MostrarAlertError("Algo no ha ido según lo esperado");
                }
            });
        }
    }

    function ClicEditarPerfil() {
        LimpiarDatosModal();

        perfil = ObtenerFilasCheckeadas('datatablePerfiles');
        idPerfil = perfil[0]['.id'];

        $('#modalPerfilesTitulo').text('Editar perfil');
        $('#inputNombre').val(perfil[0]['Nombre']);
        $('#inputNombre').prop('disabled', true); // Se deshabilita porque MKT no permite cambiar el nombre de los perfiles
        $('#inputSharedUsers').val(perfil[0]['Usuarios simultáneos']);

        if (perfil[0]['MAC cookie'] == "false") $('#checkboxMacCookie').prop('checked', false);
        if (perfil[0]['MAC cookie'] != "false") {
            $('#checkboxMacCookie').prop('checked', true);
            var cookieTimeout = perfil[0]['MAC cookie timeout'];
            var cookieTimeoutSinD = cookieTimeout.slice(0, -1);
            $('#inputCookieTimeout').val(cookieTimeoutSinD);
            $('#inputCookieTimeout').prop('disabled', false);
        }

        if (perfil[0]['rate-limit']) {
            var rate = perfil[0]['rate-limit'];
            var numeros = rate.match(/\d+/g);
            var primerNumero = parseInt(numeros[0], 10); // Convertir a número entero
            var segundoNumero = parseInt(numeros[1], 10); // Convertir a número entero
            $('#inputRateUpload').val(primerNumero);
            $('#inputRateDownload').val(segundoNumero);
        }

        var keepalive = perfil[0]['keepalive-timeout'];
        var keepaliveSinH = keepalive.slice(0, -1);
        $('#inputKeepaliveTimeout').val(keepaliveSinH);
    }

    function DeshabilitarBotones() {
        $("#btnEliminarPerfil").prop("disabled", true);
        $("#btnEditarPerfil").prop("disabled", true);
    }

    function LimpiarDatosModal() {
        $('#inputNombre').prop('disabled', false);
        $('#inputNombre').val("");
        $('#inputSharedUsers').val("");
        $('#inputRateUpload').val("");
        $('#inputRateDownload').val("");
        $('#inputCookieTimeout').val("");
        $('#inputKeepaliveTimeout').val("");
        $('#checkboxMacCookie').prop('checked', false);
        $('#inputCookieTimeout').prop('disabled', true);
    }

    function MostrarInformacion(id) {
        var infoElement = $('#' + id + 'Text');

        if (infoElement.hasClass('informacion-oculta')) {
            infoElement.removeClass('informacion-oculta');
            infoElement.addClass('informacion-visible');
        } else {
            infoElement.removeClass('informacion-visible');
            infoElement.addClass('informacion-oculta');
        }
    }
</script>