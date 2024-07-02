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
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Bootstrap (incluye tanto CSS como JavaScript) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.3/dist/bootstrap-table.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.28.0/tableExport.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.28.0/libs/jsPDF/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.3/dist/bootstrap-table.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.3/dist/extensions/export/bootstrap-table-export.min.js"></script>



    <!-- Custom styles for this template-->
    <link href="<?= base_url() ?>css/sb-admin-2.min.css" rel="stylesheet">


</head>

<style>
    .nav-link {
        cursor: pointer;
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
                    <img src="<?= base_url() ?>assets/img/ProQuo_whitetrans_big.png" width="102" />
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