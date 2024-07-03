<div class="container-fluid px-4" style="width: 85%;">
    <h1 class="mt-4">Informes</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">Proquo MKT</li>
        <li class="breadcrumb-item active">Informes</li>
    </ol>
    <div class="mainDiv">
        <div class="p-3">
            <blockquote class="blockquote">
                <p class="mb-0" style="font-size:18px;">En este apartado puede especificar si prefiere o no recibir informes periódicos de los registros y la dirección de correo donde desea recibirlos.</p>
            </blockquote>

            <div class="row mb-3 mt-5">
                <div class="col-md-4">
                    <label for="inputEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="inputEmail">
                    <div id="emailError" class="text-danger" style="display: none;">Por favor, introduce una dirección de correo electrónico válida.</div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="inputPeriodicidad" class="form-label">Periodicidad</label>
                    <select class="form-control" id="inputPeriodicidad">
                        <option value="0" selected>Desactivado</option>
                        <option value="1">Semanal</option>
                        <option value="2">Mensual</option>

                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-1 offset-md-3">
                    <button class="btn btn-primary" style="float:right" onclick="GuardarEmail()">Guardar</button>
                </div>
            </div>
        </div>

        <div class="footer_pagina">

        </div>
    </div>
</div>


<script>
    var email;

    $(document).ready(function() {
        email = <?= json_encode($email) ?>;

        if (email.EMAIL != null) $('#inputEmail').val(email.EMAIL);
        $('#inputPeriodicidad').val(email.PERIODICIDAD);

    });



    function GuardarEmail() {
        var datos = {};

        var inputEmail = $('#inputEmail').val().trim();

        // Comprobamos que el campo del mail se valido
        if (!isValidEmail(inputEmail)) {
            emailError.style.display = "block";
            event.preventDefault(); // Evita l allamada AJAX si el email no es válido
        } else {
            emailError.style.display = "none";
            datos['site_id'] = email.SITE_ID;
            datos['id'] = email.ID;
            datos['email'] = $('#inputEmail').val();
            datos['periodicidad'] = $('#inputPeriodicidad').val();

            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/Informes/GuardarEmail',
                dataType: 'json',
                data: {
                    datos: datos
                },
                success: function(response) {
                    MostrarAlertCorrecto("Datos guardados correctamente");
                },
                error: function(error) {
                    console.log("error");
                    console.log(error);
                    MostrarAlertError("Algo no ha ido según lo esperado");

                }
            });
        }
    }

    function isValidEmail(email) {
        // Esta función valida el formato de correo electrónico
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
</script>