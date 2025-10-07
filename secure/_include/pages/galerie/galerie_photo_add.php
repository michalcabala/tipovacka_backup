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
        <!--begin::Form-->
        <form class="form" action="#" method="post">
            <!--begin::Input group-->
            <div class="form-group row">
                <!--begin::Label-->
                <label class="col-lg-2 col-form-label text-lg-right">Upload Files:</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-10">
                    <!--begin::Dropzone-->
                    <div class="dropzone dropzone-queue mb-2" id="dropzone_galerie_photo_add">
                        <!--begin::Controls-->
                        <div class="dropzone-panel mb-lg-0 mb-2">
                            <a class="dropzone-select btn btn-sm btn-primary me-2">Attach files</a>
                            <a class="dropzone-upload btn btn-sm btn-light-primary me-2">Upload All</a>
                            <a class="dropzone-remove-all btn btn-sm btn-light-primary">Remove All</a>
                        </div>
                        <!--end::Controls-->

                        <!--begin::Items-->
                        <div class="dropzone-items wm-200px">
                            <div class="dropzone-item" style="display:none">
                                <!--begin::File-->
                                <div class="dropzone-file">
                                    <div class="dropzone-filename" title="some_image_file_name.jpg">
                                        <span data-dz-name>some_image_file_name.jpg</span>
                                        <strong>(<span data-dz-size>340kb</span>)</strong>
                                    </div>

                                    <div class="dropzone-error" data-dz-errormessage></div>
                                </div>
                                <!--end::File-->

                                <!--begin::Progress-->
                                <div class="dropzone-progress">
                                    <div class="progress">
                                        <div
                                                class="progress-bar bg-primary"
                                                role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Progress-->

                                <!--begin::Toolbar-->
                                <div class="dropzone-toolbar">
                                    <span class="dropzone-start"><i class="bi bi-play-fill fs-3"></i></span>
                                    <span class="dropzone-cancel" data-dz-remove style="display: none;"><i class="bi bi-x fs-3"></i></span>
                                    <span class="dropzone-delete" data-dz-remove><i class="bi bi-x fs-1"></i></span>
                                </div>
                                <!--end::Toolbar-->
                            </div>
                        </div>
                        <!--end::Items-->
                    </div>
                    <!--end::Dropzone-->

                    <!--begin::Hint-->
                    <span class="form-text text-muted">Max file size is 5MB and max number of files is 1000.</span>
                    <!--end::Hint-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
        </form>
        <!--end::Form-->
    </div>

</div>