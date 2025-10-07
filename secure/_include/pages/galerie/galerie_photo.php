<?php
include SEC_DIR."/_functions/fun_galerie.php";
global $pdo;
$lang = $_GET['lang'] ?? "cz"; $view = $_GET['view'] ?? 0; $del = $_GET['del'] ?? 0; $show = $_GET['show'] ?? 0; $edit = $_GET['edit'] ?? 0;

$sql1 = "SELECT * FROM galerie WHERE id = :view";
$res1 = $pdo->prepare($sql1);
$res1->execute(['view'=>$view]);
$dev1 = $res1->fetch();
?>

<div class="card-body">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Výpis fotografií galerie </h1>
        <a href="?section=01&amp;page=03&amp;sec_page=05&amp;view=<?php echo $view;?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">obnovit data
            <i class="fas fa-plus-circle fa-sm text-white-50"></i> </a>
    </div>
    <!-- Galerie photo delete -->
    <?php if(isset($_GET['del'])): galerie_photo_delete ($pdo, $_GET['del'], $view); endif;?>

    <!-- Galerie photo edit -->
    <?php if ($show == 2 OR $show == 21):?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success d-sm-inline">Editace fotografie galerie</h6>
            </div>
            <?php
            if ($show == 21):
                echo '<div class="btn btn-success btn-icon-split w-25 text-left">
              <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Fotografie byla uložena</span></div>';
            else:
                include SEC_DIR."/_include/pages/galerie/galerie_photo_edit.php";
            endif;?>
        </div>
    <?php endif;?>

    <div class="container-fluid">
        <h1 class="fw-light text-center text-lg-start mt-2 mb-0">PhotoGallery "<?php echo $dev1['nazev_cz'];?>"</h1>
        <p>Popis galerie: <?php echo $dev1['popis_cz'];?></p>
        <hr class="mt-2 mb-2">
            <div class="img-container-grid">

            <?php galerie_view($pdo, $view);?>
            </div>
    </div>
</div>

