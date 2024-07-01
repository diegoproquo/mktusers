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
                bootstrapTablePersonalizadaCheckbox($columns, $data, "datatableUsuarios", "Usuarios", "1", false, false, false);
                ?>
            </div>
        </div>

        <form id="importarUsuariosForm">
            <label for="file">Selecciona el archivo CSV:</label>
            <input type="file" name="file" id="file" accept=".csv">
            <input type="submit" value="Subir">
        </form>

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
                <p class="text-muted">Relacione los campos del archivo CSV con cada uno de los campos de los usuarios del Mikrotik</p>
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


<script>
    var idUsuario = -1;
    var lines = [];
    var headers;

    $(document).ready(function() {

        var conexionMKT = <?= json_encode($conexionMKT) ?>;
        if (conexionMKT == false) MostrarAlertErrorMKT();

        // Añadir botones a la toolbar
        $('.fixed-table-toolbar').append('<div class="btn-group" role="group">' +
            '<button id="btnNuevoUsuario" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalUsuarios"><i class="fas fa-plus"></i> Nuevo</button>' +
            '<button id="btnEliminarUsuarios" disabled class="btn btn-sm btn-danger ms-1" onclick="EliminarUsuarios()"><i class="fas fa-minus"></i> Eliminar</button>' +
            '<button id="btnHabilitarUsuario" disabled class="btn btn-sm btn-success ms-1" onclick="HabilitarUsuarios()"><i class="fas fa-check"></i> Habilitar</button>' +
            '<button id="btnDeshabilitarUsuario" disabled class="btn btn-sm btn-warning ms-1" onclick="DeshabilitarUsuarios()"><i class="fas fa-xmark"></i> Deshabilitar</button>' +
            '</div>');


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


        // *SECTION IMPORTAR USUARIOS
        //Combinacion de metodos para importar usuarios. 

        // Listener para el form del csv
        $('#importarUsuariosForm').on('submit', function(e) {
            e.preventDefault();
            var fileInput = $('#file')[0];
            var file = fileInput.files[0];

            // Verificar si se seleccionó un archivo
            if (!file) {
                alert("Por favor, selecciona un archivo CSV.");
                return;
            }

            // Verificar la extensión del archivo
            var fileName = file.name;
            var extension = fileName.split('.').pop().toLowerCase();
            if (extension !== 'csv') {
                alert("Por favor, selecciona un archivo CSV válido.");
                return;
            }

            var reader = new FileReader();

            reader.onload = function(e) {
                var csvData = e.target.result;
                procesarCSV(csvData);
            };

            reader.readAsText(file);
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

        console.log("entra");
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

            // Valores específicos para comparación, convertidos a minúsculas
            var headerUsuario = "usuario";
            var headerPassword = "contraseña";
            var headerComentario = "comentario";

            // Función auxiliar para convertir a minúsculas y comparar
            function isEqualIgnoreCase(a, b) {
                return a.toLowerCase() === b.toLowerCase();
            }

            // Buscar y seleccionar automáticamente en cada select
            headers.forEach(function(header) {
                if (isEqualIgnoreCase(header, headerUsuario)) {
                    $('#selectImportUser').val(header);
                }

                if (isEqualIgnoreCase(header, headerPassword)) {
                    $('#selectImportPassword').val(header);
                }

                if (isEqualIgnoreCase(header, headerComentario)) {
                    $('#selectImportComment').val(header);
                }
            });
        });
        updateSelectOptions();
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

                    // Por algun motivo devuelve un "success" en la cadena de texto, lo que hace que no sea parseable a JSON.
                    // Lo que hago es eliminar esos caracteres a mano y despues parsearlo a json
                    let responseJSONvalid = response.substring(20);
                    var jsonResponse = JSON.parse(responseJSONvalid);

                    if (jsonResponse[0] == "T") {
                        RecargarTabla('datatableUsuarios', jsonResponse[1]);
                        MostrarAlertCorrecto("Uusarios importados correctamente");
                        $('#modalImportar').modal('hide');
                        LimpiarDatosModalImportar();
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

    // *SECTION FIN SECCION IMPORTAR USUARIOS



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
                if (response[0] == true) {
                    RecargarTabla('datatableUsuarios', response[1]);
                    MostrarAlertCorrecto("Uusario añadido correctamente");
                    $('#btnCerrarModal').click();
                    LimpiarDatosModal();
                } else {
                    $('#btnCerrarModal').click();
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

    function EliminarUsuarios() {

        rows = ObtenerFilasCheckeadas();

        var datos = {usuarios: rows};

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
                    LimpiarDatosModal();
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

        rows = ObtenerFilasCheckeadas();

        var datos = {usuarios: rows};

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
                    LimpiarDatosModal();
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

        rows = ObtenerFilasCheckeadas();

        var datos = {usuarios: rows};

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
                    LimpiarDatosModal();
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

    function ObtenerFilasCheckeadas() {
        var checkedRows = $('#datatableUsuarios').bootstrapTable('getSelections');
        var rowDetailsArray = checkedRows.map(function(row) {
            return row;
        });
        return rowDetailsArray;
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