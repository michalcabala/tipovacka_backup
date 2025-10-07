<?php
include SEC_DIR."/_functions/fun_system.php";
global $pdo;
$limit = $_GET['limit'] ?? sp_hodnota($pdo, 'limit_menu-vypis');
$valid = $_GET['valid'] ?? 1;   $show = $_GET['show'] ?? 0;

$count = menu_count($pdo, $valid);
if ($limit == 0 OR $count <= $limit): $limit = $count; endif;

?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Výpis menu hlavního webu</h1>
    <a href="?section=02&amp;page=02&amp;sec_page=03&amp;show=1" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">přidat menu hlavního webu
        <i class="fas fa-plus-circle fa-sm text-white-50"></i> </a>
    <?php if ($_SESSION["user_prava"]==1):?>
    <a href="?section=02&amp;page=02&amp;sec_page=03&amp;limit=9999&amp;valid=0" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">zobrazit nevalidní záznamy
        <i class="fas fa-circle-notch fa-sm text-white-50"></i> </a>
    <?php endif; ?>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i> </a>
</div>

<!-- Page delete -->
<?php if(isset($_GET['del'])): menu_delete ($pdo, $_GET['del']); endif;?>

<!-- Page add -->
<?php if ($show == 1 OR $show == 11):?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary d-sm-inline">Přidání menu hlavního webu</h6>
    </div>
    <?php
    if ($show == 11):
        echo '<div class="btn btn-success btn-icon-split w-25 text-left">
              <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Menu bylo vloženo</span></div>';
    endif;
    include SEC_DIR."/_include/settings/menu_add.php" ?>
</div>
<?php endif;?>

<!-- Page edit -->
<?php if ($show == 2 OR $show == 21):?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success d-sm-inline">Editace menu hlavního webu</h6>
    </div>
    <?php
    if ($show == 21):
        echo '<div class="btn btn-success btn-icon-split w-25 text-left">
              <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Menu bylo uloženo</span></div>';
    else:
        include SEC_DIR."/_include/settings/menu_edit.php";
    endif;?>
</div>
<?php endif;?>

<!-- DataTables -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary d-sm-inline">Menu hlavního webu</h6>
        <span class="d-none d-sm-inline-block">načteno <?php echo $limit;?> záznamů</span>
        <?php if (sp_hodnota($pdo,'limit_menu-vypis') <= $count): ?>
            <a href="?section=02&amp;page=02&amp;sec_page=03&amp;limit=0" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                načíst všechny záznamy (<?php echo $count;?>)
                <i class="fas fa-circle-notch fa-sm text-white-50"></i>
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table data-order='[[ 1, "asc", 2, "asc" ]]' class="table table-striped table-hover table-bordered" id="dataTable">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Menu</th>
                    <th>URL</th>
                    <th>Název</th>
                    <th>Upravit</th>
                    <th>Smazat</th>
                </tr>
                </thead>
                <tfoot class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Menu</th>
                    <th>URL</th>
                    <th>Název</th>
                    <th>Upravit</th>
                    <th>Smazat</th>
                </tr>
                </tfoot>
                <tbody>
                <?php
                menu_vypis ($pdo, $limit, $valid);
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
