<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">Unifi Manager</li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    <div class="row">
        <div class="col-xl-6">
            <h5 class="card-title">Estadísticas últimas 24h</h5>
            <div class="row mt-1">
                <div class="col-md-6 mt-2">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <h5 class="card-title">Conexiones totales 24h</h5>
                                    <a class="card-text" id="spanConexiones24"></a>
                                </div>
                                <div class="col-md-2">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mt-2 mb-2">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <h5 class="card-title">Tráfico acumulado 24h</h5>
                                    <p class="card-text" id="spanTrafico24"></p>
                                </div>
                                <div class="col-md-2">
                                    <i class="fas fa-wifi"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xl-6">
            <h5 class="card-title">Estadísticas últimos 7 días</h5>
            <div class="row mt-1">
                <div class="col-md-6 mt-2">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <h5 class="card-title">Conexiones totales</h5>
                                    <p class="card-text" id="spanConexiones7"></p>
                                </div>
                                <div class="col-md-2">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mt-2">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <h5 class="card-title">Tráfico acumulado</h5>
                                    <p class="card-text" id="spanTrafico7"></p>
                                </div>
                                <div class="col-md-2">
                                    <i class="fas fa-wifi"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






    <div class="row mt-4">
        <div class="col-xl-6" id="divGrafico">
            <?php
            graficoFuncion($dataHoras, $conexionesHorasDB, $labelsHoras, "graficoHoras", "Datos por horas hoy");
            ?>
        </div>

        <div class="col-xl-6">
            <?php
            graficoBarras($dataDiarias, $conexiones7diasDB, $labelsDiarias, $conexionesMaximasDiarias, "graficoDiarias", "Datos últimos 7 días");
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <?php
            bootstrapTablePersonalizada($columns, $data, 'datatableConexiones', "Registros del portal cautivo", $esconder, true, true, true);
            ?>
        </div>
    </div>


</div>


<script>
    var fechaGraficoFuncion;
    var fechaActual;
    var fechaHace7dias;

    $(document).ready(function() {
        var conexiones24 = <?= json_encode($conexiones24horas) ?>;
        var trafico24horas = <?= json_encode($trafico24horas) ?>;
        var conexiones7 = <?= json_encode($conexiones7dias) ?>;
        var trafico7dias = <?= json_encode($trafico7dias) ?>;

        //Estas dos variables globales sirven para controlar la fecha del grafico de funcion (izquierda)
        fechaGraficoFuncion = <?= json_encode($fechaActual) ?>;
        fechaActual = fechaGraficoFuncion;
        fechaHace7dias = <?= json_encode($fechaHace7dias) ?>;

        $('#spanConexiones24').text(' ' + conexiones24.toString());
        $('#spanTrafico24').text(' ' + trafico24horas);
        $('#spanConexiones7').text(' ' + conexiones7.toString());
        $('#spanTrafico7').text(trafico7dias);

        // Creamos los campos para filtrar por fecha y les palciamos el valor que hemos usado en la consulta (1 mes atrás)
        var fechaInicio = <?= json_encode($fechaInicio) ?>;
        var fechaFin = <?= json_encode($fechaActual) ?>;
        $('.fixed-table-toolbar').append('<div class="row mb-4 align-items-end"><div class="col-md-2"><label for="inputFechaInicio">Fecha inicio</label><input id="inputFechaInicio" value="' + fechaInicio + '" class="form-control" type="date"/></div><div class="col-md-2"><label for="inputFechaFin">Fecha fin</label><input id="inputFechaFin" value="' + fechaFin + '" class="form-control" type="date"/></div><div class="col-md-2"><button onclick="FiltrarTabla()" id="filtrar" class="btn btn-primary">Filtrar</button></div></div>');

    });

    // Actualiza el grafico de la izquierda del dashboard (funcion). Los 2 botones del grafico se cargan en el helper y tienen una "accion" en elos, que es sumar o restar un dia.
    // Ambos llaman a este metodo. Pasamos la ultima fecha actualizada que tenemos en el dashboard y la accion, junto con el site_id
    // Nos devuelve la fecha resultante y el html del grafico, el cual cargamos en el div del grafico. El antiguo lo borramos

    function actualizarGraficoFuncion(accion) {
        var datos = {};

        datos['fechaGraficoFuncion'] = fechaGraficoFuncion;
        datos['accion'] = accion;
        datos['site_id'] = <?= json_encode($site_id) ?>;

        // Borramos el grafico actual
        $('#graficoHoras').remove();

        // Insertamos un spinner temporal
        $('#divGrafico').html('<div class="text-center mt-4"><div class="spinner-border text-primary" role="status" id="spinnerCargando"><span class="sr-only">Cargando...</span></div></div>');

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Dashboard/ActualizarGraficoFuncion',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                // Cargamos el nuevo grafico y actualizamos la fecha elegida
                $('#divGrafico').empty();
                $('#divGrafico').append(response[1]);
                fechaGraficoFuncion = response[2];

                // Evita ir a una fecha futura
                if (fechaGraficoFuncion === fechaActual) {
                    $('#btnGraficoMas1').prop('disabled', true);
                } else {
                    $('#btnGraficoMas1').prop('disabled', false);
                }

                // Evita retroceder mas de 7 dias (el metodo de conexiones por horas no devuelve nada mas allá)
                if (fechaGraficoFuncion === fechaHace7dias) {
                    $('#btnGraficoMenos1').prop('disabled', true);
                } else {
                    $('#btnGraficoMenos1').prop('disabled', false);
                }
                


            },
            error: function(error) {
                console.log("error");
                console.log(error);

            }
        });
    }

    function FiltrarTabla() {

        var datos = {};

        datos['fechaInicio'] = $('#inputFechaInicio').val();
        datos['fechaFin'] = $('#inputFechaFin').val();
        datos['site_id'] = <?= json_encode($site_id) ?>;

        //Mostramos el loading
        var $table = $('#datatableConexiones')
        $table.bootstrapTable('showLoading')

        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Dashboard/FiltrarTabla',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                RecargarTabla('datatableConexiones', response[1]);
                $table.bootstrapTable('hideLoading');

            },
            error: function(error) {
                console.log("error");
                console.log(error);

            }
        });

    }

    function RecargarTabla(id, data) {
        var tabla = $('#' + id);
        tabla.bootstrapTable('removeAll');
        tabla.bootstrapTable('append', data);
    }
</script>