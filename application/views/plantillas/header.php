<!DOCTYPE html>
<html lang="en">
<?php $this->load->helper('url'); ?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ProQuo MKT</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url() ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() ?>css/googleapisfonts.css" rel="stylesheet">

    <!-- JQuery -->
    <script src="<?= base_url() ?>js/jquery-3.6.0.min.js"></script>

    <!-- Popper.js -->
    <script src="<?= base_url() ?>js/popper.min1147.js"></script>

    <!-- Bootstrap 5 (incluye tanto CSS como JavaScript) -->
    <link href="<?= base_url() ?>css/bootstrap.min.css" rel="stylesheet">

    <link href="<?= base_url() ?>css/style.min.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="<?= base_url() ?>js/jquery.dataTables.js"></script>

    <link rel="stylesheet" href="<?= base_url() ?>css/bootstrap4.min.css" />
    <script src="<?= base_url() ?>js/bootstrap4.min.js"></script>


    <link href="<?= base_url() ?>css/select2.min.css" rel="stylesheet" />
    <script src="<?= base_url() ?>js/select2.min.js"></script>

    <script src="<?= base_url() ?>js/chart.js"></script>

    <!--CssPersonalizado-->
    <link rel="stylesheet" href="<?= base_url() ?>assets/stylesheets/cssPersonalizado.css">

    <!--Color picker 
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>
    -->

    <!--DATATABLES BOOSTRAP-->
    <link rel="stylesheet" href="<?= base_url() ?>css/bootstrap-table.min113.css">


    <link rel="icon" type="image/png" href="<?= base_url() ?>assets/img/favicon.ico" />
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

    <!-- Para poder exportar las datatables de bootstrap  -->
    <link href="<?= base_url() ?>css/bootstrap-table.min.css" rel="stylesheet">
    <script src="<?= base_url() ?>js/tableExport.min.js"></script>
    <script src="<?= base_url() ?>js/jspdf.umd.min.js"></script>
    <script src="<?= base_url() ?>js/bootstrap-table.min.js"></script>
    <script src="<?= base_url() ?>js/bootstrap-table-export.min.js"></script>

    <!-- Custom styles for this template-->
    <link href="<?= base_url() ?>css/sb-admin-2.min.css" rel="stylesheet">


</head>

<style>
    .nav-link {
        cursor: pointer;
    }

    .sidebar-divider {
        border-top: 1px solid rgba(255, 255, 255, .75) !important;
    }
</style>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon">
                    <img src="<?= base_url() ?>assets/img/ProQuo_whitetrans_big.png" width="98" />
                </div>
                <!-- <div class="sidebar-brand-text mx-3">ProQuo MKT </div> -->
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">


            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" onclick="IrA('Dashboard', false)">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <hr class="sidebar-divider my-0">

            <div class="sidebar-heading mt-3">
                Configuración
            </div>
            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" onclick="IrA('Usuarios/show', false)">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span></a>
            </li>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" onclick="IrA('Perfiles/show', false)">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Perfiles</span></a>
            </li>

            <hr class="sidebar-divider my-0">

            <div class="text-center d-none d-md-inline mt-4">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdownSesion" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownSesion">
                                    <li><a class="dropdown-item" href="<?php echo base_url('Login/logout'); ?>">Cerrar sesión</a></li>
                                </ul>
                            </li>
                        </ul>
                    </ul>

                </nav>


                <div class="alertSuccessCustom alert-success" id="alertSuccess">
                    <span class="closebtn" onclick="cerrarAlert('alertSuccess')">&times;</span>
                </div>

                <div class="alertErrorCustom alert-danger" id="alertError">
                    <span class="closebtn" onclick="cerrarAlert('alertError')">&times;</span>

                </div>

                <div id="layoutSidenav_content" style="background-color: #F7F7F7">
                    <main style="flex:1">



                        <script>
                            function IrA(pagina, abrirOtraPestaña) {
                                if (abrirOtraPestaña == true) window.open("<?= base_url() ?>" + pagina);
                                else window.location.replace("<?= base_url() ?>" + pagina);
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
                                $('#alertError').html('<span class="closebtn" onclick="cerrarAlert(\'alertError\')">&times;</span><strong>Error: </strong>' + texto);

                                document.getElementById('alertError').style.display = 'block';
                                document.getElementById('alertError').style.animation = 'slideIn 0.5s ease forwards';
                                setTimeout(() => {
                                    document.getElementById('alertError').style.animation = 'slideOut 0.5s ease forwards';
                                    setTimeout(() => {
                                        document.getElementById('alertError').style.display = 'none';
                                    }, 500);
                                }, 4000);
                            }

                            function MostrarAlertErrorMKT(texto) {
                                $('#alertError').html('<span class="closebtn" onclick="cerrarAlert(\'alertError\')">&times;</span> No es posible establecer conexión con el Mikrotik. Si el problema persiste, contacta con <strong>ProQuo</strong>');

                                document.getElementById('alertError').style.display = 'block';
                                document.getElementById('alertError').style.animation = 'slideIn 0.5s ease forwards';
                                setTimeout(() => {
                                    document.getElementById('alertError').style.animation = 'slideOut 0.5s ease forwards';
                                    setTimeout(() => {
                                        document.getElementById('alertError').style.display = 'none';
                                    }, 500);
                                }, 10000);
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

                            function ObtenerFilasCheckeadas(tableId) {
                                var checkedRows = $('#' + tableId).bootstrapTable('getSelections');
                                var rowDetailsArray = checkedRows.map(function(row) {
                                    return row;
                                });
                                return rowDetailsArray;
                            }
                        </script>