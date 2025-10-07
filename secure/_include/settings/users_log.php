<?php
include SEC_DIR."/_functions/fun_system.php";
global $pdo;
$limit = $_GET['limit'] ?? sp_hodnota($pdo, 'limit_users-log');
$count = users_log_count($pdo);
if ($limit == 0 OR $count <= $limit): $limit = $count; endif;
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Výpis logu přihlášení do administrace</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i> </a>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary d-sm-inline">Log přihlášení</h6>
        <span class="d-none d-sm-inline-block">načteno <?php echo $limit;?> záznamů</span>
        <?php if (sp_hodnota($pdo,'limit_users-log') <= $count): ?>
        <a href="?section=02&amp;page=01&amp;sec_page=03&amp;limit=0" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
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
                    <th>User</th>
                    <th>Skupina</th>
                    <th>IP</th>
                    <th>Date</th>
                    <th>Web</th>
                </tr>
                </thead>
                <tfoot class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Skupina</th>
                    <th>IP</th>
                    <th>Date</th>
                    <th>Web</th>
                </tr>
                </tfoot>
                <tbody>
<?php 
users_log_vypis ($pdo, $limit);
?>
                </tbody>
            </table>
        </div>
    </div>
</div>
