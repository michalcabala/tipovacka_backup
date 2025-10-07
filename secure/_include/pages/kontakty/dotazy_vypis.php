<?php
include SEC_DIR."/_functions/fun_kontakty.php";
global $pdo;
$limit = $_GET['limit'] ?? sp_hodnota($pdo, 'limit_dotazy-vypis'); $valid = $_GET['valid'] ?? 1; $show = $_GET['show'] ?? 0;
$count = dotazy_count($pdo, $valid);
if ($limit == 0 OR $count <= $limit): $limit = $count; endif;

?>
<!-- News Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Výpis dotazů z webu</h1>
    <?php if ($_SESSION["user_prava"]==1):?>
        <a href="?section=01&amp;page=09&amp;sec_page=52&amp;limit=9999&amp;valid=0" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">zobrazit nevalidní záznamy
            <i class="fas fa-circle-notch fa-sm text-white-50"></i> </a>
    <?php endif; ?>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i> </a>
</div>

<!-- Dotazy delete -->
<?php if(isset($_GET['del'])): dotazy_delete ($pdo, $_GET['del']); endif;?>

<!-- DataTables Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary d-sm-inline">Dotazy z webu</h6>
        <span class="d-none d-sm-inline-block">načteno <?php echo $limit;?> záznamů</span>
        <?php if (sp_hodnota($pdo, 'limit_dotazy-vypis') <= $count): ?>
            <a href="?section=01&amp;page=51&amp;sec_page=02&amp;limit=0" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                načíst všechny záznamy (<?php echo $count;?>)
                <i class="fas fa-circle-notch fa-sm text-white-50"></i>
            </a>
        <?php endif; ?>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table data-order='[[ 0, "desc" ]]' class="table table-striped table-hover table-bordered" id="dataTable">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Datum</th>
                    <th>Kategorie</th>
                    <th>Jméno</th>
                    <th>Detail</th>
                    <th>Email</th>
                    <th>Mobil</th>
                    <th>Smazat</th>
                </tr>
                </thead>
                <tfoot class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Datum</th>
                    <th>Kategorie</th>
                    <th>Jméno</th>
                    <th>Detail</th>
                    <th>Email</th>
                    <th>Mobil</th>
                    <th>Smazat</th>
                </tr>
                </tfoot>
                <tbody>
                <?php
                dotazy_vypis ($pdo, $limit, $valid);
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

