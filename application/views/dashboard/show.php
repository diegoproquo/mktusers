<div class="container-fluid px-4" style="width: 85%;">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">Proquo MKT</li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    <div class="mainDiv">
        <div style="text-align: center;">
            <div id="divTabla" style="width: 100%; display: inline-block; text-align: left;">
                <?php
                bootstrapTablePersonalizada($columns_usuarios_activos, $data_usuarios_activos, "datatableUsuariosActivos", "Usuarios activos", "", false, false, false);
                ?>
            </div>
        </div>

        <div style="text-align: center;">
            <div id="divTabla" style="width: 100%; display: inline-block; text-align: left;">
                <?php
                bootstrapTablePersonalizada($columns_ultimas_conexiones, $data_ultimas_conexiones, "datatableUltimasConexiones", "Últimas conexiones", "", false, false, false);
                ?>
            </div>
        </div>

        <div class="footer_pagina">

        </div>
    </div>

</div>


<script>
    $(document).ready(function() {

        var conexionMKT = <?= json_encode($conexionMKT) ?>;
        if (conexionMKT == false) MostrarAlertErrorMKT();

    });

    function ExpulsarUsuario(id) {
        var datos = {};
        datos['id'] = id;

        console.log(id);

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