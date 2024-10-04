<div class="container-fluid px-4" style="width: 85%;">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>
    <div class="mainDiv">
        <div style="text-align: center;">
            <div id="divTabla" style="width: 100%; display: inline-block; text-align: left;">
                <?php
                bootstrapTablePersonalizada($columns_usuarios_activos, $data_usuarios_activos, "datatableUsuariosActivos", "Usuarios activos", "0", false, false, false);
                ?>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-xl-6">
                <div id="divGraficoConexiones">
                    <div id="graficoConexionesWrapper">
                        <?php
                        graficoBarras($dataConexiones7Dias, $labelsConexiones7Dias, "graficoConexiones", "Conexiones semanales");
                        ?>
                    </div>
                </div>
            </div>

    
                <div class="col-xl-6">
                    <div id="divGraficoTrafico">
                        <div id="graficoTraficoWrapper">
                            <?php
                            graficoFuncionDoble($datatraficoDescarga7Dias, $datatraficoCarga7Dias, $labelsTrafico7Dias, "graficoTrafico", "Trafico semanal acumulado (MB)")
                            ?>
                        </div>
                    </div>
                </div>


            <div class="footer_pagina">

            </div>
        </div>

    </div>


    <script>
        var isSafari;
        var fechaConexiones;
        var fechaTrafico;
        var fechaActual;

        $(document).ready(function() {

            var conexionMKT = <?= json_encode($conexionMKT) ?>;
            if (conexionMKT == false) MostrarAlertErrorMKT();

            fechaConexiones = <?= json_encode($fecha_actual) ?>;
            fechaTrafico = <?= json_encode($fecha_actual) ?>;
            fechaActual = <?= json_encode($fecha_actual) ?>;

            var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

            if (!isSafari) {
                Refrescar();
            }
            $('button.btn-secondary[name="refresh"]').on('click', function() {
                Refrescar1Vez();
            });
        });

        function actualizarGraficoBarras(accion) {
            var datos = {};
            datos['fechaConexiones'] = fechaConexiones;
            datos['accion'] = accion;

            var direction = accion === 'next' ? 'left' : 'right';
            var $grafico = $('#graficoConexionesWrapper');
            var salida = (direction === 'left' ? '-100%' : '100%');
            var entrada = (direction === 'left' ? '100%' : '0%');

            $grafico.animate({
                transform: 'translateX(' + salida + ')',
                opacity: 0
            }, 200, function() {
                // Mostrar un spinner
                $('#divGraficoConexiones').html('<div class="text-center mt-4"><div class="fa fa-spinner fa-spin mt-5" style="font-size:40px; color:rgba(2,117,216,1);" role="status" id="spinnerCargando"><span class="sr-only">Cargando...</span></div></div>');

                $.ajax({
                    type: 'POST',
                    url: '<?= base_url() ?>/Dashboard/ActualizarGraficoBarras',
                    dataType: 'json',
                    data: {
                        datos: datos
                    },
                    success: function(response) {
                        $('#divGraficoConexiones').html('<div id="graficoConexionesWrapper" style="transform: translateX(' + entrada + '); opacity: 0;">' + response[2] + '</div>');

                        fechaConexiones = response[1];

                        if (fechaConexiones === fechaActual) {
                            $('#btnGraficoBarrasMas1').prop('disabled', true);
                        } else {
                            $('#btnGraficoBarrasMas1').prop('disabled', false);
                        }
                        $('#graficoConexionesWrapper').animate({
                            transform: 'translateX(0)',
                            opacity: 1
                        }, 200);
                    },
                    error: function(error) {
                        console.log("error");
                        console.log(error);
                    }
                });
            });
        }


        function actualizarGraficoFuncion(accion) {
            var datos = {};
            datos['fechaTrafico'] = fechaTrafico;
            datos['accion'] = accion;

            var direction = accion === 'next' ? 'left' : 'right';
            var $grafico = $('#graficoTraficoWrapper');
            var salida = (direction === 'left' ? '-100%' : '100%');
            var entrada = (direction === 'left' ? '100%' : '0%');

            $grafico.animate({
                transform: 'translateX(' + salida + ')',
                opacity: 0
            }, 200, function() {
                // Mostrar un spinner
                $('#divGraficoTrafico').html('<div class="text-center mt-4"><div class="fa fa-spinner fa-spin mt-5" style="font-size:40px; color:rgba(2,117,216,1);" role="status" id="spinnerCargando"><span class="sr-only">Cargando...</span></div></div>');

                $.ajax({
                    type: 'POST',
                    url: '<?= base_url() ?>/Dashboard/ActualizarGraficoFuncion',
                    dataType: 'json',
                    data: {
                        datos: datos
                    },
                    success: function(response) {
                        $('#divGraficoTrafico').html('<div id="graficoTraficoWrapper" style="transform: translateX(' + entrada + '); opacity: 0;">' + response[2] + '</div>');

                        fechaTrafico = response[1];

                        if (fechaTrafico === fechaActual) {
                            $('#btnGraficoFuncionDobleMas1').prop('disabled', true);
                        } else {
                            $('#btnGraficoFuncionDobleMas1').prop('disabled', false);
                        }
                        $('#graficoTraficoWrapper').animate({
                            transform: 'translateX(0)',
                            opacity: 1
                        }, 200);
                    },
                    error: function(error) {
                        console.log("error");
                        console.log(error);
                    }
                });
            });
        }



        function Refrescar() {
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/Dashboard/Refrescar',
                dataType: 'json',
                success: function(response) {
                    if (response[0] == true) {
                        RecargarTabla('datatableUsuariosActivos', response[1]);
                        setTimeout(Refrescar, 5000);
                    } else MostrarAlertErrorMKT();
                }
            });
        }

        function Refrescar1Vez() {
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/Dashboard/Refrescar',
                dataType: 'json',
                success: function(response) {
                    if (response[0] == true) {
                        RecargarTabla('datatableUsuariosActivos', response[1]);
                    } else MostrarAlertErrorMKT();
                }
            });
        }

        function ExpulsarUsuario(id) {
            var datos = {};
            datos['id'] = id;

            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/Dashboard/ExpulsarUsuario',
                dataType: 'json',
                data: {
                    datos: datos
                },
                success: function(response) {
                    if (response[0] == true) {
                        RecargarTabla('datatableUsuariosActivos', response[1]);
                        MostrarAlertCorrecto("Usuario expulsado correctamente");
                    } else MostrarAlertErrorMKT();
                },
                error: function(error) {
                    console.log("error");
                    console.log(error);
                    MostrarAlertError("Algo no ha ido según lo esperado");
                }
            });
        }
    </script>