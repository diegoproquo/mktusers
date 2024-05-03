<div class="container-fluid px-4" style="width: 85%;">
    <h1 class="mt-4">Sites</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">Proquo MKT</li>
        <li class="breadcrumb-item active">Sites</li>
    </ol>
    <div class="mainDiv">
        <div class="content_pagina" style="text-align: center;">
            <div id="divTabla" style="width:100%; display: inline-block; text-align: left;">
                <div class="card-body">
                    <?php
                    bootstrapTablePersonalizada($columns, $data, "datatableSites", "Sites", "0,3", false, false, false);
                    ?>
                </div>
            </div>
        </div>

        <div class="footer_pagina">
            <div style="padding:40px;">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSites" tabindex="-1" role="dialog" aria-labelledby="modalSitesTitulo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSitesTitulo">Añadir site</h5>
                <button type="button" id="btnCerrarModal" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <select class="sites select2-container--modal" id="selectSites" style="width: 50%;">
                    <?php

                    foreach ($sites as $site) {
                    ?>
                        <option value="<?= $site->{'name'} ?>"> <?= $site->{'desc'} ?> </option>
                    <?php
                    }
                    ?>

                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="GuardarSite()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalSitesEditar" tabindex="-1" role="dialog" aria-labelledby="modalSitesEditar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSitesTituloEditar">Editar site</h5>
                <button type="button" id="btnCerrarModalEditar" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label for="inputNombreSite" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="inputNombreSite">
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="ActualizarNombre()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var idSite = -1;
    $(document).ready(function() {

        $('.search-input').after('<button id="btnNuevoSite" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalSites" style="margin-left:20px"><i class="fas fa-plus"></i> Añadir site</button>');

        $('#modalSites').on('shown.bs.modal', function() {
            $('.sites').select2({
                placeholder: 'Seleccione un site',
                dropdownParent: $('#modalSites .modal-body'),
            });
        });

    });



    function GuardarSite() {
        var datos = {};

        datos['siteDesc'] = $('#selectSites').val();

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Sites/GuardarEditar',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                if (response == false) alert("El site seleccionado ya está añadido, seleccione otro")
                else {
                    RecargarTabla('datatableSites', response[1]);
                    $('#btnCerrarModal').click();
                    MostrarAlertCorrecto("Site añadido correctamente");
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(error) {
                console.log("error");
                console.log(error);
                MostrarAlertError("Algo no ha ido según lo esperado");
            }
        });
    }

    function ClicEditarSite(ID) {
        var $table = $('#datatableSites')
        var datos = $table.bootstrapTable('getRowByUniqueId', ID);
        $('#inputNombreSite').val(datos['NOMBRE']);
        idSite = datos['ID'];
    }

    function ActualizarNombre(id) {
        var datos = {};

        datos['siteDesc'] = $('#inputNombreSite').val();
        datos['ID'] = idSite;

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Sites/ActualizarNombre',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                RecargarTabla('datatableSites', response[1]);
                MostrarAlertCorrecto("Nombre actualizado correctamente");
                $('#btnCerrarModalEditar').click();
            },
            error: function(error) {
                console.log("error");
                console.log(error);
                MostrarAlertError("Algo no ha ido según lo esperado");
            }
        });

    }

    function ClicEliminarSite(id) {
        alert("De momento esta opcion esta desactivada por seguridad");
        return;

        var borrar = prompt("Introduzca 1234 para borrar el usuario")
        if (borrar != "1234") {
            return;
        } else {

            idSite = id;
            var datos = {};

            datos['id'] = idSite;

            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/Usuarios/EliminarUsuario',
                dataType: 'json',
                data: {
                    datos: datos
                },
                success: function(response) {
                    RecargarTabla('datatableSites', response[1]);
                    MostrarAlertCorrecto("Site eliminado correctamente");

                },
                error: function(error) {
                    console.log("error");
                    console.log(error);
                    MostrarAlertError("Algo no ha ido según lo esperado");

                }
            });
        }
    }

    function LimpiarDatosModal() {
        $('#inputNombre').val("");
        $('#inputUsuario').val("");
        $('#inputPassword').val("");
        $('#inputPasswordConfirmar').val("");
    }
</script>