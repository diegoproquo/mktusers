<div class="container-fluid px-4" style="width: 85%;">
    <h1 class="mt-4">Perfiles</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">Proquo MKT</li>
        <li class="breadcrumb-item active">Perfiles </li>
    </ol>
    <div class="mainDiv">
        <div style="text-align: center;">
            <div id="divTabla" style="width: 100%; display: inline-block; text-align: left;">
                <?php
               //bootstrapTablePersonalizada($columns, $data, "datatablePerfiles", "Perfiles", "0", false, false, false, true);
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
                <button type="button" id="btnCerrarModal" class="close" data-dismiss="modal" aria-label="Close">
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
                        <label for="checkboxRateLimit" class="form-label">Rate limit</label>
                        <input class="form-check-input  ml-2" type="checkbox" id="checkboxRateLimit" />
                    </div>
                </div>

                <div class="row mt-2" id="rowRateLimit" style="display:none">
                    <div class="col-md-6">
                        <label>Upload</label>
                        <input id="inputRateUpload" type="number" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label>Download </label>
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
                <div class="col-md-12">
                    <label>Mac-cookie timeout </label>
                    <input id="inputMacCookieTimeout" type="number" class="form-control" />
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="GuardarEditarPerfil()">Guardar</button>
        </div>
    </div>
</div>
</div>


<script>
    var idPerfil = -1;
    var site_id;
    $(document).ready(function() {

        $('.search-input').after('<button id="btnNuevoPerfil" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalPerfiles" style="margin-left:20px"><i class="fas fa-plus"></i> Nuevo perfil</button>');

        $('#btnNuevoPerfil').on('click', function() {
            LimpiarDatosModal();
            $('#modalPerfilesTitulo').text('Nuevo perfil');
            idPerfil = -1;
        });


        $('#inputMacCookie').change(function() {
            if ($(this).is(':checked')) {
                $('#rowCookieTimeout').show();
            } else {
                $('#rowCookieTimeout').hide();
            }
        });

        $('#checkboxRateLimit').change(function() {
            if ($(this).is(':checked')) {
                $('#rowRateLimit').show();
            } else {
                $('#rowRateLimit').hide();
            }
        });

    });



    function GuardarEditarPerfil() {
        var datos = {};

        datos['id'] = idPerfil;
        datos['nombre'] = $('#inputNombre').val();
        datos['rateUpload'] = $('#inputRateUpload').val();
        datos['rateDownload'] = $('#inputRateDownload').val();
        datos['macCookie'] = $('#checkboxMacCookie').prop('checked');
        datos['cookieTimeout'] = $('#inputMacCookieTimeout').val();

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Perfiles/GuardarEditar',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                RecargarTabla('datatablePerfiles', response[1]);
                $('#btnCerrarModal').click();
                MostrarAlertCorrecto("Datos guardados correctamente");
            },
            error: function(error) {
                console.log("error");
                console.log(error);
                MostrarAlertError("Algo no ha ido según lo esperado");

            }
        });

    }

    function ClicEliminarPerfil(id) {
        var borrar = prompt("Introduzca 1234 para borrar el perfil")
        if (borrar != "1234") {
            return;
        } else {

            idPerfil = id;
            var datos = {};

            datos['id'] = idPerfil;
            datos['site_id'] = site_id;

            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/Perfiles/EliminarPerfil',
                dataType: 'json',
                data: {
                    datos: datos
                },
                success: function(response) {
                    RecargarTabla('datatablePerfiles', response[1]);
                    MostrarAlertCorrecto("Perfil eliminado correctamente");
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

    function LimpiarDatosModal() {
        $('#inputNombre').val("");
        $('#inputPerfil').val("");
        $('#inputPassword').val("");
        $('#inputPasswordConfirmar').val("");
        $('#selectRol').val(0);
    }
</script>