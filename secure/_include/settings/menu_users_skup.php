<?php
include SEC_DIR."/_functions/fun_system.php";
global $pdo;
$limit = $_GET['limit'] ?? sp_hodnota($pdo, 'limit_menu-vypis');
$valid = $_GET['valid'] ?? 1;   $show = $_GET['show'] ?? 0;     $skup_id = $_GET['skup_id'] ?? 0;

$count = menu_users_skup_count($pdo, $valid);
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Výpis práv skupiny uživatelů <?php echo users_skup_name ($pdo, $skup_id); ?> na menu</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i> </a>
</div>

<!-- Page delete -->
    <?php if(isset($_GET['del'])):  menu_users_skup_delete($pdo, $_GET['del'], $skup_id);  endif;    ?>

<!-- Page add -->
    <?php if(isset($_GET['add'])):  menu_users_skup_delete($pdo, $_GET['add'], $skup_id);  endif;    ?>


<!-- DataTables -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary d-sm-inline">Práva uživatelů na menu</h6>
        <span class="d-none d-sm-inline-block">načteno <?php echo $limit;?> záznamů</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table data-order='[[ 1, "asc", 2, "asc" ]]' class="table table-striped table-hover table-bordered" id="dataTable">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Menu</th>
                    <th>Název</th>
                    <th>URL</th>
                    <th>Přidat</th>
                    <th>Smazat</th>
                </tr>
                </thead>
                <tfoot class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Menu</th>
                    <th>Název</th>
                    <th>URL</th>
                    <th>Přidat</th>
                    <th>Smazat</th>
                </tr>
                </tfoot>
                <tbody>
                <?php
                menu_users_skup_vypis ($pdo, $skup_id, $limit, $valid);
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
