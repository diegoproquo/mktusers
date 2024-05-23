<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">Proquo MKT</li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    <div class="mainDiv">
        <div style="text-align: center;">
            <div id="divTabla" style="width: 100%; display: inline-block; text-align: left;">
                <?php
                bootstrapTablePersonalizada($columns_usuarios_activos, $data_usuarios_activos, "datatableUsuariosActivos", "Usuarios activos", "0", false, false, false, false);
                ?>
            </div>
        </div>

        <div style="text-align: center;">
            <div id="divTabla" style="width: 100%; display: inline-block; text-align: left;">
                <?php
                bootstrapTablePersonalizada($columns_ultimas_conexiones, $data_ultimas_conexiones, "datatableUltimasConexiones", "Últimas conexiones", "0", false, false, false, false);
                ?>
            </div>
        </div>

        <div class="footer_pagina">

        </div>
    </div>

</div>


<script>

    $(document).ready(function() {

    });



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


</script>