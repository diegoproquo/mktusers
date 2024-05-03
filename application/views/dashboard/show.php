<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">Proquo MKT</li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>


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