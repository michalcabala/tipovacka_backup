<?php
global $pdo;
include SEC_DIR."/_functions/fun_galerie.php";
$edit = $_GET['edit'] ?? "";
$nazev_cz = $_POST['nazev_cz'] ?? "";       $nazev_en = $_POST['nazev_en'] ?? ""; $aktivni = $_POST['aktivni'] ?? 0;
$popis_cz = $_POST['popis_cz'] ?? "";       $popis_en = $_POST['popis_en'] ?? ""; $tip_zapasy_remizy = $_POST['tip_zapasy_remizy'] ?? 0;
$datum_od = $_POST['datum_od'] ?? "";       $datum_do = $_POST['datum_do'] ?? ""; $datum_do_poradi = $_POST['datum_do_poradi'] ?? "";
$tip_zapasy = $_POST['tip_zapasy'] ?? 0;    $tip_poradi = $_POST['tip_poradi'] ?? 0; $tip_otazky = $_POST['tip_otazky'] ?? 0; $news_id = $_POST['news_id'] ?? ''; $url = $_POST['url'] ?? '';
$valid = $_POST['valid'] ?? 0;  $add = $_POST['add'] ?? 0;
?>

<div class="card-body">
    <?php
    if($add == 0):
        $sql = "SELECT * FROM zdef_tipovacka WHERE id = :id";
        $res = $pdo->prepare($sql);
        $res->execute(['id'=>$edit]);
        $dev = $res->fetch();
        $nazev_cz = $dev["nazev_cz"]; $nazev_en = $dev["nazev_en"]; $popis_cz = $dev["popis_cz"]; $popis_en = $dev["popis_en"]; $datum_od = $dev["datum_od"];
        $datum_do = $dev["datum_do"]; $datum_do_poradi = $dev["datum_do_poradi"]; $tip_zapasy = $dev["tip_zapasy"]; $tip_poradi = $dev["tip_poradi"]; $tip_otazky = $dev["tip_otazky"];
        $tip_zapasy_remizy = $dev["tip_zapasy_remizy"]; $valid = $dev["valid"]; $image = $dev["image"]; $url = $dev["url"]; $news_id = $dev["news_id"]; $aktivni = $dev["aktivni"];
        ?>
    <form method="post" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="nazev_cz">Název (cz)</label>
                <input type="text" name="nazev_cz" id="nazev_cz" class="form-control text-left" value="<?php echo $nazev_cz; ?>" />
            </div>
            <div class="form-group col-md-5">
                <label for="popis_cz">Popis tipovačky (cz) </label>
                <input type="text" name="popis_cz" id="popis_cz" class="form-control text-left" value="<?php echo $popis_cz; ?>" />
            </div>
            <div class="form-group col-md-2">
                <label for="url" class="block">URL</label>
                <input type="text" name="url" id="url" class="form-control text-left" value="<?php echo $url; ?>" />
            </div>
            <div class="form-group col-md-2">
                <label for="news_id">ID připojené novinky</label>
                <input type="number" name="news_id" id="news_id" class="form-control text-left" value="<?php echo $news_id; ?>" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="datum_od">Datum od</label>
                <input type="date" name="datum_od" id="datum_od" class="form-control text-left" value="<?php echo $datum_od; ?>" />
            </div>
            <div class="form-group col-md-2">
                <label for="datum_do">Datum do</label>
                <input type="date" name="datum_do" id="datum_do" class="form-control text-left" value="<?php echo $datum_do; ?>" />
            </div>
            <div class="form-group col-md-2">
                <label for="datum_do_poradi">Datum do (pořadí)</label>
                <input type="date" name="datum_do_poradi" id="datum_do_poradi" class="form-control text-left" value="<?php echo $datum_do_poradi; ?>" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="aktivni">Aktivní</label>
                <input type="checkbox" name="aktivni" id="aktivni" <?php if ($aktivni==1): echo "checked"; endif;?>  value="1" class="form-control text-left" />
            </div>
            <div class="form-group col-md-2">
                <label for="tip_zapasy">Tipovat zápasy</label>
                <input type="checkbox" name="tip_zapasy" id="tip_zapasy" <?php if ($tip_zapasy==1): echo "checked"; endif;?>  value="1" class="form-control text-left" />
            </div>
            <div class="form-group col-md-2">
                <label for="tip_zapasy_remizy">Povolit remízy</label>
                <input type="checkbox" name="tip_zapasy_remizy" id="tip_zapasy_remizy" <?php if ($tip_zapasy_remizy==1): echo "checked"; endif;?>  value="1" class="form-control text-left" />
            </div>
            <div class="form-group col-md-2">
                <label for="tip_poradi">Tipovat pořadí</label>
                <input type="checkbox" name="tip_poradi" id="tip_poradi" <?php if ($tip_poradi==1): echo "checked"; endif;?>  value="1" class="form-control text-left" />
            </div>
            <div class="form-group col-md-2">
                <label for="tip_otazky">Tipovat otázky</label>
                <input type="checkbox" name="tip_otazky" id="tip_otazky" <?php if ($tip_otazky==1): echo "checked"; endif;?>  value="1" class="form-control text-left" />
            </div>
        </div>
        <div class="form-row">
            <?php if (en_on($pdo)==1):?>
                <div class="form-group col-md-3">
                    <label for="nazev_en">Název (en)</label>
                    <input type="text" name="nazev_en" id="nazev_en" class="form-control text-left" value="<?php echo $nazev_en; ?>" />
                </div>
                <div class="form-group col-md-9">
                    <label for="popis_en">Popis (en)</label>
                    <input type="text" name="popis_en" id="popis_en" class="form-control text-left" value="<?php echo $popis_en; ?>" />
                </div>
            <?php endif;?>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="userfile">Obrázek tipovačky (3:2, min 200px)</label>
                <input type="file" name="userfile" id="userfile" class="form-control text-left" />
            </div>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="valid" id="valid" class="custom-control-input" value="1" <?php if ($valid==1): echo "checked"; endif;?> />
                <label for="valid" class="custom-control-label">valid</label>
            </div>
            <div class="form-group col-md-2">
                <input type="hidden" name="add" value="2" />
                <label for="submit">&nbsp;</label>
                <button type="submit" class="form-control btn btn-primary">Uložit tipovačku</button>
            </div>
            <?php
            if($dev['image']==""):
                echo "Obrázek není připojen";
            else:
                $file = '<img src="../files/images/tipovacka/small/'.$image.'" />';
                echo $file;
            endif;
            ?>
            <div class="col-md-12 small">
                Založeno: <?php echo format_datetime_www($dev['ts_i']); ?>;
                Založil: <?php echo $dev['user_i']; ?>;
                Upraveno: <?php echo format_datetime_www($dev['ts_u']); ?>;
                Upravil: <?php echo $dev['user_u']; ?>
            </div>
        </div>
    </form>
    <?php
    elseif($add == 2):
        $soubor_str = tipovacka_image_add($edit);
        try {
            tipovacka_edit($pdo, $edit, $url, $news_id, $nazev_cz, $nazev_en, $popis_cz, $popis_en, $datum_od, $datum_do, $datum_do_poradi, $tip_zapasy, $tip_poradi, $tip_otazky, $tip_zapasy_remizy, $aktivni, $valid, $soubor_str);
        } catch (Exception $e) {
        }
    endif;
    ?>
</div>