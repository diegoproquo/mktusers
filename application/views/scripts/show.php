<div class="container-fluid px-4" style="width: 85%;">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-gray-800">Scripts</h1>
        <div class="container mt-5">
        </div>
    </div>

    <p class="mb-4"> </p>

    <div class="mainDiv">
        <div style="text-align: center;">
            <div class="row">
                <div class="col-md-6 h4">
                    <i class="fas fa-sign-in-alt mb-3"></i>
                    Login
                    <textarea class="form-control" rows="23" id="inputOnlogin"></textarea>
                    <button id="btnGuardarOnlogin" class="btn btn-sm btn-primary mt-2" onclick="GuardarScript(1)" style="float:right"><i class="fas fa-save"></i> Guardar</button>
                </div>
                <div class="col-md-6 h4">
                    <i class="fas fa-sign-out-alt mb-3"></i>
                    Logout
                    <textarea class="form-control" rows="23" id="inputOnlogout"></textarea>
                    <button id="btnGuardarOnlogout" class="btn btn-sm btn-primary mt-2" onclick="GuardarScript(2)" style="float:right"><i class="fas fa-save"></i> Guardar</button>
                </div>
            </div>
        </div>

        <div class="footer_pagina">

        </div>
    </div>
</div>




<script>
    $(document).ready(function() {

        var scripts = <?= json_encode($scripts) ?>;
        $('#inputOnlogin').val(scripts[0]['SCRIPT']);
        $('#inputOnlogout').val(scripts[1]['SCRIPT']);

    });

    function GuardarScript(id) {
        var datos = {};

        datos['id'] = id;
        if (id == 1) datos['script'] = $('#inputOnlogin').val();
        else if (id == 2) datos['script'] = $('#inputOnlogout').val();


        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Scripts/GuardarEditar',
            dataType: 'json',
            data: {
                datos: datos
            },
            success: function(response) {
                
                if (response[0] == true) {
                    if (response[1] == "") MostrarAlertCorrecto("Datos guardados correctamente");
                    else MostrarAlertError(response[1]);
                } else {
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
</script>