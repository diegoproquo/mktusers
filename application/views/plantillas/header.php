<!DOCTYPE html>
<html lang="en">
<?php $this->load->helper('url'); ?>

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<title>Proquo MKT</title>

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<!-- Popper.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

	<!-- Bootstrap (incluye tanto CSS como JavaScript) -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



	<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link href="<?= base_url() ?>public/css/styles.css" rel="stylesheet" />
	<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha256-aAr2Zpq8MZ+YA/D6JtRD3xtrwpEz2IqOS+pWD/7XKIw=" crossorigin="anonymous" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha256-OFRAJNoaD8L3Br5lglV7VyLRf0itmoBzWUoM+Sji4/8=" crossorigin="anonymous"></script>

	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	<!--CssPersonalizado-->
	<link rel="stylesheet" href="<?= base_url() ?>assets/stylesheets/cssPersonalizado.css">

	<!--Color picker -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>


	<!--DATATABLES BOOSTRAP-->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.13.1/bootstrap-table.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.13.1/bootstrap-table.min.js"></script>

	<!-- Latest compiled and minified Locales -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.13.1/locale/bootstrap-table-zh-CN.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.3/dist/bootstrap-table.min.css" rel="stylesheet">

	<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.3/dist/bootstrap-table.min.js"></script>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

	<link rel="icon" type="image/png" href="<?= base_url() ?>assets/img/favicon.ico" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/fonts/iconic/css/material-design-iconic-font.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/vendor/animsition/css/animsition.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/vendor/daterangepicker/daterangepicker.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/css/main.css">



	<!-- Para poder exportar las datatables de bootstrap  -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.3/dist/bootstrap-table.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.28.0/tableExport.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.28.0/libs/jsPDF/jspdf.umd.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.3/dist/bootstrap-table.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.3/dist/extensions/export/bootstrap-table-export.min.js"></script>
</head>

<body class="sb-nav-fixed">


	<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
		<!-- Navbar Brand-->
		<a class="navbar-brand" onclick="IrA('Dashboard', false)" style="cursor:pointer; color:white; width:125px ">Proquo MKT</a>
		<img src="<?= base_url() ?>assets/img/ProQuo_whitetrans_big.png" width="72" style="margin-right:30px" />
		<!-- Sidebar Toggle-->
		<button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
		<!-- Navbar Search
		<form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
			<div class="input-group">
				<input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
				<button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
			</div>
		</form>-->
		<!-- Navbar-->
		<ul class="d-none navbar-nav d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" id="navbarDropdownSesion" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
				<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownSesion">

					<li><a class="dropdown-item" href="<?php echo base_url('Login/logout'); ?>">Cerrar sesión</a></li>
				</ul>
			</li>

		</ul>
	</nav>

	<style>
		.nav-link {
			cursor: pointer;
		}
	</style>

	<div id="layoutSidenav">
		<div id="layoutSidenav_nav">
			<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
				<div class="sb-sidenav-menu">
					<div class="nav">
						<div class="sb-sidenav-menu-heading">Estadísticas</div>
						<a class="nav-link" onclick="IrA('Dashboard', false)">
							<div class="sb-nav-link-icon"><i class="fas fa-chart-simple"></i></div>
							Dashboard
						</a>

						<div class="sb-sidenav-menu-heading">Gestión</div>
						<a class="nav-link" onclick="IrA('Usuarios/show', false)">
							<div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
							Usuarios
						</a>

						<a class="nav-link" onclick="IrA('Perfiles/show', false)">
							<div class="sb-nav-link-icon"><i class="fas fa-address-card"></i></div>
							Perfiles
						</a>

						 

					</div>
				</div>
				<div class="sb-sidenav-footer">
					<div class="small">Sesión iniciada como:</div> <span><?php echo $this->session->userdata('nombre'); ?></span>
				</div>
			</nav>
		</div>

		<div class="alertSuccessCustom alert-success" id="alertSuccess">
			<span class="closebtn" onclick="cerrarAlert('alertSuccess')">&times;</span>
		</div>

		<div class="alertErrorCustom alert-danger" id="alertError">
			<span class="closebtn" onclick="cerrarAlert('alertError')">&times;</span>

		</div>

		<div id="layoutSidenav_content" style="background-color: #F7F7F7">
			<main>

				<script>

					function IrA(pagina, abrirOtraPestaña) {
						if (abrirOtraPestaña == true) window.open("<?= base_url() ?>" + pagina );
						else window.location.replace("<?= base_url() ?>" + pagina );
					}




					//FUNCIONES COMUNES
					function MostrarAlertCorrecto(texto) {
						$('#alertSuccess').html('<span class="closebtn" onclick="cerrarAlert(\'alertSuccess\')">&times;</span><strong>¡Éxito! </strong>' + texto);

						document.getElementById('alertSuccess').style.display = 'block';
						document.getElementById('alertSuccess').style.animation = 'slideIn 0.5s ease forwards';
						setTimeout(() => {
							document.getElementById('alertSuccess').style.animation = 'slideOut 0.5s ease forwards';
							setTimeout(() => {
								document.getElementById('alertSuccess').style.display = 'none';
							}, 500);
						}, 4000);
					}

					function MostrarAlertError(texto) {
						$('#alertError').html('<span class="closebtn" onclick="cerrarAlert(\'alertError\')">&times;</span><strong>¡UPS! </strong>' + texto);

						document.getElementById('alertError').style.display = 'block';
						document.getElementById('alertError').style.animation = 'slideIn 0.5s ease forwards';
						setTimeout(() => {
							document.getElementById('alertError').style.animation = 'slideOut 0.5s ease forwards';
							setTimeout(() => {
								document.getElementById('alertError').style.display = 'none';
							}, 500);
						}, 4000);
					}

					function cerrarAlert(id) {
						document.getElementById(id).style.animation = 'slideOut 0.5s ease forwards';
						setTimeout(() => {
							document.getElementById(id).style.display = 'none';
						}, 500);
					}

					function RecargarTabla(id, data) {
						var tabla = $('#' + id);
						tabla.bootstrapTable('removeAll');
						tabla.bootstrapTable('append', data);
					}
				</script>