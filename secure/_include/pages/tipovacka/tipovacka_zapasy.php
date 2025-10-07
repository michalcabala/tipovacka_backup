<?php
include SEC_DIR."/_functions/fun_tipovacka.php";
global $pdo;
$limit = $_GET['limit'] ?? sp_hodnota($pdo, 'limit_tipovacka-zapasy'); $valid = $_GET['valid'] ?? 1; $show = $_GET['show'] ?? 0;
if(isset($_GET["tipdefzero"])): $_SESSION["user_tipdef"] = 0; endif;
if(!isset($_SESSION["user_tipdef"])): $_SESSION["user_tipdef"] = sp_hodnota($pdo,'tipovacka_def_id'); endif;

$count = tipovacka_zapasy_count($pdo, $valid, $_SESSION["user_tipdef"]);
if ($limit == 0 OR $count <= $limit): $limit = $count; endif;
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Výpis zápasů tipovaček</h1>
    <a href="?section=01&amp;page=51&amp;sec_page=05&amp;show=1" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">přidat zápas
        <i class="fas fa-plus-circle fa-sm text-white-50"></i> </a>
    <a href="?section=01&amp;page=51&amp;sec_page=05&amp;tipdefzero=0" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm text-dark">zobrazit bez filtru tipovaček
        <i class="fas fa-circle-notch fa-sm text-black-50"></i> </a>
    <?php if ($_SESSION["user_prava"]==1):?>
        <a href="?section=01&amp;page=51&amp;sec_page=05&amp;limit=9999&amp;valid=0" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">zobrazit nevalidní záznamy
            <i class="fas fa-circle-notch fa-sm text-white-50"></i> </a>
    <?php endif; ?>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i> </a>
</div>

<!-- Page delete -->
<?php if(isset($_GET['del'])): tipovacka_zapasy_delete ($pdo, $_GET['del']); endif;?>

<!-- Page add -->
<?php if ($show == 1 OR $show == 11):?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary d-sm-inline">Přidání zápasu tipovačky</h6>
        </div>
        <?php
        if ($show == 11):
            echo '<div class="btn btn-success btn-icon-split w-25 text-left">
              <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Zápas byl vložen</span></div>';
            $_POST = array();
        else:
            include SEC_DIR."/_include/pages/tipovacka/tipovacka_zapasy_add.php";
        endif;
        ?>
    </div>
<?php endif;?>

<!-- Page edit -->
<?php if ($show == 2 OR $show == 21):?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success d-sm-inline">Editace zápasu tipovačky</h6>
        </div>
        <?php
        if ($show == 21):
            echo '<div class="btn btn-success btn-icon-split w-25 text-left">
              <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Zápas byl uložen</span></div>';
            $_POST = array();
            tipovacka_users_rel_prepocet ($pdo, $_SESSION["user_tipdef"]);
        else:
            include SEC_DIR."/_include/pages/tipovacka/tipovacka_zapasy_edit.php";
        endif;?>
    </div>
<?php endif;?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary d-sm-inline">Zápasy tipovačky</h6>
        <span class="d-none d-sm-inline-block">načteno <?php echo $limit;?> záznamů, defaultní tipovačka pro výpisy: <?php echo tipovacka_name($pdo, $_SESSION['user_tipdef']);?></span>
        <?php if (sp_hodnota($pdo, 'limit_tipovacka-zapasy') <= $count): ?>
            <a href="?section=01&amp;page=51&amp;sec_page=05&amp;limit=0" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                načíst všechny záznamy (<?php echo $count;?>)
                <i class="fas fa-circle-notch fa-sm text-white-50"></i>
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table data-order='[]' class="table table-striped table-hover table-bordered" id="dataTable">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Tipovačka</th>
                    <th>Poř.</th>
                    <th>Skup.</th>
                    <th>Zápas</th>
                    <th>Výsledek</th>
                    <th>Datum záp.</th>
                    <th>Datum tip.</th>
                    <th>Koef.</th>
                    <th>Tip</th>
                    <th>Upravit</th>
                    <th>Smazat</th>
                </tr>
                </thead>
                <tfoot class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Tipovačka</th>
                    <th>Poř.</th>
                    <th>Skup.</th>
                    <th>Zápas</th>
                    <th>Výsledek</th>
                    <th>Datum záp.</th>
                    <th>Datum tip.</th>
                    <th>Koef.</th>
                    <th>Tip</th>
                    <th>Upravit</th>
                    <th>Smazat</th>
                </tr>
                </tfoot>
                <tbody>
                <?php
                tipovacka_zapasy_vypis ($pdo, $limit, $valid, $_SESSION["user_tipdef"]);
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
