<style>
  .gradient-custom-2 {
    /* fallback for old browsers */
    background: #fccb90;

    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to right, #192a56, #273c75, #0c2461);

    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to right, #192a56, #273c75, #0c2461);
  }

  @media (min-width: 768px) {
    .gradient-form {
      height: 100vh !important;
    }
  }

  @media (min-width: 769px) {
    .gradient-custom-2 {
      border-top-right-radius: .3rem;
      border-bottom-right-radius: .3rem;
    }
  }
</style>


<section class="h-100 gradient-form" style="background-color: #eee;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-xl-10">
        <div class="card rounded-3 text-black">
          <div class="row g-0">
            <div class="col-lg-6">
              <div class="card-body p-md-5 mx-md-4">

                <div class="text-center mt-4 mb-5">
                  <h4 class="mb-3" style="color: #333; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); font-weight: 500;">Bienvenido</h4>
                </div>

                <?php if ($this->session->flashdata('error')) : ?>
                  <div class="alert alert-danger" role="alert">
                    <?php echo $this->session->flashdata('error'); ?>
                  </div>
                <?php endif; ?>

                <form method="post" action="Login/iniciarSesion" onsubmit="return loading()">
                  <p>Usuario</p>

                  <div class="form-outline mb-4">
                    <input type="text" id="usuario" class="form-control" name="usuario" />
                  </div>

                  <p>Contraseña</p>
                  <div class="form-outline mb-4">
                    <input type="password" id="password" class="form-control" name="password" />
                  </div>

                  <div class="text-center pt-1 mb-5 pb-1">
                    <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit" id="btnLogin">Log
                      in</button>
                    <!--<a class="text-muted" href="#!">¿Ha olvidado su contraseña?</a>-->
                  </div>
                </form>

              </div>
            </div>
            <div class="col-lg-6 d-flex pt-5 flex-column align-items-center gradient-custom-2">
              <div class="px-3 py-4 p-md-3 mx-md-4 text-center">
                <img src="<?= base_url() ?>assets/img/ProQuo_whitetrans_big.png" style="width: 200px;" alt="logo">
              </div>
              <div class="text-white px-3 py-4 p-md-5 mx-md-4" style="text-align:center">
                <h4 class="mb-4">
                  Soluciones profesionales
                  para redes de comunicaciones
                </h4>
                <p class="small mb-0">
                  • Redes WiFi indoor & outdoor
                  • Teletrabajo seguro con acceso remoto VPN
                  • Mantenimiento con respuesta rápida y cercano
                  • Telefonía IP

                </p>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
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