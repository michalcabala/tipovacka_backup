<?php global $section; global $page; global $sec_page;?>
<div class="sidebar-heading">
    Hlavní menu
</div>
<?php
// 1 - main, 2 - vse krome system,
$user_prava = $_SESSION["user_prava"];
switch ($user_prava)
{    case "1" OR "2": //main přístupová práva        ?>
    <!-- MENU NOVINKY -->
    <li class="nav-item <?php if($section=="01" AND $page=="01"): echo "active";endif;?>">
        <a class="nav-link collapsed pt-2 pb-2" href="#" data-toggle="collapse" data-target="#collapseNews" aria-expanded="true" aria-controls="collapseNews">
            <i class="fas fa-fw fa-table"></i>
            <span>Novinky</span>
        </a>
        <div id="collapseNews" class="collapse <?php if($section=="01" AND $page=="01"): echo "show";endif;?>" aria-labelledby="headingNews" data-parent="#accordionSidebar">
            <div class="bg-white py-1 collapse-inner rounded">
                <h6 class="collapse-header">Novinky:</h6>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="01" AND $sec_page=="02"): echo "active";endif;?>" href="?section=01&amp;page=01&amp;sec_page=02">Výpis novinek</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="01" AND $sec_page=="01"): echo "active";endif;?>" href="?section=01&amp;page=01&amp;sec_page=02&amp;show=1">Přidat novinku</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="01" AND $sec_page=="03"): echo "active";endif;?>" href="?section=01&amp;page=01&amp;sec_page=03">Typy novinek</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="01" AND $sec_page=="05"): echo "active";endif;?>" href="?section=01&amp;page=01&amp;sec_page=05">Uživatelé newsletteru</a>
            </div>
        </div>
    </li>
    <!-- MENU STATICKE TEXTY -->
    <li class="nav-item <?php if($section=="01" AND $page=="02"): echo "active";endif;?>">
        <a class="nav-link collapsed pt-2 pb-2" href="#" data-toggle="collapse" data-target="#collapseTexty" aria-expanded="true" aria-controls="collapseTexty">
            <i class="fas fa-fw fa-tag"></i>
            <span>Statické texty, výrazy</span>
        </a>
        <div id="collapseTexty" class="collapse <?php if($section=="01" AND $page=="02"): echo "show";endif;?>" aria-labelledby="headingTexty" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Statické texty, výrazy:</h6>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="02" AND $sec_page=="02"): echo "active";endif;?>" href="?section=01&amp;page=02&amp;sec_page=02">Výpis statických textů</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="02" AND $sec_page=="01"): echo "active";endif;?>" href="?section=01&amp;page=02&amp;sec_page=02&amp;show=1">Přidat statický text</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="02" AND $sec_page=="03"): echo "active";endif;?>" href="?section=01&amp;page=02&amp;sec_page=03">Výpis statických výrazů</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="02" AND $sec_page=="11"): echo "active";endif;?>" href="?section=01&amp;page=02&amp;sec_page=03&amp;show=1">Přidat statický výraz</a>
            </div>
        </div>
    </li>
    <!-- MENU GALERIE -->
    <li class="nav-item <?php if($section=="01" AND $page=="03"): echo "active";endif;?>">
        <a class="nav-link collapsed pt-2 pb-2" href="#" data-toggle="collapse" data-target="#collapseGalerie" aria-expanded="true" aria-controls="collapseGalerie">
            <i class="fas fa-fw fa-camera"></i>
            <span>Galerie</span>
        </a>
        <div id="collapseGalerie" class="collapse <?php if($section=="01" AND $page=="03"): echo "show";endif;?>" aria-labelledby="headingGalerie" data-parent="#accordionSidebar">
            <div class="bg-white py-1 collapse-inner rounded">
                <h6 class="collapse-header">Galerie:</h6>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="03" AND $sec_page=="02"): echo "active";endif;?>" href="?section=01&amp;page=03&amp;sec_page=02">Výpis galerií</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="03" AND $sec_page=="01"): echo "active";endif;?>" href="?section=01&amp;page=03&amp;sec_page=02&amp;show=1">Přidat galerii</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="03" AND $sec_page=="03"): echo "active";endif;?>" href="?section=01&amp;page=03&amp;sec_page=03">Typy galerií</a>
            </div>
        </div>
    </li>
    <!-- MENU BLOG -->
    <li class="nav-item <?php if($section=="01" AND $page=="04"): echo "active";endif;?>">
        <a class="nav-link collapsed pt-2 pb-2" href="#" data-toggle="collapse" data-target="#collapseBlog" aria-expanded="true" aria-controls="collapseBlog">
            <i class="fas fa-fw fa-blog"></i>
            <span>Blog</span>
        </a>
        <div id="collapseBlog" class="collapse <?php if($section=="01" AND $page=="04"): echo "show";endif;?>" aria-labelledby="headingBlog" data-parent="#accordionSidebar">
            <div class="bg-white py-1 collapse-inner rounded">
                <h6 class="collapse-header">Blog:</h6>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="04" AND $sec_page=="02"): echo "active";endif;?>" href="?section=01&amp;page=04&amp;sec_page=02">Výpis článků</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="04" AND $sec_page=="01"): echo "active";endif;?>" href="?section=01&amp;page=04&amp;sec_page=02&amp;show=1">Přidat článek</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="04" AND $sec_page=="03"): echo "active";endif;?>" href="?section=01&amp;page=04&amp;sec_page=03">Kategorie článků</a>
              </div>
        </div>
    </li>
    <!-- MENU KONTAKTY -->
    <li class="nav-item <?php if($section=="01" AND $page=="09"): echo "active";endif;?>">
        <a class="nav-link collapsed pt-2 pb-2" href="#" data-toggle="collapse" data-target="#collapseContact" aria-expanded="true" aria-controls="collapseContact">
            <i class="fas fa-fw fa-address-card"></i>
            <span>Kontakty</span>
        </a>
        <div id="collapseContact" class="collapse <?php if($section=="01" AND $page=="09"): echo "show";endif;?>" aria-labelledby="headingContact" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Kontakty:</h6>
                <a class="collapse-item <?php if($section=="01" AND $page=="09" AND $sec_page=="52"): echo "active";endif;?>" href="index.php?section=01&amp;page=09&amp;sec_page=52">Dotazy výpis</a>
                <a class="collapse-item <?php if($section=="01" AND $page=="09" AND $sec_page=="53"): echo "active";endif;?>" href="index.php?section=01&amp;page=09&amp;sec_page=53">Dotazy kategorie</a>

            </div>
        </div>
    </li>
    <!-- MENU REPORTY, ZAVOZY
    <li class="nav-item <?php if($section=="01" AND $page=="21"): echo "active";endif;?>">
        <a class="nav-link collapsed pt-2 pb-2" href="#" data-toggle="collapse" data-target="#collapseContact" aria-expanded="true" aria-controls="collapseContact">
            <i class="fas fa-fw fa-address-card"></i>
            <span>Report, závozy</span>
        </a>
        <div id="collapseContact" class="collapse <?php if($section=="01" AND $page=="21"): echo "show";endif;?>" aria-labelledby="headingContact" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Závozy:</h6>
                <a class="collapse-item <?php if($section=="01" AND $page=="21" AND $sec_page=="02"): echo "active";endif;?>" href="index.php?section=01&amp;page=21&amp;sec_page=02">Výpis závozů</a>
                <a class="collapse-item <?php if($section=="01" AND $page=="21" AND $sec_page=="03"): echo "active";endif;?>" href="index.php?section=01&amp;page=21&amp;sec_page=03">Import závozů</a>
                <a class="collapse-item <?php if($section=="01" AND $page=="21" AND $sec_page=="04"): echo "active";endif;?>" href="index.php?section=01&amp;page=21&amp;sec_page=04">Import zásilek</a>

            </div>
        </div>
    </li> -->



<?php   break;}?>
