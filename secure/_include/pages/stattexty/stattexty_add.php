<?php
include SEC_DIR."/_functions/fun_galerie.php";
global $pdo;
$lang = $_GET['lang'] ?? "cz";

$nazev_cz = $_POST['nazev_cz'] ?? "";
$nazev_en = $_POST['nazev_en'] ?? "";
$cislo = $_POST['cislo'] ?? 0;
$galerie_id = $_POST['galerie_id'] ?? 0;
$col = $_POST['col'] ?? 12;
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
                <div class="form-group col-md-6">
                    <label for="nazev_cz">Název statického textu (<?php echo $lang; ?>)</label>
                    <input type="text" name="nazev_cz" id="nazev_cz" class="form-control text-left" value="<?php echo $nazev_cz; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="cislo">Číslo textu</label>
                    <input type="number" name="cislo" id="cislo" class="form-control text-left" value="<?php echo $cislo; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="galerie_id">ID galerie</label>
                    <input type="number" name="galerie_id" id="galerie_id" class="form-control text-left" value="<?php echo $galerie_id; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="col">Sloupců</label>
                    <input type="number" name="col" id="col" class="form-control text-left" value="<?php echo $col; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
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
                    <button type="submit" class="form-control btn btn-primary">Vložit statický text</button>
                </div>
            </div>
        </form>

        <script src="/./secure/_scripts/ckeditor5/build/ckeditor.js"></script>
        <script src="/./secure/_scripts/ckfinder/ckfinder.js"></script>
        <script src="/./secure/_scripts/ckeditor_tm.js"></script>


    <?php
    elseif($add == 1):
        stattexty_add ($pdo, $cislo, $nazev_cz, $text_cz, $galerie_id, $col);
    endif;
    ?>
</div>