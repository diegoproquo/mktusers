<div class="container-fluid px-4" style="width: 85%;">
    <h1 class="mt-4">Usuarios <?=  $site_nombre ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">Unifi Manager</li>
        <li class="breadcrumb-item active">Usuarios </li>
    </ol>
    <div class="mainDiv">
        <div class="content_pagina" style="text-align: center;">
            <div id="divTabla" style="width: 100%; display: inline-block; text-align: left;">
                <?php
                bootstrapTablePersonalizada($columns, $data, "datatableUsuarios", "Usuarios  $site_nombre", "0,3,6", false, false, false);
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
                        <label>Nombre</label>
                        <input id="inputNombre" type="text" class="form-control" />
                    </div>
                </div>
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
                        <label>Rol</label>
                        <select class="form-control" id="selectRol">
                            <option value="0" selected="selected">Usuario</option>
                            <option value="1">Administrador</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="GuardarEditarUsuario()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<script>
    var idUsuario = -1;
    var site_id;
    $(document).ready(function() {

        $('.search-input').after('<button id="btnNuevoUsuario" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalUsuarios" style="margin-left:20px"><i class="fas fa-plus"></i> Nuevo usuario</button>');

        $('#btnNuevoUsuario').on('click', function() {
            LimpiarDatosModal();
            $('#modalUsuariosTitulo').text('Nuevo usuario');
            idUsuario = -1;
        });

        site_id = '<?php echo addslashes($site_id); ?>';

    });



    function GuardarEditarUsuario() {
        var datos = {};

        if ($('#inputPassword').val() != $('#inputPasswordConfirmar').val()) {
            alert("Las contraseñas no coinciden");
            return;
        }

        datos['id'] = idUsuario;
        datos['nombre'] = $('#inputNombre').val();
        datos['usuario'] = $('#inputUsuario').val();
        datos['password'] = $('#inputPassword').val();
        datos['rol'] = $('#selectRol').val();
        datos['site_id'] = site_id;

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Usuarios/GuardarEditar',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                RecargarTabla('datatableUsuarios', response[1]);
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

    function ClicEliminarUsuario(id) {
        var borrar = prompt("Introduzca 1234 para borrar el usuario")
        if (borrar != "1234") {
            return;
        } else {

            idUsuario = id;
            var datos = {};

            datos['id'] = idUsuario;
            datos['site_id'] = site_id;

            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/Usuarios/EliminarUsuario',
                dataType: 'json',
                data: {
                    datos: datos
                },
                success: function(response) {
                    RecargarTabla('datatableUsuarios', response[1]);
                    MostrarAlertCorrecto("Usuario eliminado correctamente");
                },
                error: function(error) {
                    console.log("error");
                    console.log(error);
                    MostrarAlertError("Algo no ha ido según lo esperado");

                }
            });
        }
    }

    function ClicEditarUsuario(id) {
        var datos = {};
        $('#modalUsuariosTitulo').text("Editar usuario");

        idUsuario = id;
        datos['id'] = idUsuario;

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Usuarios/getUsuario',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                $('#inputNombre').val(response['NOMBRE']);
                $('#inputUsuario').val(response['USUARIO']);
                $('#inputPassword').val(response['PASSWORD']);
                $('#inputPasswordConfirmar').val(response['PASSWORD']);
                $('#selectRol').val(response['ROL']).trigger('change');
            }
        });

    }

    function LimpiarDatosModal() {
        $('#inputNombre').val("");
        $('#inputUsuario').val("");
        $('#inputPassword').val("");
        $('#inputPasswordConfirmar').val("");
        $('#selectRol').val(0);
    }

</script>