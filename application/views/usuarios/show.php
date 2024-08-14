<div class="container-fluid px-4" style="width: 85%;">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Usuarios</h1>
        <div class="btn-group">
            <button id="downloadButton" class="btn btn-sm btn-info" onclick="DescargarPlantilla()"><i class="fas fa-download"></i> Plantilla CSV</button>
            <button id="uploadButton" class="btn btn-sm btn-primary ms-1"><i class="fas fa-file-import"></i> Importar CSV</button>
        </div>

    </div>

    <div class="mainDiv">
        <div style="text-align: center;">
            <div id="divTabla" style="width: 100%; display: inline-block; text-align: left;">
                <?php
                bootstrapTablePersonalizadaCheckbox($columns, $data, "datatableUsuarios", "Usuarios", "1,8", false, true, true);
                ?>
            </div>
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
                        <label for="selectPerfiles" class="form-label">Perfiles</label>
                        <select class="form-control" id="selectPerfiles">
                            <?php

                            foreach ($perfiles as $perfil) {
                                if ($perfil['name'] == "default") {
                            ?>
                                    <option default value="<?= $perfil['name'] ?>"> <?= $perfil['name']  ?> </option>
                                <?php
                                } else {
                                ?>
                                    <option value="<?= $perfil['name'] ?>"> <?= $perfil['name']  ?> </option>
                            <?php
                                }
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
                <button type="button" class="btn btn-primary" onclick="GuardarEditarUsuario()">Guardar</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modalImportar" tabindex="-1" role="dialog" aria-labelledby="modalImportarTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImportarTitulo">Importación de usuarios</h5>
                <button type="button" id="btnCerrarModal" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Relacione los campos del archivo CSV con cada uno de los campos de los usuarios del Mikrotik. Asegurese de que el archivo CSV ha sido guardado en el formato correcto: <strong>CSV UTF-8 (delimitado por comas)</strong></p>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="selectImportUser" class="form-label">Nombre de usuario</label>
                        <select class="form-control select-header" id="selectImportUser">

                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="selectImportPassword" class="form-label">Contraseña</label>
                        <select class="form-control select-header" id="selectImportPassword">

                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="selectImportComment" class="form-label">Comentario</label>
                        <select class="form-control select-header" id="selectImportComment">

                        </select>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="selectImportPerfiles" class="form-label">Perfil</label>
                        <select class="form-control" id="selectImportPerfiles">
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

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="btnConfirmarImportar" type="button" class="btn btn-primary">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<form id="importarUsuariosForm" style="display:none">
    <label for="file">Selecciona el archivo CSV:</label>
    <input type="file" name="file" id="file" accept=".csv">
    <input id="btnImportarUsuariosFormSubmit" type="submit" value="Subir">
</form>

<script>
    var idUsuario = -1;
    var lines = [];
    var headers;

    $(document).ready(function() {

        var conexionMKT = <?= json_encode($conexionMKT) ?>;
        if (conexionMKT == false) MostrarAlertErrorMKT();

        // Añadir botones a la toolbar
        $('.fixed-table-toolbar').append('<div class="btn-group" id="btnGrupo" role="group">' +
            '<button id="btnNuevoUsuario" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalUsuarios"><i class="fas fa-plus"></i> Nuevo</button>' +
            '<button id="btnEliminarUsuarios" disabled class="btn btn-sm btn-danger ms-1" onclick="EliminarUsuarios()"><i class="fas fa-minus"></i> Eliminar</button>' +
            '<button id="btnHabilitarUsuario" disabled class="btn btn-sm btn-success ms-1" onclick="HabilitarUsuarios()"><i class="fas fa-check"></i> Habilitar</button>' +
            '<button id="btnDeshabilitarUsuario" disabled class="btn btn-sm btn-warning ms-1" onclick="DeshabilitarUsuarios()"><i class="fas fa-xmark"></i> Deshabilitar</button>' +
            '</div>');

        $('#btnGrupo').after('<button id="btnEditarUsuario" disabled class="btn btn-sm btn-info ms-5" data-toggle="modal" data-target="#modalUsuarios" onclick="ClicEditarUusario()"><i class="fas fa-pen"></i> Editar</button>');

        // Listener para limpiar modal siempre que se cierra
        $('#modalUsuarios').on('hidden.bs.modal', function(e) {
            LimpiarDatosModal();
        });

        $('#uploadButton').on('click', function() {
            $('#file').click();
        });

        // Control de la modal
        $('#btnNuevoUsuario').on('click', function() {
            LimpiarDatosModal();
            $('#modalUsuariosTitulo').text('Nuevo usuario');
            idUsuario = -1;
        });

        // Deshabilitar botones si no hay ninguna fila seleccionada
        var $table = $('#datatableUsuarios');
        var $btnEliminarUsuarios = $('#btnEliminarUsuarios');
        var $btnHabilitarUsuario = $('#btnHabilitarUsuario');
        var $btnDeshabilitarUsuario = $('#btnDeshabilitarUsuario');
        var $btnEditarUsuario = $('#btnEditarUsuario');
        $(function() {
            $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function() {
                var selections = $table.bootstrapTable('getSelections');
                var numSelections = selections.length;
                $btnEliminarUsuarios.prop('disabled', numSelections === 0);
                $btnHabilitarUsuario.prop('disabled', numSelections === 0);
                $btnDeshabilitarUsuario.prop('disabled', numSelections === 0);
                $btnEditarUsuario.prop('disabled', numSelections !== 1);

            });
        });


        //////////////////////////////////////////////// *SECTION IMPORTAR USUARIOS
        //Combinacion de metodos para importar usuarios. 

        // Listener para el form del csv
        $('#file').on('change', function(e) {
            e.preventDefault();
            var fileInput = $('#file')[0];
            var file = fileInput.files[0];

            // Verificar si se seleccionó un archivo
            if (!file) {
                MostrarAlertError("Por favor, selecciona un archivo CSV.");
                return;
            }

            // Verificar la extensión del archivo
            var fileName = file.name;
            var extension = fileName.split('.').pop().toLowerCase();
            if (extension !== 'csv') {
                MostrarAlertError("Por favor, selecciona un archivo CSV.");
                return;
            }

            var reader = new FileReader();

            reader.onload = function(e) {
                var csvData = e.target.result;
                procesarCSV(csvData);
            };

            reader.readAsText(file, 'UTF-8');
        });

        $('.select-header').on('change', function() {
            updateSelectOptions();
        });



    });

    //Procesa el csv y guarda los datos en las variables globales para usar despues
    function procesarCSV(data) {
        var allTextLines = data.split(/\r\n|\n/);
        headers = allTextLines[0].split(';'); // Use ';' as the delimiter
        lines = [];

        for (var i = 1; i < allTextLines.length; i++) {
            var data = allTextLines[i].split(';'); // Use ';' as the delimiter
            if (data.length == headers.length) {
                var tarr = {};
                for (var j = 0; j < headers.length; j++) {
                    tarr[headers[j]] = data[j];
                }
                lines.push(tarr);
            }
        }
        fillSelectOptions();
        $('#modalImportar').modal('show');
    }

    // Verificar si todos los selects tienen opciones seleccionadas
    $('#btnConfirmarImportar').on('click', function() {

        var columnaUsuario = $('#selectImportUser').val();
        var columnaPassword = $('#selectImportPassword').val();
        var columnaComment = $('#selectImportComment').val();

        if (columnaUsuario && columnaPassword && columnaComment) {
            ImportarUsuarios(); // Llamar a la función si todos tienen opciones seleccionadas
        } else {
            alert('Por favor, selecciona una opción en todos los campos.');
        }
    });


    // Relena los datos de los select de las modales y los selecciona en caso de que el nombre de la columna (header) coincida
    function fillSelectOptions() {
        $('.select-header').each(function() {
            var select = $(this);
            select.empty();
            select.append('<option value="">Seleccione una opción</option>');

            headers.forEach(function(header) {
                select.append('<option value="' + header + '">' + header + '</option>');
            });

            headers.forEach(function(header) {
                if (isEqualIgnoreCase(header, 'Usuario') || isEqualIgnoreCase(header, 'User')) {
                    $('#selectImportUser').val(header);
                } else if (isEqualIgnoreCase(header, 'Contraseña') || isEqualIgnoreCase(header, 'Password') || isEqualIgnoreCase(header, 'contrasena')) {
                    $('#selectImportPassword').val(header);
                } else if (isEqualIgnoreCase(header, 'Comentario') || isEqualIgnoreCase(header, 'Comment')) {
                    $('#selectImportComment').val(header);
                }
                // Agrega más comparaciones aquí si es necesario
            });

        });
        updateSelectOptions();
    }

    function isEqualIgnoreCase(str1, str2) {
        return str1.toLowerCase() === str2.toLowerCase();
    }

    // Controla que no se pueda selccionar las mismas opciones en 2 select distintos
    function updateSelectOptions() {
        var selectedValues = [];
        $('.select-header').each(function() {
            var value = $(this).val();
            if (value) {
                selectedValues.push(value);
            }
        });

        $('.select-header').each(function() {
            var select = $(this);
            select.find('option').each(function() {
                var option = $(this);
                if (selectedValues.includes(option.val()) && option.val() !== select.val()) {
                    option.prop('disabled', true);
                } else {
                    option.prop('disabled', false);
                }
            });
        });
    }

    // Llamada ajax para procesar el csv e importar los usuarios
    function ImportarUsuarios() {
        var columnaUsuario = $('#selectImportUser').val();
        var columnaPassword = $('#selectImportPassword').val();
        var columnaComment = $('#selectImportComment').val();
        var perfil = $('#selectImportPerfiles').val();

        $.ajax({
            type: "POST",
            url: '<?= base_url() ?>/Usuarios/procesarCSV',
            data: JSON.stringify({
                csvData: lines,
                columnaUsuario: columnaUsuario,
                columnaPassword: columnaPassword,
                columnaComment: columnaComment,
                perfil: perfil

            }),
            contentType: "application/json",
            success: function(response) {

                // Parseamos a json, no se envia correctaemnte el csv con datatype:"json"

                var jsonResponse = JSON.parse(response);

                if (jsonResponse[0] == true) {
                    RecargarTabla('datatableUsuarios', jsonResponse[1]);

                    $('#modalImportar').modal('hide');
                    LimpiarDatosModalImportar();

                    if (jsonResponse[2] == "") MostrarAlertCorrecto("Usuarios importados correctamente");
                    else MostrarAlertError(jsonResponse[2]);

                } else {
                    $('#modalImportar').modal('hide');
                    LimpiarDatosModalImportar();
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

    ////////////////////////// *SECTION FIN SECCION IMPORTAR USUARIOS



    function GuardarEditarUsuario() {
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
            url: '<?= base_url() ?>/Usuarios/GuardarEditarUsuario',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                if (response[0] == true) {
                    RecargarTabla('datatableUsuarios', response[1]);
                    if (response[2] == "") {
                        if (idUsuario == -1) MostrarAlertCorrecto("Usuario añadido correctamente");
                        else {
                            MostrarAlertCorrecto("Usuario modificado correctamente");
                            $('#btnEditarUsuario').prop('disabled', true);
                        }
                    } else {
                        MostrarAlertError(response[2]);
                    }
                    idUsuario = -1;
                } else {
                    MostrarAlertErrorMKT();
                }
                $('#btnCerrarModal').click();
                DeshabilitarBotones();
            },
            error: function(error) {
                console.log("error");
                console.log(error);
                MostrarAlertError("Algo no ha ido según lo esperado");
            }
        });
    }

    function EliminarUsuarios() {

        rows = ObtenerFilasCheckeadas('datatableUsuarios');

        var datos = {
            usuarios: rows
        };

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Usuarios/EliminarUsuarios',
            dataType: 'json',
            data: JSON.stringify(datos),
            success: function(response) {
                if (response[0] == true) {
                    RecargarTabla('datatableUsuarios', response[1]);
                    MostrarAlertCorrecto("Usuario eliminado correctamente");
                    DeshabilitarBotones();
                } else {
                    $('#btnCerrarModal').click();
                    MostrarAlertErrorMKT();
                }
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

        rows = ObtenerFilasCheckeadas('datatableUsuarios');

        var datos = {
            usuarios: rows
        };

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Usuarios/HabilitarUsuarios',
            dataType: 'json',
            data: JSON.stringify(datos),
            success: function(response) {
                if (response[0] == true) {
                    RecargarTabla('datatableUsuarios', response[1]);
                    MostrarAlertCorrecto("Usuario habilitado correctamente");
                    DeshabilitarBotones();
                } else {
                    $('#btnCerrarModal').click();
                    MostrarAlertErrorMKT();
                }
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

        rows = ObtenerFilasCheckeadas('datatableUsuarios');

        var datos = {
            usuarios: rows
        };

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Usuarios/DeshabilitarUsuarios',
            dataType: 'json',
            data: JSON.stringify(datos),
            success: function(response) {
                if (response[0] == true) {
                    RecargarTabla('datatableUsuarios', response[1]);
                    MostrarAlertCorrecto("Usuario deshabilitado correctamente");
                    DeshabilitarBotones();
                } else {
                    $('#btnCerrarModal').click();
                    MostrarAlertErrorMKT();
                }
            },
            error: function(error) {
                console.log("error");
                console.log(error);
                MostrarAlertError("Algo no ha ido según lo esperado");
                DeshabilitarBotones();

            }
        });
    }


    function ClicEditarUusario() {
        usuario = ObtenerFilasCheckeadas('datatableUsuarios');
        idUsuario = usuario[0]['.id'];
        $('#modalUsuariosTitulo').text('Editar usuario');
        $('#inputUsuario').val(usuario[0]['Usuario']);

        var perfil = usuario[0]['Perfil'];
        var $selectPerfiles = $('#selectPerfiles');

        // Comprobar si la opción de perfil existe en el select y si no selecciona la default
        if ($selectPerfiles.find('option[value="' + perfil + '"]').length) {
            $selectPerfiles.val(perfil);
        } else {
            $selectPerfiles.val('default');
        }

        $('#inpuComentario').val(usuario[0]['Comentario']);
    }

    function DescargarPlantilla() {
        var filePath = '<?php echo base_url("assets/PQ_plantilla_usuarios.csv"); ?>';

        // Crea un enlace temporal
        var a = document.createElement('a');
        a.href = filePath;

        // Define el nombre del archivo que se descargará
        a.download = 'PQ_plantilla_usuarios.csv';

        // Añade el enlace temporal al DOM
        document.body.appendChild(a);

        // Dispara el evento de click en el enlace
        a.click();

        // Elimina el enlace temporal del DOM
        document.body.removeChild(a);
    }


    function DeshabilitarBotones() {
        $("#btnEliminarUsuarios").prop("disabled", true);
        $("#btnHabilitarUsuario").prop("disabled", true);
        $("#btnDeshabilitarUsuario").prop("disabled", true);
    }

    function LimpiarDatosModal() {
        $('#inputUsuario').val("");
        $('#inputPassword').val("");
        $('#inputPasswordConfirmar').val("");
        $('#inpuComentario').val("");
    }

    function LimpiarDatosModalImportar() {
        $('#selectImportUser').empty();
        $('#selectImportPassword').empty();
        $('#selectImportComment').empty();
        $('#importarUsuariosForm')[0].reset();
    }
</script>