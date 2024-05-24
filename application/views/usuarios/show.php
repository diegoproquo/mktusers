<div class="container-fluid px-4" style="width: 85%;">
    <h1 class="mt-4">Usuarios</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">Proquo MKT</li>
        <li class="breadcrumb-item active">Usuarios </li>
    </ol>
    <div class="mainDiv">
        <div style="text-align: center;">
            <div id="divTabla" style="width: 100%; display: inline-block; text-align: left;">
                <?php
                bootstrapTablePersonalizadaCheckbox($columns, $data, "datatableUsuarios", "Usuarios", "", false, false, false);
                ?>
            </div>
        </div>

        <div class="footer_pagina">

        </div>
    </div>
</div>


<div class="modal fade" id="modalUsuarios" tabindex="-1" role="dialog" aria-labelledby="modalUsuariosTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUsuariosTitulo">Nuevo usuario</h5>
                <button type="button" id="btnCerrarModal" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label>Usuario</label>
                        <input id="inputUsuario" type="text" class="form-control" />
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label>Contraseña</label>
                        <input id="inputPassword" type="password" class="form-control" />
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label>Confirmar contraseña</label>
                        <input id="inputPasswordConfirmar" type="password" class="form-control" />
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="selectPerfiles" class="form-label">Perfiles</label>
                        <select class="form-control" id="selectPerfiles">
                            <?php

                            foreach ($perfiles as $perfil) {
                            ?>
                                <option value="<?= $perfil['name'] ?>"> <?= $perfil['name']  ?> </option>
                            <?php
                            }
                            ?>

                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label>Comentario</label>
                        <input id="inpuComentario" type="text" class="form-control" />
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="NuevoUsuario()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<script>
    var idUsuario = -1;
    var site_id;

    $(document).ready(function() {

        // Añadir botones a la toolbar
        $('.search-input').after('<button id="btnNuevoUsuario" class="btn btn-sm btn-success ms-1" data-toggle="modal" data-target="#modalUsuarios"><i class="fas fa-plus"></i> Nuevo</button> ' +
            '<button id="btnEliminarUsuarios" disabled onclick="EliminarUsuarios()" class="btn btn-sm btn-danger ms-1"><i class="fas fa-minus"></i> Eliminar</button> ' +
            '<button id="btnHabilitarUsuario" disabled onclick="HabilitarUsuarios()" class="btn btn-sm btn-primary ms-1"><i class="fas fa-check"></i> Habilitar</button>' +
            '<button id="btnDeshabilitarUsuario" disabled onclick="DeshabilitarUsuarios()" class="btn btn-sm btn-warning ms-1"><i class="fas fa-xmark"></i> Deshabilitar</button>');

        // Control de la modal
        $('#btnNuevoUsuario').on('click', function() {
            LimpiarDatosModal();
            $('#modalUsuariosTitulo').text('Nuevo usuario');
            idUsuario = -1;
        });

        // Deshabilitar botones si no hay ninguna fila seleccionada
        var $table = $('#datatableUsuarios')
        var $btnEliminarUsuarios = $('#btnEliminarUsuarios')
        var $btnHabilitarUsuario = $('#btnHabilitarUsuario')
        var $btnDeshabilitarUsuario = $('#btnDeshabilitarUsuario')
        $(function() {
            $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function() {
                $btnEliminarUsuarios.prop('disabled', !$table.bootstrapTable('getSelections').length)
                $btnHabilitarUsuario.prop('disabled', !$table.bootstrapTable('getSelections').length)
                $btnDeshabilitarUsuario.prop('disabled', !$table.bootstrapTable('getSelections').length)
            })
            $btnEliminarUsuarios.click(function() {
                var ids = $.map($table.bootstrapTable('getSelections'), function(row) {
                    return row.id
                })

                $table.bootstrapTable('btnEliminarUsuarios', {
                    field: 'id',
                    values: ids
                })
                $btnEliminarUsuarios.prop('disabled', true)
            });
            $btnHabilitarUsuario.click(function() {
                var ids = $.map($table.bootstrapTable('getSelections'), function(row) {
                    return row.id
                })

                $table.bootstrapTable('btnHabilitarUsuario', {
                    field: 'id',
                    values: ids
                })
                $btnHabilitarUsuario.prop('disabled', true)
            });
            $btnDeshabilitarUsuario.click(function() {
                var ids = $.map($table.bootstrapTable('getSelections'), function(row) {
                    return row.id
                })

                $table.bootstrapTable('btnDeshabilitarUsuario', {
                    field: 'id',
                    values: ids
                })
                $btnDeshabilitarUsuario.prop('disabled', true)
            })
        })

    });



    function NuevoUsuario() {
        var datos = {};

        if ($('#inputPassword').val() != $('#inputPasswordConfirmar').val()) {
            alert("Las contraseñas no coinciden");
            return;
        }

        datos['id'] = idUsuario;
        datos['usuario'] = $('#inputUsuario').val();
        datos['password'] = $('#inputPassword').val();
        datos['perfil'] = $('#selectPerfiles').val();
        datos['comentario'] = $('#inpuComentario').val();

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Usuarios/NuevoUsuario',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                RecargarTabla('datatableUsuarios', response[1]);
                MostrarAlertCorrecto("Uusario añadido correctamente");
                $('#btnCerrarModal').click();
                LimpiarDatosModal();
            },
            error: function(error) {
                console.log("error");
                console.log(error);
                MostrarAlertError("Algo no ha ido según lo esperado");
            }
        });
    }

    function EliminarUsuarios() {

        rows = ObtenerFilasCheckeadas();

        var datos = {};
        datos['usuarios'] = rows;

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Usuarios/EliminarUsuarios',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                RecargarTabla('datatableUsuarios', response[1]);
                MostrarAlertCorrecto("Usuario eliminado correctamente");
                DeshabilitarBotones();
            },
            error: function(error) {
                console.log("error");
                console.log(error);
                MostrarAlertError("Algo no ha ido según lo esperado");
                DeshabilitarBotones();
            }
        });
    }

    function HabilitarUsuarios() {

        rows = ObtenerFilasCheckeadas();

        var datos = {};
        datos['usuarios'] = rows;

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Usuarios/HabilitarUsuarios',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                RecargarTabla('datatableUsuarios', response[1]);
                MostrarAlertCorrecto("Usuario habilitado correctamente");
                DeshabilitarBotones();
            },
            error: function(error) {
                console.log("error");
                console.log(error);
                MostrarAlertError("Algo no ha ido según lo esperado");
                DeshabilitarBotones();

            }
        });
    }

    function DeshabilitarUsuarios() {

        rows = ObtenerFilasCheckeadas();

        var datos = {};
        datos['usuarios'] = rows;

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Usuarios/DeshabilitarUsuarios',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                RecargarTabla('datatableUsuarios', response[1]);
                MostrarAlertCorrecto("Usuario deshabilitado correctamente");
                DeshabilitarBotones();
            },
            error: function(error) {
                console.log("error");
                console.log(error);
                MostrarAlertError("Algo no ha ido según lo esperado");
                DeshabilitarBotones();

            }
        });
    }

    function ObtenerFilasCheckeadas() {
        var checkedRows = $('#datatableUsuarios').bootstrapTable('getSelections');
        var rowDetailsArray = checkedRows.map(function(row) {
            return row;
        });
        return rowDetailsArray;
    }

    function DeshabilitarBotones(){
        $("#btnEliminarUsuarios").prop("disabled",true);
        $("#btnHabilitarUsuario").prop("disabled",true);
        $("#btnDeshabilitarUsuario").prop("disabled",true);
    }

    function LimpiarDatosModal() {
        $('#inputUsuario').val("");
        $('#inputPassword').val("");
        $('#inputPasswordConfirmar').val("");
        $('#inpuComentario').val("");

    }
</script>