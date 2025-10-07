<?php global $section; global $page; global $sec_page;?>
<div class="sidebar-heading">
    Projektové menu
</div>
<?php
// 1 - main, 2 - vse krome system,
$user_prava = $_SESSION["user_prava"];
switch ($user_prava)
{    case "1" OR "2": //main přístupová práva        ?>
    <!-- MENU CENIK -->
    <li class="nav-item <?php if($section=="01" AND $page=="51"): echo "active";endif;?>">
        <a class="nav-link collapsed pt-2 pb-2" href="#" data-toggle="collapse" data-target="#collapseTipovacky" aria-expanded="true" aria-controls="collapseTipovacky">
            <i class="fas fa-fw fa-donate"></i>
            <span>Tipovačky</span>
        </a>
        <div id="collapseTipovacky" class="collapse <?php if($section=="01" AND $page=="51"): echo "show";endif;?>" aria-labelledby="headingTipovacky" data-parent="#accordionSidebar">
            <div class="bg-white py-1 collapse-inner rounded">
                <h6 class="collapse-header">Tipovačky:</h6>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="51" AND $sec_page=="02"): echo "active";endif;?>" href="?section=01&amp;page=51&amp;sec_page=02">Výpis tipovaček</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="51" AND $sec_page=="03"): echo "active";endif;?>" href="?section=01&amp;page=51&amp;sec_page=03">Výpis týmů</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="51" AND $sec_page=="04"): echo "active";endif;?>" href="?section=01&amp;page=51&amp;sec_page=04">Výpis uživatelů</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="51" AND $sec_page=="05"): echo "active";endif;?>" href="?section=01&amp;page=51&amp;sec_page=05">Výpis zápasů</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="51" AND $sec_page=="06"): echo "active";endif;?>" href="?section=01&amp;page=51&amp;sec_page=06">Uživatelé tipovačky</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="51" AND $sec_page=="11"): echo "active";endif;?>" href="?section=01&amp;page=51&amp;sec_page=11">Seznam tipů zápasů</a>
                <a class="collapse-item py-1 <?php if($section=="01" AND $page=="51" AND $sec_page=="12"): echo "active";endif;?>" href="?section=01&amp;page=51&amp;sec_page=12">Seznam tipů pořadí</a>

            </div>
        </div>
    </li>


<?php   break;}?>
