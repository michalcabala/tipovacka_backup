<?php
global $pdo;
$lang = $_GET['lang'] ?? "cz"; $edit = $_GET['edit'] ?? 0;

$nazev = $_POST['nazev'] ?? ""; $perex = $_POST['perex'] ?? ""; $url = $_POST['url'] ?? ""; $datum = $_POST['datum'] ?? ""; $blog_kat = $_POST['blog_kat'] ?? ""; $fav = $_POST['fav'] ?? 0;
$galerie_id = $_POST['galerie_id'] ?? 0;    $visible = $_POST['visible'] ?? 0;  $valid = $_POST['valid'] ?? 0;
if(isset($_POST['editor'])):
    $text = str_replace("\r\n",'', $_POST['editor']);
else:	$text = "";		endif;

$add = $_POST['add'] ?? 0;
?>

<div class="card-body">
    <?php
    if($add == 0):
        $sql = "SELECT * FROM blog WHERE id = :edit";
        $res = $pdo->prepare($sql);
        $res->execute(['edit'=>$edit]);
        $dev = $res->fetch();
        if ($lang == "cz"):
            $nazev = $dev["nazev_cz"]; $url = $dev["url_cz"]; $datum = $dev["datum"]; $perex = stripslashes($dev["perex_cz"]); $text = stripslashes($dev["text_cz"]);
        else:
            $nazev = $dev["nazev_en"]; $url = $dev["url_en"]; $datum = $dev["datum"]; $perex = stripslashes($dev["perex_en"]); $text = stripslashes($dev["text_en"]);
        endif;
        $galerie_id = $dev["galerie_id"]; $visible = $dev["visible"]; $blog_kat = $dev['blog_kat']; $fav = $dev['fav']; $valid = $dev["valid"];
        ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="nazev">Název novinky (<?php echo $lang; ?>)</label>
                    <input type="text" name="nazev" id="nazev" class="form-control text-left" value="<?php echo $nazev; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="datum">Datum</label>
                    <input type="date" name="datum" id="datum" class="form-control text-left" value="<?php echo $datum; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="blog_kat">Kategorie článku</label>
                    <select name="blog_kat" id="blog_kat" class="custom-select">
                        <?php blog_kat_option_form($pdo, $blog_kat);?>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="galerie_id">ID galerie</label>
                    <input type="number" name="galerie_id" id="galerie_id" class="form-control text-left" value="<?php echo $galerie_id; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="perex">Perex článku (<?php echo $lang; ?>)</label>
                    <input type="text" name="perex" id="perex" class="form-control text-left" value="<?php echo $perex; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="url">URL (<?php echo $lang;?>)</label>
                    <input type="text" name="url" id="url" class="form-control text-left" value="<?php echo $url; ?>" />
                </div>
                <div class="form-group col-md-1">
                    <label for="fav">Favourite</label>
                    <input type="checkbox" name="fav" id="fav" <?php if ($fav==1): echo "checked"; endif;?> value="1" class="form-control text-left" />
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
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="valid" id="valid" class="custom-control-input" value="1" <?php if ($valid==1): echo "checked"; endif;?> />
                    <label for="valid" class="custom-control-label">valid</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="editor">Editor</label>
                    <textarea name="editor" id="editor" class="editor">
                        <?php echo htmlspecialchars($text, ENT_COMPAT) ?>
                    </textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <input type="hidden" name="add" value="2" />
                    <label for="submit">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-primary">Upravit článek</button>
                </div>
            </div>
            <div class="col-md-12 small">
                Založeno: <?php echo format_datetime_www($dev['ts_i']); ?>;
                Založil: <?php echo $dev['user_i']; ?>;
                Upraveno: <?php echo format_datetime_www($dev['ts_u']); ?>;
                Upravil: <?php echo $dev['user_u']; ?>
            </div>
        </form>

        <script src="/./secure/_scripts/ckeditor5/build/ckeditor.js"></script>
        <script src="/./secure/_scripts/ckfinder/ckfinder.js"></script>
        <script src="/./secure/_scripts/ckeditor_tm.js"></script>


    <?php
    elseif($add == 2):
        blog_edit ($pdo, $edit, $datum, $blog_kat, $nazev, $perex, $text, $galerie_id, $visible, $fav, $lang, $url, $valid);
    endif;
    ?>
</div>