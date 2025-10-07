<?php
include SEC_DIR."/_functions/fun_galerie.php";
global $pdo;
$lang = $_GET['lang'] ?? "cz"; $galerie_id = $_GET['photo'] ?? 0; $deltemp = $_GET['deltemp'] ?? 0; $deldupl = $_GET['deldupl'] ?? 0; $sort = $_GET['sort'] ?? 0;
$move = $_GET['move'] ?? 0;

$dir_temp = SEC_DIR."/_include/pages/galerie/temp/";
$add = $_POST['add'] ?? 0;
$tempfiles = count(glob(SEC_DIR."/_include/pages/galerie/temp/" . "*"));

if ($deltemp == 1):
    foreach(glob("{$dir_temp}/*") as $file)
    {
        if(is_dir($file)): recursiveRemoveDirectory($file); else:   unlink($file);  endif;
    }
    galerie_temp_photo_delete ($pdo, 999999);
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
    echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
elseif($deltemp == 11):
        echo '<div class="btn btn-success btn-icon-split w-25 text-left">
              <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Dočasné soubory byly smazány</span></div>';
endif;



?>

<div class="card-body">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Přidání fotografií do galerie "<?php echo galerie_name($pdo, $galerie_id);?>"</h1>
        <a href="?section=01&amp;page=03&amp;sec_page=06&amp;photo=<?php echo $galerie_id;?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">obnovit data
            <i class="fas fa-plus-circle fa-sm text-white-50"></i> </a>
    </div>
    <?php
    if ($move == 1): galerie_photo_move ($pdo, $galerie_id);endif;
    if ($sort == 1): galerie_photo_poradi_update ($pdo, $galerie_id);endif;
    if ($deldupl == 1): galerie_photo_duplicity_delete ($pdo, $galerie_id);endif;
    ?>

    <div class="container">
        <!-- HTML heavily inspired by https://blueimp.github.io/jQuery-File-Upload/ -->
        <div id="actions" class="row">
            <div class="col-lg-12 mb-2">
                <a href="?section=01&amp;page=03&amp;sec_page=06&amp;photo=<?php echo $galerie_id;?>&amp;deltemp=1" class="btn btn-danger">
                    <i class="fas fa-fw fa-tag"></i>
                    <span>Vymazat temp (<?php echo $tempfiles;?>)</span>
                </a>
                <a href="?section=01&amp;page=03&amp;sec_page=06&amp;photo=<?php echo $galerie_id;?>&amp;move=1" class="btn btn-danger">
                    <i class="fas fa-fw fa-forward"></i>
                    <span>Přesun temp>galerie</span>
                </a>
                <a href="?section=01&amp;page=03&amp;sec_page=06&amp;photo=<?php echo $galerie_id;?>&amp;sort=1" class="btn btn-danger">
                    <i class="fas fa-fw fa-sort"></i>
                    <span>Aktualizovat pořadí</span>
                </a>
                <a href="?section=01&amp;page=03&amp;sec_page=06&amp;photo=<?php echo $galerie_id;?>&amp;deldupl=1" class="btn btn-danger">
                    <i class="fas fa-fw fa-eraser"></i>
                    <span>Odstranění duplicit</span>
                </a>
            </div>
            <div class="col-lg-12">
                <!-- The fileinput-button span is used to style the file input field as button -->

                <span class="btn btn-success fileinput-button dz-clickable">
                    <i class="fas fa-fw fa-plus"></i>
                    <span>Přidej foto...</span>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="fas fa-fw fa-upload"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="fas fa-fw fa-circle"></i>
                    <span>Cancel upload</span>
                </button>
            </div>

            <div class="col-lg-12">
                <!-- The global file processing state -->
                <span class="fileupload-process">
                    <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <div class="progress-bar progress-bar-success" style="width:0;" data-dz-uploadprogress=""></div>
                    </div>
                </span>
            </div>
        </div>

        <div class="table table-striped files" id="previews">
            <div id="template" class="file-row dz-image-preview">
                <!-- This is used as the file preview template -->
                <div>
                    <span class="preview"><img src="" alt="" data-dz-thumbnail></span>
                </div>
                <div>
                    <p class="name" data-dz-name></p>
                    <strong class="error text-danger" data-dz-errormessage></strong>
                </div>
                <div>
                    <p class="size" data-dz-size></p>
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <div class="progress-bar progress-bar-success" style="width:0;" data-dz-uploadprogress></div>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary start">
                        <i class="glyphicon glyphicon-upload"></i>
                        <span>Start</span>
                    </button>
                    <button data-dz-remove class="btn btn-warning cancel">
                        <i class="glyphicon glyphicon-ban-circle"></i>
                        <span>Cancel</span>
                    </button>
                    <button data-dz-remove class="btn btn-danger delete">
                        <i class="glyphicon glyphicon-trash"></i>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        </div>


</div>