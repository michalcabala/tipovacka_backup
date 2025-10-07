<?php
Header ("Expires: ".GMDate("D, d M Y H:i:s")." GMT");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "_functions/mysql_connect.php";
include "_functions/fun_default.php"; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Tipovačka HCPCEFANS, admin">
    <meta name="generator" content="TM">
    <title>Administrace, tipovacka.hcpcefans.cz</title>
    <link rel="icon" href="img/admin_logo_favicon.gif" sizes="192x192" />
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/datatables.min.css" rel="stylesheet">
    <link href="vendor/dropzone/dropzone.css" rel="stylesheet">
    <link href="vendor/ekkolightbox/ekko-lightbox.css" rel="stylesheet">
    <link href="_css/default.css" rel="stylesheet" type="text/css">

</head>

<body id="page-top">
<?php include "_functions/admin_login.php"; ?>
<?php include "_functions/pages_include.php";?>

<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
            <div class="sidebar-brand-icon rotate-n-15">

            </div>
            <img class="image-profile" src="img/admin_logo.gif">

        </a>
        <hr class="sidebar-divider my-0">
        <!-- Navigace - Dashboard -->
        <?php include "_include/menu/mm_dashboard.php" ?>
        <hr class="sidebar-divider">
        <!-- Navigace Pages obecné-->
        <?php include "_include/menu/mm_all.php" ?>
        <hr class="sidebar-divider">
        <!-- Navigace Pages projektové-->
        <?php include "_include/menu/mm_project.php" ?>
        <hr class="sidebar-divider">
        <!-- Navigace System -->
        <?php include "_include/menu/mm_system.php" ?>
        <hr class="sidebar-divider d-none d-md-block">
        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
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
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>

                <!-- Topbar Navbar -->
                <div class="d-none d-lg-inline-block col-lg-2 mt-1 ml-0 mr-0">
                    <a href="https://www.emilovapalenice.cz" title="Emilova palírna" class="stretched-link">
                        <img class="image-profile" src="img/admin_logo_long.gif">
                    </a>
                </div>
                <ul class="navbar-nav ml-auto">
                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["user_name"]; ?></span>
                            <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="index.php?qn_logout=1" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Odhlásit se
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
            <?php global $sec_text; include SEC_DIR."/_include/".$sec_text.".php" ?>


            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span><strong>Copyright &copy; <a href="https://tipovacka.hcpcefans.cz" alt="Tipovačka hcpdcefans">tipovacka.hcpdcefans.cz</a> <?php echo Date("Y");?></strong></span>&nbsp;|&nbsp;
                    <span><strong>created by <a href="mailto:tom.jirecek@gmail.com" alt="Bc. Tomáš Jireček">Bc. Tomáš Jireček</a></strong></span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Opravdu odejít?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Zvol odhlásit se, pokud chceš opravdu odejít.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Zpět</button>
                <a class="btn btn-primary" href="index.php?qn_logout=1">Odhlásit</a>
            </div>
        </div>
    </div>
</div>



<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/datatables/datatables.min.js"></script>
<script src="vendor/dropzone/dropzone-min.js"></script>
<script src="js/my_dropzone.js"></script>
<script src="vendor/ekkolightbox/ekko-lightbox.min.js"></script>
<script src="js/ekko-lightbox.js"></script>
<script src="js/my.js"></script>

<!-- Page level custom scripts -->
<script src="js/datatables.js"></script>

</body>
</html>