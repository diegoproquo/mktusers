<div class="container-fluid px-4" style="width: 85%;">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tags</h1>
        <div class="btn-group">
        <button id="btnNuevoTag" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalTags" style="margin-left:20px"><i class="fas fa-plus"></i> Nuevo</button>
            <button id="btnEliminarTag" disabled class="btn btn-sm btn-danger ms-1" onclick="EliminarTag()"><i class="fas fa-minus"></i> Eliminar</button>
            <button id="btnEditarTag" disabled class="btn btn-sm btn-info ms-5" data-toggle="modal" data-target="#modalTags" onclick="ClicEditarTag()"><i class="fas fa-pen"></i> Editar</button>
        
        </div>

    </div>

    <p class="mb-4"> Esta es la ventana para gestionar los tags que pueden aplicarse a los usuarios hotspot con el fin de agruparlos e identificarlos más fácilmente.
    </p>

    <div class="mainDiv">


        <div class="row">
            <div class="col-md-6">
                <div class="containerCard">
                    <div class="card border-left-primary shadow h-100 py-2" style="cursor:grab" draggable="true" id="card1">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-s font-weight-bold text-primary text-uppercase mb-1">
                                        1º curso</div>
                                    <div class="mb-0 text-gray-800">32 alumnos</div>
                                </div>
                                <div class="col-auto ms-2">
                                    <i class="fas fa-tag fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-left-success shadow h-100 py-2" style="cursor:grab" draggable="true" id="card2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-s font-weight-bold text-success text-uppercase mb-1">
                                        2º curso</div>
                                    <div class="mb-0 text-gray-800">32 alumnos</div>
                                </div>
                                <div class="col-auto ms-2">
                                    <i class="fas fa-tag fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-6">

                <div class="dropzone dropzoneEditar" id="dropzone1">Editar</div>
                <div class="dropzone dropzoneEliminar" id="dropzone2">Eliminar</div>
            </div>

        </div>

    </div>

    <style>
.containerCard {
    display: flex;
    justify-content: space-around;
    padding: 20px;
}

.cardCustom {
    width: 100px;
    height: 100px;
    background-color: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    cursor: grab;
}

.dropzoneEditar {
    width: auto;
    height: 250px;
    background-color: #cceeff;
    border: 2px dashed #4dc3ff;
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: background-color 0.3s ease; /* Animación */
}

.dropzoneEliminar {
    width: auto;
    height: 250px;
    background-color: #ffcccc;
    border: 2px dashed #ff4d4d;
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 30px;
    transition: background-color 0.3s ease; /* Animación */
}

/* Estilo cuando un card está sobre la zona de destino */
.dropzoneEditar.hover {
    background-color: #4dd2ff; /* Verde cuando el card está sobre la zona */
    border-color: #33ccff; /* Cambiar el color del borde también si es necesario */
}

.dropzoneEliminar.hover {
    background-color: #ff4d4d; /* Verde cuando el card está sobre la zona */
    border-color: #ff0000; /* Cambiar el color del borde también si es necesario */
}


    </style>


    <div class="modal fade" id="modalTags" tabindex="-1" role="dialog" aria-labelledby="modalTagsTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTagsTitulo">Nuevo tag</h5>
                    <button id="btnCerrarModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row mt-2">
                        <div class="col-md-9">
                            <label>Nombre</label>
                        </div>
                        <div class="col-md-3">
                            <label>Color</label>
                        </div>
                    </div>
                    <div class="row mt-2 mb-2">
                        <div class="col-md-9">
                            <input id="inputUsuario" type="text" class="form-control" />
                        </div>
                        <div class="col-md-3">

                            <input type="text" class="form-control color-picker" id="inputColor" value="#ffffff">
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
        var idTag = -1;

        $(document).ready(function() {


            // Agregar eventos a las tarjetas (cards)
            document.querySelectorAll('.card').forEach(card => {
                card.addEventListener('dragstart', handleDragStart);
            });

            // Agregar eventos a las zonas de destino (dropzones)
            document.querySelectorAll('.dropzone').forEach(zone => {
                zone.addEventListener('dragover', handleDragOver);
                zone.addEventListener('dragleave', handleDragLeave); // Eliminar la clase hover al salir
                zone.addEventListener('drop', handleDrop);
            });

            let draggedElement = null;

            function handleDragStart(e) {
                draggedElement = e.target; // Guardamos el elemento que se está arrastrando
                e.dataTransfer.effectAllowed = 'move'; // Tipo de arrastre
                e.dataTransfer.setData('text/html', e.target.innerHTML); // Datos para transferencia
            }

            function handleDragOver(e) {
                e.preventDefault(); // Prevenir el comportamiento por defecto para permitir el drop
                e.dataTransfer.dropEffect = 'move'; // Efecto de movimiento visual
                this.classList.add('hover'); // Añadir un efecto visual al sobrevolar
            }

            function handleDragLeave(e) {
                this.classList.remove('hover'); // Eliminar el efecto visual al dejar la zona de destino
            }

            function handleDrop(e) {
                e.preventDefault();
                this.classList.remove('hover'); // Eliminar el efecto visual después del drop

                // Verificar la zona de destino y ejecutar acciones basadas en su ID
                if (this.id === 'dropzone1') {
                    alert(`Has soltado ${draggedElement.innerText} en el Dropzone 1. Ejecutando acción 1...`);
                } else if (this.id === 'dropzone2') {
                    alert(`Has soltado ${draggedElement.innerText} en el Dropzone 2. Ejecutando acción 2...`);
                }

                draggedElement = null; // Limpiar la referencia al elemento arrastrado
            }


            // Color primario e inicializar el input
            $('#inputColor').spectrum({
                showInput: true,
                showAlpha: true,
                preferredFormat: "hex",
                cancelText: "Cancelar",
                chooseText: "Seleccionar",
            });

            // ñadir los botones a la datatable
            $('.fixed-table-toolbar').append('<div class="btn-group" id="btnGrupo" role="group">' +
                '<button id="btnNuevoTag" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalTags" style="margin-left:20px"><i class="fas fa-plus"></i> Nuevo</button>' +
                '<button id="btnEliminarTag" disabled class="btn btn-sm btn-danger ms-1" onclick="EliminarTag()"><i class="fas fa-minus"></i> Eliminar</button>' +
                '</div>');

            $('#btnGrupo').after('<button id="btnEditarTag" disabled class="btn btn-sm btn-info ms-5" data-toggle="modal" data-target="#modalTags" onclick="ClicEditarTag()"><i class="fas fa-pen"></i> Editar</button>');

            $('#btnNuevoTag').on('click', function() {
                LimpiarDatosModal();
                $('#modalPerfilesTitulo').text('Nuevo usuario web');
                idTag = -1;
            });

            // Controla cuando se deshabilita el boton de eliminar
            var $table = $('#datatableTags');
            var $btnEliminarTag = $('#btnEliminarTag');
            var $btnEditarTag = $('#btnEditarTag');

            $(function() {
                $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function() {
                    var selections = $table.bootstrapTable('getSelections');
                    var numSelections = selections.length;
                    $btnEliminarTag.prop('disabled', numSelections === 0);
                    $btnEditarTag.prop('disabled', numSelections !== 1);
                })

            });

        });

        function GuardarEditar() {
            var datos = {};

            if ($('#inputPassword').val() != $('#inputPasswordConfirmar').val()) {
                alert("Las contraseñas no coinciden");
                return;
            }

            datos['id'] = idTag;
            datos['usuario'] = $('#inputUsuario').val();
            datos['password'] = $('#inputPassword').val();
            datos['rol'] = $('#selectRol').val();


            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/Tags/GuardarEditar',
                dataType: 'json',
                data: {
                    datos: datos
                },
                success: function(response) {

                    if (response[0] == true) {
                        MostrarAlertCorrecto("Datos guardados correctamente");
                        RecargarTabla('datatableTags', response[1]);
                    } else MostrarAlertError("Algo no ha ido según lo esperado");

                    DeshabilitarBotones();
                    $('#btnCerrarModal').click();
                    LimpiarDatosModal();
                    idTag = -1;
                },
                error: function(error) {
                    console.log("error");
                    console.log(error);
                    MostrarAlertError("Algo no ha ido según lo esperado");

                }
            });

        }

        function EliminarTag(id) {
            var borrar = prompt('Introduzca "1234" para borrar el tag');
            if (borrar != "1234") {
                return;
            } else {

                rows = ObtenerFilasCheckeadas('datatableTags');
                var datos = {
                    usuariosweb: rows
                };

                $.ajax({
                    type: 'POST',
                    url: '<?= base_url() ?>/Tags/EliminarTag',
                    dataType: 'json',
                    data: JSON.stringify(datos),
                    success: function(response) {
                        console.log(response);
                        if (response[0] == true) {
                            MostrarAlertCorrecto("Datos guardados correctamente");
                            RecargarTabla('datatableTags', response[1]);
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

        function ClicEditarTag() {
            LimpiarDatosModal();
            usuarioweb = ObtenerFilasCheckeadas('datatableTags');
            idTag = usuarioweb[0]['ID'];
            $('#modalPerfilesTitulo').text('Editar usuarioweb');
            $('#inputUsuario').val(usuarioweb[0]['USUARIO']);
            $('#inputPassword').val(usuarioweb[0]['PASSWORD']);
            $('#inputPasswordConfirmar').val(usuarioweb[0]['PASSWORD']);
            if (usuarioweb[0]['ROL'] == "Usuario") $('#selectRol').val(0);
            else $('#selectRol').val(1);
        }

        function DeshabilitarBotones() {
            $("#btnEliminarTag").prop("disabled", true);
            $("#btnEditarTag").prop("disabled", true);
        }

        function LimpiarDatosModal() {
            $('#inputUsuario').val("");
            $('#inputPassword').val("");
            $('#inputPasswordConfirmar').val("");
            $('#selectRol').val(0);
        }


        function DevolverColorRgba(inputId) {
            var spectrum = $(inputId).spectrum('get');
            var color = 'rgba(' + spectrum._r + ', ' + spectrum._g + ', ' + spectrum._b + ', ' + spectrum._a + ')';
            return color;
        }
    </script>