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
                <?php
                graficoBarras($dataConexiones7Dias, $labelsConexiones7Dias, "graficoBarras", "Conexiones últimos 7 días");
                ?>
            </div>
            
            <div class="col-xl-6">
                <?php
                graficoFuncionDoble($datatraficoDescarga7Dias, $datatraficoCarga7Dias, $labelsTrafico7Dias, "graficoFuncion", "Trafico acumulado últimos 7 días (MB)")
                ?>
            </div>
        </div>

        <div class="footer_pagina">

        </div>
    </div>

</div>


<script>
    var isSafari;
    $(document).ready(function() {

        var conexionMKT = <?= json_encode($conexionMKT) ?>;
        if (conexionMKT == false) MostrarAlertErrorMKT();

        var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
        console.log(isSafari);

        if (!isSafari) {
            Refrescar();
        }
        $('button.btn-secondary[name="refresh"]').on('click', function() {
            Refrescar1Vez();
        });
    });

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