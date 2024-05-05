<style>
    @import "bourbon";

    body {
        background: #eee !important;
    }

    .wrapper {
        margin-top: 80px;
        margin-bottom: 80px;
    }

    .form-signin {
        max-width: 380px;
        padding: 15px 35px 45px;
        margin: 0 auto;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .form-signin .form-signin-heading,
    .form-signin .checkbox {
        margin-bottom: 30px;
    }

    .form-signin .checkbox {
        font-weight: normal;
    }

    .form-signin .form-control {
        position: relative;
        font-size: 16px;
        height: auto;
        padding: 10px;
        @include box-sizing(border-box);
    }

    .form-signin .form-control:focus {
        z-index: 2;
    }

    .form-signin input[type="text"] {
        margin-bottom: -1px;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .form-signin input[type="password"] {
        margin-bottom: 20px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>

<div class="wrapper">

    <form class="form-signin" method="post" action="Login/iniciarSesion" onsubmit="return loading()">

        <h2 class="form-signin-heading">Login MKT</h2>
        <input type="text" class="form-control" name="host" placeholder="Dirección IP" required="" autofocus="" />
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