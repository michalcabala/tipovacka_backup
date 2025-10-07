<?php global $section; global $page; global $sec_page;?>
<div class="sidebar-heading">
    Settings
</div>
<?php
// 1 - main, 2 - vse krome system,
$user_prava = $_SESSION["user_prava"];
switch ($user_prava)
{    case "1": //main přístupová práva        ?>
      <li class="nav-item <?php if($section=="02" AND $page=="01"): echo "active";endif;?>">
            <a class="nav-link collapsed pt-2 pb-2" href="#" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>Uživatelské účty</span>
            </a>
            <div id="collapseUsers" class="collapse <?php if($section=="02" AND $page=="01"): echo "show";endif;?>" aria-labelledby="headingUsers" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Uživatelské účty:</h6>
                    <a class="collapse-item py-1 <?php if($section=="02" AND $page=="01" AND $sec_page=="02"): echo "active";endif;?>" href="?section=02&amp;page=01&amp;sec_page=02">Výpis uživatelů</a>
                    <a class="collapse-item py-1 <?php if($section=="02" AND $page=="01" AND $sec_page=="01"): echo "active";endif;?>" href="?section=02&amp;page=01&amp;sec_page=02&amp;show=1">Přidat uživatele</a>
                    <a class="collapse-item py-1 <?php if($section=="02" AND $page=="01" AND $sec_page=="03"): echo "active";endif;?>" href="?section=02&amp;page=01&amp;sec_page=03">Skupiny uživatelů</a>
                    <a class="collapse-item py-1 <?php if($section=="02" AND $page=="01" AND $sec_page=="05"): echo "active";endif;?>" href="?section=02&amp;page=01&amp;sec_page=05">Log přihlášení</a>
                </div>
            </div>
        </li>
        <li class="nav-item <?php if($section=="02" AND $page=="02"): echo "active";endif;?>">
            <a class="nav-link collapsed pt-2 pb-2" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>Systémové proměnné</span>
            </a>
            <div id="collapseTwo" class="collapse <?php if($section=="02" AND $page=="02"): echo "show";endif;?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Systémové proměnné:</h6>
                    <a class="collapse-item py-1 <?php if($section=="02" AND $page=="02" AND $sec_page=="02"): echo "active";endif;?>" href="?section=02&amp;page=02&amp;sec_page=02">Výpis proměnných</a>
                    <a class="collapse-item py-1 <?php if($section=="02" AND $page=="02" AND $sec_page=="01"): echo "active";endif;?>" href="?section=02&amp;page=02&amp;sec_page=02&amp;show=1">Přidat proměnnou</a>
                    <a class="collapse-item py-1 <?php if($section=="02" AND $page=="02" AND $sec_page=="03"): echo "active";endif;?>" href="?section=02&amp;page=02&amp;sec_page=03">Výpis menu</a>
                    <a class="collapse-item py-1 <?php if($section=="02" AND $page=="02" AND $sec_page=="04"): echo "active";endif;?>" href="?section=02&amp;page=02&amp;sec_page=04">Práva na menu</a>
                </div>
            </div>
        </li>
<?php
    break;
    case "2": //přístupová práva na všechny akce kromě systémových?>
        <li class="nav-item <?php if($section=="02" AND $page=="01"): echo "active";endif;?>">
            <a class="nav-link collapsed pt-2 pb-2" href="#" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>Uživatelské účty</span>
            </a>
            <div id="collapseUsers" class="collapse <?php if($section=="02" AND $page=="01"): echo "show";endif;?>" aria-labelledby="headingUsers" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Uživatelské účty:</h6>
                    <span class="collapse-item py-1 <?php if($section=="02" AND $page=="01" AND $sec_page=="02"): echo "active";endif;?>" title="Nemáš oprávnění">Výpis uživatelů</span>
                    <span class="collapse-item py-1 <?php if($section=="02" AND $page=="01" AND $sec_page=="01"): echo "active";endif;?>" title="Nemáš oprávnění">Přidat uživatele</span>
                    <span class="collapse-item py-1 <?php if($section=="02" AND $page=="01" AND $sec_page=="03"): echo "active";endif;?>" title="Nemáš oprávnění">Skupiny uživatelů</span>
                    <span class="collapse-item py-1 <?php if($section=="02" AND $page=="01" AND $sec_page=="05"): echo "active";endif;?>" title="Nemáš oprávnění">Log přihlášení</span>
                </div>
            </div>
        </li>
        <li class="nav-item <?php if($section=="02" AND $page=="02"): echo "active";endif;?>">
            <a class="nav-link collapsed pt-2 pb-2" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>Systémové proměnné</span>
            </a>
            <div id="collapseTwo" class="collapse <?php if($section=="02" AND $page=="02"): echo "show";endif;?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Systémové proměnné:</h6>
                    <span class="collapse-item py-1 <?php if($section=="02" AND $page=="02" AND $sec_page=="02"): echo "active";endif;?>" title="Nemáš oprávnění">Výpis proměnných</span>
                    <span class="collapse-item py-1 <?php if($section=="02" AND $page=="02" AND $sec_page=="01"): echo "active";endif;?>" title="Nemáš oprávnění">Přidat proměnnou</span>
                </div>
            </div>
        </li>
<?php   break;}?>
