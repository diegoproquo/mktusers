<div class="container-fluid px-4" style="width: 85%;">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-gray-800">Usuarios Web</h1>
        <div class="container mt-5">
        </div>
    </div>

    <p class="mb-4"> Esta es la ventana para gestionar el acceso de los usuarios a la aplicación web.
    </p>

    <div class="mainDiv">
        <div style="text-align: center;">
            <div id="divTabla" style="width: 100%; display: inline-block; text-align: left;">
                <?php
                bootstrapTablePersonalizadaCheckbox($columns, $data, "datatableUsuariosWeb", "Usuarios Web", "2,5", false, false, false);
                ?>
            </div>
        </div>

        <div class="footer_pagina">

        </div>
    </div>
</div>


<div class="modal fade" id="modalUsuariosWeb" tabindex="-1" role="dialog" aria-labelledby="modalUsuariosWebTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUsuariosWebTitulo">Nuevo usuario web</h5>
                <button id="btnCerrarModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label>Nombre de usuario</label>
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
                        <label for="selectRol" class="form-label">Permisos</label>
                        <select class="form-control" id="selectRol">
                            <option value="0">Usuario</option>
                            <option value="1">Administrador</option>

                        </select>
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
    var idUsuarioWeb = -1;

    $(document).ready(function() {

        // Inicializar los popover
        $('[data-toggle="popover"]').popover({
            trigger: 'hover'
        });


        // ñadir los botones a la datatable
        $('.fixed-table-toolbar').append('<div class="btn-group" id="btnGrupo" role="group">' +
            '<button id="btnNuevoUsuarioWeb" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalUsuariosWeb" style="margin-left:20px"><i class="fas fa-plus"></i> Nuevo</button>' +
            '<button id="btnEliminarUsuarioWeb" disabled class="btn btn-sm btn-danger ms-1" onclick="EliminarUsuarioWeb()"><i class="fas fa-minus"></i> Eliminar</button>' +
            '</div>');

        $('#btnGrupo').after('<button id="btnEditarUsuarioWeb" disabled class="btn btn-sm btn-info ms-5" data-toggle="modal" data-target="#modalUsuariosWeb" onclick="ClicEditarUsuarioWeb()"><i class="fas fa-pen"></i> Editar</button>');

        $('#btnNuevoUsuarioWeb').on('click', function() {
            LimpiarDatosModal();
            $('#modalPerfilesTitulo').text('Nuevo usuario web');
            idUsuarioWeb = -1;
        });

        // Controla cuando se deshabilita el boton de eliminar
        var $table = $('#datatableUsuariosWeb');
        var $btnEliminarUsuarioWeb = $('#btnEliminarUsuarioWeb');
        var $btnEditarUsuarioWeb = $('#btnEditarUsuarioWeb');

        $(function() {
            $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function() {
                var selections = $table.bootstrapTable('getSelections');
                var numSelections = selections.length;
                $btnEliminarUsuarioWeb.prop('disabled', numSelections === 0);
                $btnEditarUsuarioWeb.prop('disabled', numSelections !== 1);
            })

        });

    });

    function GuardarEditar() {
        var datos = {};

        if ($('#inputPassword').val() != $('#inputPasswordConfirmar').val()) {
            alert("Las contraseñas no coinciden");
            return;
        }

        datos['id'] = idUsuarioWeb;
        datos['usuario'] = $('#inputUsuario').val();
        datos['password'] = $('#inputPassword').val();
        datos['rol'] = $('#selectRol').val();


        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/UsuariosWeb/GuardarEditar',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {

                if (response[0] == true) {
                    MostrarAlertCorrecto("Datos guardados correctamente");
                    RecargarTabla('datatableUsuariosWeb', response[1]);
                } else MostrarAlertError("Algo no ha ido según lo esperado");

                DeshabilitarBotones();
                $('#btnCerrarModal').click();
                LimpiarDatosModal();
                idUsuarioWeb = -1;
            },
            error: function(error) {
                console.log("error");
                console.log(error);
                MostrarAlertError("Algo no ha ido según lo esperado");

            }
        });

    }

    function EliminarUsuarioWeb(id) {
        var borrar = prompt('Introduzca "1234" para borrar el usuario web');
        if (borrar != "1234") {
            return;
        } else {

            rows = ObtenerFilasCheckeadas('datatableUsuariosWeb');
            console.log(rows);
            var datos = {
                usuariosweb: rows
            };

            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/UsuariosWeb/EliminarUsuarioWeb',
                dataType: 'json',
                data: JSON.stringify(datos),
                success: function(response) {
                    console.log(response);
                    if (response[0] == true) {
                        MostrarAlertCorrecto("Datos guardados correctamente");
                        RecargarTabla('datatableUsuariosWeb', response[1]);
                    } else MostrarAlertError("Algo no ha ido según lo esperado");
                    DeshabilitarBotones();

                },
                error: function(error) {
                    console.log("error");
                    console.log(error);
                    MostrarAlertError("Algo no ha ido según lo esperado");
                }
            });
        }
    }

    function ClicEditarUsuarioWeb() {
        LimpiarDatosModal();
        usuarioweb = ObtenerFilasCheckeadas('datatableUsuariosWeb');
        idUsuarioWeb = usuarioweb[0]['ID'];
        $('#modalUsuariosWebTitulo').text('Editar usuario web');
        $('#inputUsuario').val(usuarioweb[0]['USUARIO']);
        $('#inputPassword').val(usuarioweb[0]['PASSWORD']);
        $('#inputPasswordConfirmar').val(usuarioweb[0]['PASSWORD']);
        if (usuarioweb[0]['ROL'] == "Usuario") $('#selectRol').val(0);
        else $('#selectRol').val(1);
    }

    function DeshabilitarBotones() {
        $("#btnEliminarUsuarioWeb").prop("disabled", true);
        $("#btnEditarUsuarioWeb").prop("disabled", true);
    }

    function LimpiarDatosModal() {
        $('#inputUsuario').val("");
        $('#inputPassword').val("");
        $('#inputPasswordConfirmar').val("");
        $('#selectRol').val(0);
    }
</script>