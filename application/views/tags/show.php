<style>
    html {
        overflow: visible;
    }

    .containerCard {
        padding: 20px;
        overflow: auto;
        max-height: 550px
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
        transition: background-color 0.3s ease;
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
        transition: background-color 0.3s ease;
    }

    .card {
        height: 110px;
        max-width: 417px;
    }

    .dropzoneEditar.hover {
        background-color: #66d9ff;
        border-color: #007399;
    }

    .dropzoneEliminar.hover {
        background-color: #ff6666;
        border-color: #990000;
    }

</style>

<div class="container-fluid px-4" style="width: 85%;overflow: hidden;">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tags</h1>
        <div class="btn-group">
            <button id="btnNuevoTag" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalTags" style="margin-left:20px"><i class="fas fa-plus"></i> Nuevo</button>
        </div>

    </div>

    <p style="margin-bottom:0px;"> Esta es la ventana para gestionar los tags que pueden aplicarse a los usuarios hotspot con el fin de agruparlos e identificarlos más fácilmente.</p>
    <p> <strong>Arrastre los tags para modificarlos.</strong></p>


    <div class="mainDiv">
        <div class="row">
            <div class="col-md-8">
                <div class="containerCard" id="containerCard">

                </div>
            </div>

            <div class="col-md-4 mt-4">
                <div class="dropzone dropzoneEditar h3 font-weight-bold" id="dropzoneEditar">Editar &nbsp;<i class="fas fa-pen "></i></div>
                <div class="dropzone dropzoneEliminar h3 font-weight-bold" id="dropzoneEliminar">Eliminar &nbsp;<i class="fas fa-trash"></i></div>
            </div>
        </div>
    </div>


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
                            <input id="inputNombre" type="text" class="form-control" />
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

    <button id="btnAbrirModal" data-toggle="modal" data-target="#modalTags" hidden></button>

    <script>
        var idTag = -1;
        var tags;

        $(document).ready(function() {

            tags = <?= json_encode($tags) ?>;
            GenerarTags();

            // Color primario e inicializar el input
            $('#inputColor').spectrum({
                showInput: true,
                preferredFormat: "hex",
                cancelText: "Cancelar",
                chooseText: "Seleccionar",
            });

            $('#modalTags').on('hidden.bs.modal', function(e) {
                LimpiarDatosModal();
            });

            // Control de la modal
            $('#btnNuevoTag').on('click', function() {
                LimpiarDatosModal();
                $('#modalTagsTitulo').text('Nuevo tag');
                idTag = -1;
            });
        });


        // PRIMERA HAY QUE CREAR EL HTML, DESPUES HAY QUE INICIALIZARLO PARA PERMITIR LOS METODOS DRAG & DROP
        function GenerarTags() {

            $("#containerCard").empty();

            var count = 0;
            var elemento = "";
            for (i = 0; i < tags.length; i++) {

                if (count % 2 == 0) elemento += '<div class="row pb-4">';

                elemento += '<div class="col-md-6"> ' +
                    ' <div class="card shadow h-100 py-2" style="cursor:grab; border-left:.25rem solid ' + tags[i]["COLOR"] + ' !important " draggable="true" id="' + tags[i]["ID"] + '">' +
                    ' <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2">' +
                    ' <div class="text-s font-weight-bold text-uppercase mb-1" style="color: ' + tags[i]["COLOR"] + ' !important">' + tags[i]["NOMBRE"] + '</div>' +
                    ' <div class="mb-0 text-gray-800">' + tags[i]['USUARIOS'] + ' usuarios</div>' +
                    '</div><div class="col-auto ms-2"><i class="fas fa-tag fa-2x text-gray-300"></i></div></div></div></div></div>';

                if (count % 2 != 0 && count > 0) elemento += '</div>';

                count++;

            }

            $("#containerCard").append(elemento);
            InicializarTags();
        }

        function GuardarEditar() {
            var datos = {};

            if ($('#inputNombre').val() == "") {
                alert("El nombre no puede quedar en blanco");
                return;
            }

            datos['id'] = idTag;
            datos['nombre'] = $('#inputNombre').val();
            datos['color'] = DevolverColorRgba('#inputColor');


            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/Tags/GuardarEditar',
                dataType: 'json',
                data: {
                    datos: datos
                },
                success: function(response) {

                    if (response[0] == true) {
                        MostrarAlertCorrecto("Tag guardado correctamente");
                        tags = response[1];
                        GenerarTags();
                    } else MostrarAlertError("Algo no ha ido según lo esperado");

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

                var datos = {
                    id: id
                };

                $.ajax({
                    type: 'POST',
                    url: '<?= base_url() ?>/Tags/EliminarTag',
                    dataType: 'json',
                    data: {
                        datos: datos
                    },
                    success: function(response) {
                        if (response[0] == true) {
                            MostrarAlertCorrecto("Tag eliminado correctamente");
                            tags = response[1];
                            GenerarTags();
                        } else MostrarAlertError("Algo no ha ido según lo esperado");

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
        }

        function ClicEditarTag(id) {
            LimpiarDatosModal();

            var tag = tags.find(function(tag) {
                return tag.ID === id;
            });

            idTag = tag['ID'];
            $('#inputNombre').val(tag['NOMBRE']);
            $('#inputColor').spectrum('set', tag['COLOR']);
            $('#modalTagsTitulo').text('Editar tag');
            $('#btnAbrirModal').click();


        }

        function LimpiarDatosModal() {
            $('#inputNombre').val("");
            $('#inputColor').val("");
        }


        function DevolverColorRgba(inputId) {
            var spectrum = $(inputId).spectrum('get');

            // Limitar los valores a dos decimales
            var r = spectrum._r.toFixed(0);
            var g = spectrum._g.toFixed(0);
            var b = spectrum._b.toFixed(0);
            var a = spectrum._a.toFixed(0);

            var color = 'rgba(' + r + ', ' + g + ', ' + b + ', ' + a + ')';
            return color;
        }


        // Este metodo sirve para controlar los eventos drag and drop. Basicamente se crea un clon de la card y el elemento original se le da una opacidad de 0 para que sea invisible.
        // Despues abajo del todo establecemos el eventoq eu queremos que suceda al soltar el elemento en el dropzone
        function InicializarTags() {

            // Obtener todas las cards que pueden ser arrastradas
            const cards = document.querySelectorAll('.card');

            cards.forEach(card => {
                // Cuando comience a arrastrar
                card.addEventListener('dragstart', function(event) {
                    document.body.style.overflow = "hidden"; //OCultamos el scroll para qu eno 
                    this.style.opacity = '0'; // Hacer el elemento original transparente

                    // Crear una imagen fantasma personalizada (usando el elemento original)
                    const ghost = this.cloneNode(true); // Clonar el elemento

                    // Hacer que el fantasma no sea transparente (opacidad completa)
                    ghost.style.width = this.offsetWidth + 'px'; // Fijar el ancho del fantasma al mismo que el original
                    ghost.style.height = this.offsetHeight + 'px';
                    ghost.style.opacity = '1';
                    ghost.style.zIndex = '1000'; // Asegurar que el fantasma esté por encima de otros elementos

                    // Insertar el fantasma en el body temporalmente
                    document.body.appendChild(ghost);

                    // Usar setDragImage para mostrar el fantasma
                    event.dataTransfer.setDragImage(ghost, this.offsetWidth / 2, this.offsetHeight / 2);

                    setTimeout(() => {
                        // Remover el fantasma tras un pequeño retraso
                        document.body.removeChild(ghost);
                    }, 0);
                });

                // Cuando termine de arrastrar
                card.addEventListener('dragend', function(event) {
                    document.body.style.overflow = "";
                    this.style.opacity = '1'; // Restaurar la opacidad del elemento original
                });
            });

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
                if (this.id === 'dropzoneEditar') {
                    ClicEditarTag(draggedElement.id);
                } else if (this.id === 'dropzoneEliminar') {
                    EliminarTag(draggedElement.id);
                }

                draggedElement = null; // Limpiar la referencia al elemento arrastrado
            }
        }
    </script>