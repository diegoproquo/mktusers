
<div class="wrapper_login">

    <form class="form-signin" method="post" action="Login/iniciarSesion" onsubmit="return loading()">

        <h2 class="form-signin-heading" style="text-align:center">Gestor de usuarios WiFi</h2>
        <input type="text" class="form-control" name="user" placeholder="Usuario" required="" />
        <input type="password" class="form-control" name="pass" placeholder="Contraseña" required="" />

        <button class="btn btn-lg btn-primary btn-block" type="submit" id="btnLogin">Login</button>

        <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger mt-4" role="alert">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

    </form>
</div>

<script>
    function loading() {
        var botonLogin = document.getElementById("btnLogin");
        botonLogin.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        setTimeout(function() {
            botonLogin.disabled = true;
        }, 100);
        return true;
    }
</script>