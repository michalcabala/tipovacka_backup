<?php
include SEC_DIR."/_functions/fun_galerie.php";
global $pdo;
$lang = $_GET['lang'] ?? "cz";

$nazev_cz = $_POST['nazev_cz'] ?? ""; $nazev_en = $_POST['nazev_en'] ?? ""; $poradi = $_POST['poradi'] ?? 1; $poradi_final = $_POST['poradi_final'] ?? 0;
$tipovacka_id = $_POST['tipovacka_id'] ?? $_SESSION["user_tipdef"];
$add = $_POST['add'] ?? 0;
?>

<div class="card-body">
    <?php
    if($add == 0):
        ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="nazev_cz">Název týmu (cz)</label>
                    <input type="text" name="nazev_cz" id="nazev_cz" class="form-control text-left" value="<?php echo $nazev_cz; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="tipovacka_id">Tipovačka</label>
                    <select name="tipovacka_id" id="tipovacka_id" class="custom-select">
                        <?php tipovacka_option_form($pdo, $tipovacka_id);?>
                    </select>
                </div>
                <div class="form-group col-md-1">
                    <label for="poradi">Pořadí</label>
                    <input type="number" name="poradi" id="poradi" class="form-control text-left" value="<?php echo $poradi; ?>" />
                </div>
                <div class="form-group col-md-1">
                    <label for="poradi_final">Finální pořadí</label>
                    <input type="number" name="poradi_final" id="poradi_final" class="form-control text-left" value="<?php echo $poradi_final; ?>" />
                </div>
            </div>
            <?php if (en_on($pdo)==1):?>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="nazev_en">Název týmu (en)</label>
                    <input type="text" name="nazev_en" id="nazev_en" class="form-control text-left" value="<?php echo $nazev_en; ?>" />
                </div>
            </div>
            <?php endif;?>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <input type="hidden" name="add" value="1" />
                    <label for="submit">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-primary">Vložit tým do tipovačky</button>
                </div>
            </div>
        </form>

    <?php
    elseif($add == 1):
        tipovacka_teams_add($pdo, $tipovacka_id, $nazev_cz, $nazev_en, $poradi, $poradi_final);
    endif;
    ?>
</div>