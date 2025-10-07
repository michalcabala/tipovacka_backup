<?php
include SEC_DIR."/_functions/fun_galerie.php";
global $pdo;
$lang = $_GET['lang'] ?? "cz";      $edit = $_GET['edit'] ?? 0;
$nazev = $_POST['nazev'] ?? "";     $cislo = $_POST['cislo'] ?? 0;      $galerie_id = $_POST['galerie_id'] ?? 0;
$col = $_POST['col'] ?? 12;         $valid = $_POST['valid'] ?? 0;      $add = $_POST['add'] ?? 0;
if(isset($_POST['editor'])):
    $text = str_replace("\r\n",'', $_POST['editor']);
else:	$text = "";		endif;

?>

<div class="card-body">
    <?php
    if($add == 0):
        $sql = "SELECT * FROM stat_texty WHERE id = :edit";
        $res = $pdo->prepare($sql);
        $res->execute(['edit'=>$edit]);
        $dev = $res->fetch();
        if ($lang == "cz"):
            $nazev = $dev["nazev_cz"]; $cislo = $dev["cislo"]; $text = stripslashes($dev["text_cz"]);
        else:
            $nazev = $dev["nazev_en"]; $cislo = $dev["cislo"]; $text = stripslashes($dev["text_en"]);
        endif;
        $galerie_id = $dev["galerie_id"]; $col = $dev["col"]; $valid = $dev["valid"];
        ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nazev">Název statického textu (<?php echo $lang; ?>)</label>
                    <input type="text" name="nazev" id="nazev" class="form-control text-left" value="<?php echo $nazev; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="cislo">Číslo textu</label>
                    <input type="number" name="cislo" id="cislo" class="form-control text-left" value="<?php echo $cislo; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="galerie_id">ID galerie</label>
                    <input type="number" name="galerie_id" id="galerie_id" class="form-control text-left" value="<?php echo $galerie_id; ?>" />
                </div>
                <div class="form-group col-md-1">
                    <label for="col">Sloupců</label>
                    <input type="number" name="col" id="col" class="form-control text-left" value="<?php echo $col; ?>" />
                </div>
            </div>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="valid" id="valid" class="custom-control-input" value="1" <?php if ($valid==1): echo "checked"; endif;?> />
                <label for="valid" class="custom-control-label">valid</label>
            </div>
            <div class="form-row">
                <div class="form-group col-md-<?php echo $col;?>">
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
                    <button type="submit" class="form-control btn btn-primary">Upravit statický text</button>
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
        stattexty_edit ($pdo, $edit, $cislo, $nazev, $text, $galerie_id, $col, $lang, $valid);
    endif;
    ?>
</div>