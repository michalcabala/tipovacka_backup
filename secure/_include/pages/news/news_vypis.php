<?php
include SEC_DIR."/_functions/fun_news.php";
global $pdo;
$limit = $_GET['limit'] ?? sp_hodnota($pdo, 'limit_news-vypis'); $valid = $_GET['valid'] ?? 1; $show = $_GET['show'] ?? 0;
$count = news_count($pdo, $valid);
if ($limit == 0 OR $count <= $limit): $limit = $count; endif;

?>
<!-- News Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Výpis novinek</h1>
    <a href="?section=01&amp;page=01&amp;sec_page=02&amp;show=1" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">přidat novinku
        <i class="fas fa-plus-circle fa-sm text-white-50"></i> </a>
    <?php if ($_SESSION["user_prava"]==1):?>
        <a href="?section=01&amp;page=01&amp;sec_page=02&amp;limit=9999&amp;valid=0" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">zobrazit nevalidní záznamy
            <i class="fas fa-circle-notch fa-sm text-white-50"></i> </a>
    <?php endif; ?>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i> </a>
</div>

<!-- News delete -->
<?php if(isset($_GET['del'])): news_delete ($pdo, $_GET['del']); endif;?>
<!-- News ico delete -->
<?php if(isset($_GET['icon'])): news_ico_delete($pdo, $_GET['icon']); endif;?>

<!-- News add -->
<?php if ($show == 1 OR $show == 11):?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary d-sm-inline">Přidání novinky</h6>
        </div>
        <?php
        if ($show == 11):
            echo '<div class="btn btn-success btn-icon-split w-25 text-left">
              <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Novinka byla vložena</span></div>';
        endif;
        include SEC_DIR."/_include/pages/news/news_add.php" ?>
    </div>
<?php endif;?>

<!-- News edit -->
<?php if ($show == 2 OR $show == 21):?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success d-sm-inline">Editace novinky</h6>
        </div>
        <?php
        if ($show == 21):
            echo '<div class="btn btn-success btn-icon-split w-25 text-left">
              <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Novinka byla uložena</span></div>';
        else:
            include SEC_DIR."/_include/pages/news/news_edit.php";
        endif;?>
    </div>
<?php endif;?>

<!-- DataTables Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary d-sm-inline">Novinky</h6>
        <span class="d-none d-sm-inline-block">načteno <?php echo $limit;?> záznamů</span>
        <?php if (sp_hodnota($pdo, 'limit_news-vypis') <= $count): ?>
            <a href="?section=01&amp;page=01&amp;sec_page=02&amp;limit=0" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
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
                    <th>Typ</th>
                    <th>Název novinky</th>
                    <th>Datum</th>
                    <th>Ikona</th>
                    <th>Galerie</th>
                    <th>View</th>
                    <th>Send</th>
                    <th>Náhled</th>
                    <th><?php if(en_on($pdo) == 1): ?>EN<?php endif; ?></th>
                    <th>Upravit</th>
                    <th>Send</th>
                    <th>Ikona Del</th>
                    <th>Smazat</th>
                </tr>
                </thead>
                <tfoot class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Typ</th>
                    <th>Název novinky</th>
                    <th>Datum</th>
                    <th>Ikona</th>
                    <th>Galerie</th>
                    <th>View</th>
                    <th>Send</th>
                    <th>Náhled</th>
                    <th><?php if(en_on($pdo) == 1): ?>EN<?php endif; ?></th>
                    <th>Upravit</th>
                    <th>Send</th>
                    <th>Ikona Del</th>
                    <th>Smazat</th>
                </tr>
                </tfoot>
                <tbody>
                <?php
                news_vypis ($pdo, $limit, $valid);
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
