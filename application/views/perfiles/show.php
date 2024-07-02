<div class="container-fluid px-4" style="width: 85%;">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Perfiles</h1>
    </div>
    <div class="mainDiv">
        <div style="text-align: center;">
            <div id="divTabla" style="width: 100%; display: inline-block; text-align: left;">
                <?php
                bootstrapTablePersonalizadaCheckbox($columns, $data, "datatablePerfiles", "Perfiles", "", false, false, false);
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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
                        <label>Usuarios simultáneos</label>
                        <input id="inputSharedUsers" type="number" class="form-control" min="1" max="5" />
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="checkboxRateLimit" class="form-label">Rate limit</label>
                        <input class="form-check-input  ml-2" type="checkbox" id="checkboxRateLimit" />
                    </div>
                </div>

                <div class="row mt-2" id="rowRateLimit" style="display:none">
                    <div class="col-md-6">
                        <label>Upload (en Mbps)</label>
                        <input id="inputRateUpload" type="number" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label>Download (en Mbps)</label>
                        <input id="inputRateDownload" type="number" class="form-control" />
                    </div>
                </div>


                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="checkboxMacCookie" class="form-label">Mac cookie</label>
                        <input class="form-check-input  ml-2" type="checkbox" id="checkboxMacCookie" />
                    </div>
                </div>

                <div class="row mt-2" id="rowCookieTimeout" style="display:none">
                    <div class="col-md-6">
                        <label>Cookie timeout (en días)</label>
                        <input id="inputCookieTimeout" type="number" class="form-control" min="1" max="30" />
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="NuevoPerfil()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<script>
    var idPerfil = -1;

    $(document).ready(function() {

        var conexionMKT = <?= json_encode($conexionMKT) ?>;
        if (conexionMKT == false) MostrarAlertErrorMKT();

        $('.fixed-table-toolbar').append('<div class="btn-group" role="group">' +
            '<button id="btnNuevoPerfil" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalPerfiles" style="margin-left:20px"><i class="fas fa-plus"></i> Nuevo perfil</button>' +
            '<button id="btnEliminarPerfil" disabled class="btn btn-sm btn-danger ms-1" onclick="EliminarPerfil()"><i class="fas fa-minus"></i> Eliminar</button>' +
            '</div>');

        $('#btnNuevoPerfil').on('click', function() {
            LimpiarDatosModal();
            $('#modalPerfilesTitulo').text('Nuevo perfil');
            idPerfil = -1;
        });


        // Listener para mostrar o esconder inputs de rate limit en funcion del checkbox
        $('#checkboxRateLimit').change(function() {
            if ($(this).is(':checked')) {
                $('#rowRateLimit').show();
            } else {
                $('#rowRateLimit').hide();
            }
        });

        var $table = $('#datatablePerfiles')
        var $btnEliminarPerfil = $('#btnEliminarPerfil')
        $(function() {
            $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function() {
                $btnEliminarPerfil.prop('disabled', !$table.bootstrapTable('getSelections').length)
            })
        })

        // lIstener para controlar el input de los usuarios simultaneos
        var inputSharedUsers = document.getElementById('inputSharedUsers');
        inputSharedUsers.addEventListener('input', function() {
            // Obtiene el valor actual del input como un número
            var value = parseInt(inputSharedUsers.value);

            // Verifica si el valor está fuera del rango permitido
            if (value < 1) {
                // Si es menor que 1, establece el valor en 1
                inputSharedUsers.value = 1;
            } else if (value > 5) {
                // Si es mayor que 5, establece el valor en 5
                inputSharedUsers.value = 5;
            }
        });

        // Listener para mostrar o esconder inputs de cookie timeout en funcion del checkbox
        $('#checkboxMacCookie').change(function() {
            if ($(this).is(':checked')) {
                $('#rowCookieTimeout').show();
            } else {
                $('#rowCookieTimeout').hide();
            }
        });

        // lIstener para controlar el input de la cookie de sesion
        var inputCookieTimeout = document.getElementById('inputCookieTimeout');
        inputCookieTimeout.addEventListener('input', function() {
            // Obtiene el valor actual del input como un número
            var value = parseInt(inputCookieTimeout.value);

            // Verifica si el valor está fuera del rango permitido
            if (value < 1) {
                // Si es menor que 1, establece el valor en 1
                inputCookieTimeout.value = 1;
            } else if (value > 5) {
                // Si es mayor que 5, establece el valor en 5
                inputCookieTimeout.value = 30;
            }
        });
    });



    function NuevoPerfil() {
        var datos = {};

        if ($('#checkboxRateLimit').is(':checked')) {
            if ($('#inputRateUpload').val() == "" || $('#inputRateDownload').val() == "") var rate = null;
            else var rate = $('#inputRateUpload').val() + 'M/' + $('#inputRateDownload').val() + 'M';
        } else {
            var rate = null;
        }

        datos['nombre'] = $('#inputNombre').val();
        datos['sharedUsers'] = $('#inputSharedUsers').val();
        datos['rate'] = rate;

        var cookie = $('#checkboxMacCookie').prop('checked') ? 'true' : 'false';
        datos['macCookie'] = cookie;

        if (cookie == 'false') datos['macCookieTimeout'] = null;
        else {
            if ($('#inputCookieTimeout').val() == "") datos['macCookieTimeout'] = '3d';
            else datos['macCookieTimeout'] = $('#inputCookieTimeout').val() + 'd';
        }


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
                    $('#modalPerfiles').modal('hide');
                    MostrarAlertCorrecto("Datos guardados correctamente");
                    LimpiarDatosModal();
                } else {
                    $('#modalPerfiles').modal('hide');
                    LimpiarDatosModal();
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

    function EliminarPerfil(id) {
        var borrar = prompt("Introduzca 1234 para borrar el perfil")
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

    function ClicEditarPerfil(id) {
        var datos = {};
        $('#modalPerfilesTitulo').text("Editar perfil");

        idPerfil = id;
        datos['id'] = idPerfil;

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Perfiles/getPerfil',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                $('#inputNombre').val(response['NOMBRE']);
                $('#inputPerfil').val(response['USUARIO']);
                $('#inputPassword').val(response['PASSWORD']);
                $('#inputPasswordConfirmar').val(response['PASSWORD']);
                $('#selectRol').val(response['ROL']).trigger('change');
            }
        });

    }

    function DeshabilitarBotones() {
        $("#btnEliminarPerfil").prop("disabled", true);
    }

    function LimpiarDatosModal() {
        $('#inputNombre').val("");
        $('#inputPerfil').val("");
        $('#inputPassword').val("");
        $('#inputPasswordConfirmar').val("");
        $('#selectRol').val(0);
    }
</script>