<?php
include SEC_DIR."/_functions/fun_galerie.php";
global $pdo;
$lang = $_GET['lang'] ?? "cz";

$nazev_cz = $_POST['nazev_cz'] ?? ""; $perex_cz = $_POST['perex_cz'] ?? "";  $datum = $_POST['datum'] ?? "";
$news_typ = $_POST['news_typ'] ?? ""; $galerie_id = $_POST['galerie_id'] ?? 0; $visible = $_POST['visible'] ?? 0;
if(isset($_POST['editor'])):
    $text_cz = str_replace("\r\n",'', $_POST['editor']);
else:	$text_cz = "";		endif;

$add = $_POST['add'] ?? 0;
?>

<div class="card-body">
    <?php
    if($add == 0):
        ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="nazev_cz">Název novinky (<?php echo $lang; ?>)</label>
                    <input type="text" name="nazev_cz" id="nazev_cz" class="form-control text-left" value="<?php echo $nazev_cz; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="datum">Datum</label>
                    <input type="date" name="datum" id="datum" class="form-control text-left" value="<?php echo $datum; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="news_typ">Typ novinky</label>
                    <select name="news_typ" id="news_typ" class="custom-select">
                        <?php news_typ_option_form($pdo, $news_typ);?>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="galerie_id">ID galerie</label>
                    <input type="number" name="galerie_id" id="galerie_id" class="form-control text-left" value="<?php echo $galerie_id; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="perex_cz">Perex novinky (<?php echo $lang; ?>)</label>
                    <input type="text" name="perex_cz" id="perex_cz" class="form-control text-left" value="<?php echo $perex_cz; ?>" />
                </div>
                <div class="form-group col-md-3">
                    <label for="userfile">Obrázek novinky</label>
                    <input type="file" name="userfile" id="userfile" class="form-control text-left" />
                </div>
                <div class="form-group col-md-3">
                    <label for="visible">Zobrazit</label>
                    <select name="visible" id="visible" class="custom-select">
                        <option value="1" <?php if ($visible==1): echo 'selected="selected"'; endif; ?>>Ano</option>
                        <option value="2" <?php if ($visible==2): echo 'selected="selected"'; endif; ?>>Ano, pouze CZ</option>
                        <option value="3" <?php if ($visible==3): echo 'selected="selected"'; endif; ?>>Ano, pouze EN</option>
                        <option value="0" <?php if ($visible==0): echo 'selected="selected"'; endif; ?>>Ne</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="editor">Editor</label>
                    <textarea name="editor" id="editor" class="editor">
                        <?php echo htmlspecialchars($text_cz, ENT_COMPAT) ?>
                    </textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <input type="hidden" name="add" value="1" />
                    <label for="submit">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-primary">Vložit novinku</button>
                </div>
            </div>
        </form>

        <script src="/./secure/_scripts/ckeditor5/build/ckeditor.js"></script>
        <script src="/./secure/_scripts/ckfinder/ckfinder.js"></script>
        <script src="/./secure/_scripts/ckeditor_tm.js"></script>


    <?php
    elseif($add == 1):
        $news_maxid = news_maxid ($pdo);
        $soubor = news_photo_add ($pdo, $news_maxid);
        news_add ($pdo, $datum, $news_typ, $nazev_cz, $perex_cz, $text_cz, $galerie_id, $visible, $soubor);
    endif;
    ?>
</div>