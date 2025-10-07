<?php
include SEC_DIR."/_functions/fun_tipovacka.php";
global $pdo;
$limit = $_GET['limit'] ?? sp_hodnota($pdo, 'limit_tipovacka-tips'); $valid = $_GET['valid'] ?? 1; $show = $_GET['show'] ?? 0; $dupl = $_GET['dupl'] ?? 0;
if(isset($_GET["tipdefzero"])): $_SESSION["user_tipdef"] = 0; endif;
if(!isset($_SESSION["user_tipdef"])): $_SESSION["user_tipdef"] = sp_hodnota($pdo,'tipovacka_def_id'); endif;

$count = tipovacka_tips_zapasy_count($pdo, $valid, $_SESSION["user_tipdef"]);
if ($limit == 0 OR $count <= $limit): $limit = $count; endif;

?>
<!-- News Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Výpis tipů zápasů tipovaček</h1>
    <a href="?section=01&amp;page=51&amp;sec_page=11&amp;tipdefzero=0" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm text-dark">zobrazit bez filtru tipovaček
        <i class="fas fa-circle-notch fa-sm text-black-50"></i> </a>
    <a href="?section=01&amp;page=51&amp;sec_page=11&amp;dupl=1" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm text-light">zobrazit duplicitní tipy
        <i class="fas fa-circle-notch fa-sm text-black-50"></i> </a>
    <?php if ($_SESSION["user_prava"]==1):?>
        <a href="?section=01&amp;page=51&amp;sec_page=11&amp;limit=9999&amp;valid=0" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">zobrazit nevalidní záznamy
            <i class="fas fa-circle-notch fa-sm text-white-50"></i> </a>
    <?php endif; ?>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i> </a>
</div>

<!-- Page delete -->
<?php if(isset($_GET['del'])): tipovacka_tips_zapasy_delete ($pdo, $_GET['del']); endif;?>
<!-- Page undelete -->
<?php if(isset($_GET['undel'])): tipovacka_tips_zapasy_undelete ($pdo, $_GET['undel']); endif;?>

<!-- DataTables Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary d-sm-inline">Tipy zápasů uživatelů tipovaček</h6>
        <span class="d-none d-sm-inline-block">načteno <?php echo $limit;?> záznamů, defaultní tipovačka pro výpisy: <?php echo tipovacka_name($pdo, $_SESSION['user_tipdef']);?></span>
        <?php if (sp_hodnota($pdo, 'limit_tipovacka-tips') <= $count): ?>
            <a href="?section=01&amp;page=51&amp;sec_page=11&amp;limit=0" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                načíst všechny záznamy (<?php echo $count;?>)
                <i class="fas fa-circle-notch fa-sm text-white-50"></i>
            </a>
        <?php endif; ?>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table data-order='[[ 2, "asc" ]]' class="table table-striped table-hover table-bordered" id="dataTable">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Tipovačka</th>
                    <th>Uživatel</th>
                    <th>Poř.</th>
                    <th>Skup.</th>
                    <th>Zápas</th>
                    <th>Tip výsl.</th>
                    <th>Výsledek</th>
                    <th>Tip</th>
                    <th>Body</th>
                    <th>Obnovit</th>
                    <th>Smazat</th>
                    <th><small>ts_u</small></th>
                </tr>
                </thead>
                <tfoot class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Tipovačka</th>
                    <th>Uživatel</th>
                    <th>Poř.</th>
                    <th>Skup.</th>
                    <th>Zápas</th>
                    <th>Tip výsl.</th>
                    <th>Výsledek</th>
                    <th>Tip</th>
                    <th>Body</th>
                    <th>Obnovit</th>
                    <th>Smazat</th>
                    <th><small>ts_u</small></th>
                </tr>
                </tfoot>
                <tbody>
                <?php
                tipovacka_tips_zapasy_vypis ($pdo, $limit, $valid, $_SESSION["user_tipdef"], $dupl);
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
