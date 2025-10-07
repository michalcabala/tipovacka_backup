<?php
global $pdo;

$nazev_cz = $_POST['nazev_cz'] ?? "";       $nazev_en = $_POST['nazev_en'] ?? ""; $aktivni = $_POST['aktivni'] ?? 1;
$popis_cz = $_POST['popis_cz'] ?? "";       $popis_en = $_POST['popis_en'] ?? ""; $tip_zapasy_remizy = $_POST['tip_zapasy_remizy'] ?? 0;
$datum_od = $_POST['datum_od'] ?? "";       $datum_do = $_POST['datum_do'] ?? ""; $datum_do_poradi = $_POST['datum_do_poradi'] ?? "";
$tip_zapasy = $_POST['tip_zapasy'] ?? 0;    $tip_poradi = $_POST['tip_poradi'] ?? 0; $tip_otazky = $_POST['tip_otazky'] ?? 0; $news_id = $_POST['news_id'] ?? "";
$add = $_POST['add'] ?? 0;
?>
<div class="card-body">
    <?php
    if($add == 0):
        ?>
        <form method="post">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="nazev_cz">Název (cz)</label>
                    <input type="text" name="nazev_cz" id="nazev_cz" class="form-control text-left" value="<?php echo $nazev_cz; ?>" />
                </div>
                <div class="form-group col-md-7">
                    <label for="popis_cz">Popis (cz) tipovačky</label>
                    <input type="text" name="popis_cz" id="popis_cz" class="form-control text-left" value="<?php echo $popis_cz; ?>" />
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
            <?php if (en_on($pdo)==1):?>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="nazev_en">Název (en)</label>
                    <input type="text" name="nazev_en" id="nazev_en" class="form-control text-left" value="<?php echo $nazev_en; ?>" />
                </div>
                <div class="form-group col-md-9">
                    <label for="popis_en">Popis (en)</label>
                    <input type="text" name="popis_en" id="popis_en" class="form-control text-left" value="<?php echo $popis_en; ?>" />
                </div>
            </div>
            <?php endif;?>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <input type="hidden" name="add" value="1" />
                    <label for="submit">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-primary">Vložit tipovačku</button>
                </div>
            </div>
        </form>
    <?php
    elseif($add == 1):
        tipovacka_add($pdo, $news_id, $nazev_cz, $nazev_en, $popis_cz, $popis_en, $datum_od, $datum_do, $datum_do_poradi, $tip_zapasy, $tip_poradi, $tip_otazky, $tip_zapasy_remizy, $aktivni);
    endif;
    ?>
</div>