<?php
require "functions/mysql_connect.php";
include "functions/settings.php";
include "functions/fun_default.php";
include 'functions/pages_include.php';	?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $sv[$pagetitle];?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="<?php echo $sv[1];?>" name="keywords">
    <meta content="<?php echo $sv[2];?>" name="description">
    <meta name="robots" content="index, follow">
    <meta name="google-site-verification" content="f5lgN2PSufiE7IZXZqApoBmUt0vfBciLlNnSxdHA4I4" />
    <link href="/images/_design/favicon.ico" rel="icon">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">
    <!-- Icon Font Stylesheet -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!-- Libraries Stylesheet -->
    <link href="/lib/animate/animate.min.css" rel="stylesheet">
    <link href="/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="/lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/lib/datatables/datatables.min.css" rel="stylesheet">
    <link href="/lib/nanogallery2/dist/css/nanogallery2.min.css" rel="stylesheet" type="text/css">
    <link href="/lib/nanogallery2/dist/css/nanogallery2.woff.min.css" rel="stylesheet" />
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/cookieconsent.css" rel="stylesheet">
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GHDH2LWZ4P"></script>
    <script type="text/plain" data-cookiecategory="analytics" src="/js/analytics.js" defer></script>

</head>
<body class="bg-light">
<?php
include ROOT_DIR."/functions/admin_login_api.php";
if ((isset($_SESSION['qusr_logged'])) AND ($_SESSION['qusr_logged']== 1)): //prihlaseny uzivatel
    include ROOT_DIR."/inc/menu_main.php";

    include ROOT_DIR.'/inc/'.$page.'.php';

    include ROOT_DIR."/inc/footer.php";
?>
    <!-- Back to Top -->
    <a href="#" title="Nahoru" class="btn btn-lg btn-danger btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


<!--<div id="fb-root"></div>
<div id="fb-customer-chat" class="fb-customerchat"></div>-->
<?php endif; //prihlaseny uzivatel ?>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="/lib/datatables/datatables.min.js"></script>
<script src="/lib/wow/wow.min.js"></script>
<script src="/lib/easing/easing.min.js"></script>
<script src="/lib/waypoints/waypoints.min.js"></script>
<script src="/lib/owlcarousel/owl.carousel.min.js"></script>
<script src="/lib/nanogallery2/dist/jquery.nanogallery2.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.0/dist/index.bundle.min.js"></script>

<!-- Template Javascript -->
<script src="/js/main.js"></script>
<script src="/js/datatables.js"></script>
<!--<script src="/js/messenger.js"></script>-->

<script defer src="/js/cookieconsent.js"></script>
<script defer src="/js/cookieconsent-init.js"></script>

</body>

</html>