<?php
include SEC_DIR."/_functions/fun_galerie.php";
global $pdo;
$lang = $_GET['lang'] ?? "cz"; $edit = $_GET['edit'] ?? 0;

$nazev_cz = $_POST['nazev_cz'] ?? ""; $nazev_en = $_POST['nazev_en'] ?? ""; $poradi = $_POST['poradi'] ?? 1; $poradi_final = $_POST['poradi_final'] ?? 0;
$tipovacka_id = $_POST['tipovacka_id'] ?? ""; $valid = $_POST['valid'] ?? 0;

$add = $_POST['add'] ?? 0;
?>

<div class="card-body">
    <?php
    if($add == 0):
        $sql = "SELECT * FROM zdef_tipovacka_teams WHERE id = :edit";
        $res = $pdo->prepare($sql);
        $res->execute(['edit'=>$edit]);
        $dev = $res->fetch();
        $nazev_cz = $dev["nazev_cz"]; $nazev_en = $dev["nazev_en"]; $poradi = $dev["poradi"]; $poradi_final = $dev["poradi_final"];
        $tipovacka_id = $dev['tipovacka_id'];$valid = $dev["valid"]; $image = $dev["image"];
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
            <div class="form-row">
                <?php if (en_on($pdo)==1):?>
                <div class="form-group col-md-4">
                    <label for="nazev_en">Název týmu (en)</label>
                    <input type="text" name="nazev_en" id="nazev_en" class="form-control text-left" value="<?php echo $nazev_en; ?>" />
                </div>
                <?php endif;?>
                <div class="form-group col-md-3">
                    <label for="userfile">Logo týmu</label>
                    <input type="file" name="userfile" id="userfile" class="form-control text-left" />
                </div>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="valid" id="valid" class="custom-control-input" value="1" <?php if ($valid==1): echo "checked"; endif;?> />
                    <label for="valid" class="custom-control-label">valid</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <input type="hidden" name="add" value="2" />
                    <label for="submit">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-primary">Upravit tým</button>
                </div>
            </div>
            <?php
            if($dev['image']==""):
                echo "Obrázek není připojen";
            else:
                $file = '<img src="../files/images/teams/small/'.$image.'" />';
                echo $file;
            endif;
            ?>
            <div class="col-md-12 small">
                Založeno: <?php echo format_datetime_www($dev['ts_i']); ?>;
                Založil: <?php echo $dev['user_i']; ?>;
                Upraveno: <?php echo format_datetime_www($dev['ts_u']); ?>;
                Upravil: <?php echo $dev['user_u']; ?>
            </div>
        </form>

    <?php
    elseif($add == 2):
        $soubor_str = tipovacka_teams_image_add($edit);
        tipovacka_teams_edit($pdo, $edit, $tipovacka_id, $nazev_cz, $nazev_en, $poradi, $poradi_final, $valid, $soubor_str);
    endif;
    ?>
</div>