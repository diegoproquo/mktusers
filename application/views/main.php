
<div id="container">
    <h1>Unifi Manager</h1>
    <div id="body">
        <button onclick="PruebaConexion()">Prueba de conexión</button>
        <a href="<?= base_url('Prueba') ?>">Ir a admin panel</a>
    </div>

    <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group row">
            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputUser" class="col-sm-2 col-form-label">User Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputUser" name="user" placeholder="Username">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password">
            </div>
        </div>
        <div class="form-group row">
            <div class="offset-sm-2 col-sm-10">
                <input type="submit" value="Sign in" name="submit" class="btn btn-primary" />
            </div>
        </div>
    </form>
    <button type="button" onclick="Test()" class="btn btn-primary">Primary
    </button>
    <button type="button" class="btn btn-secondary">Secondary
    </button>
    <button type="button" class="btn btn-success">Success
    </button>
    <button type="button" class="btn btn-danger">Danger
    </button>
    <button type="button" class="btn btn-warning">Warning
    </button>
    <button type="button" class="btn btn-info">Info
    </button>
    <button type="button" class="btn btn-light">Light
    </button>
    <button type="button" class="btn btn-dark">Dark
    </button>
    <button type="button" class="btn btn-link">Link
    </button>
</div>


<script>
    $(document).ready(function() {
        // Intercepta el envío del formulario
        $("#frmLogin").submit(function(event) {
            event.preventDefault(); // Detiene el envío del formulario

            var usuario = $("#usuario").val();
            var parametros = JSON.stringify({
                usuario: usuario,
            });

            $.ajax({
                type: "post",
                url: "<?= base_url() ?>/Usuarios/PrimerAcceso",
                dataType: "json",
                data: parametros,
                success: function(response) {
                    if (response[0] == true) {
                        $("#modalPrimerAcceso").modal("show");
                    } else if (response[0] == false) {
                        $("#frmLogin")[0].submit();
                    }

                }
            });
        });
    });

    function PruebaConexion() {
        $.ajax({
            type: 'POST',
            url: '<?= ("index.php/main/PruebaConexionUnifi") ?>',
            dataType: 'json',
            success: function(response) {

            },
            error: function(error) {
                console.log(error);

            }
        });
    }

    function Test() {
        var usuario = "Diego";

        var parametros = ({
            usuario: usuario,
        });

        $.ajax({
            type: "post",
            url: '<?= ("index.php/main/Test") ?>',
            dataType: "json",
            data: parametros,
            success: function(response) {
                console.log(response);
                $('#inputEmail').val(response);


            }
        });
    }

</script>
</body>