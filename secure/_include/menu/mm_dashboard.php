<div class="sidebar-heading">
    DASHBOARD
</div>
<?php
// 1 - main, 2 - vse krome system,
$user_prava = $_SESSION["user_prava"];
switch ($user_prava)
{
    case "1": //main přístupová práva?>
        <li class="nav-item active">
            <a class="nav-link" href="index.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
        </li>
<?php break;
    case "2": //přístupová práva na všechny akce kromě systémových?>
        <li class="nav-item active">
            <a class="nav-link" href="index.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
        </li>
 <?php break; } ?>
