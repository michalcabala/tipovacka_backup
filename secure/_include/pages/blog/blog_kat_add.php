<?php
global $pdo;

$nazev_cz = $_POST['nazev_cz'] ?? "";   $nazev_en = $_POST['nazev_en'] ?? "";
$popis_cz = $_POST['popis_cz'] ?? "";   $popis_en = $_POST['popis_en'] ?? ""; $page_cz = $_POST['page_cz'] ?? ""; $page_en = $_POST['page_en'] ?? "";
$poradi = $_POST['poradi'] ?? 1;        $color = $_POST['color'] ?? ""; $visible = $_POST['visible'] ?? 0;
$add = $_POST['add'] ?? 0;
?>
<div class="card-body">
    <?php
    if($add == 0):
        ?>
        <form method="post">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="nazev_cz">Název (cz)</label>
                    <input type="text" name="nazev_cz" id="nazev_cz" class="form-control text-left" value="<?php echo $nazev_cz; ?>" />
                </div>
                <div class="form-group col-md-3">
                    <label for="popis_cz">Popis (cz)</label>
                    <input type="text" name="popis_cz" id="popis_cz" class="form-control text-left" value="<?php echo $popis_cz; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="page_cz">Page (cz)</label>
                    <input type="text" name="page_cz" id="page_cz" class="form-control text-left" value="<?php echo $page_cz; ?>" />
                </div>
                <div class="form-group col-md-1">
                    <label for="poradi">Pořadí</label>
                    <input type="number" name="poradi" id="poradi" class="form-control text-left" value="<?php echo $poradi; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="visible">Zobrazit</label>
                    <select name="visible" id="visible" class="custom-select">
                        <option value="1" <?php if ($visible==1): echo 'selected="selected"'; endif; ?>>Ano</option>
                        <option value="0" <?php if ($visible==0): echo 'selected="selected"'; endif; ?>>Ne</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="color">Color</label>
                    <input type="text" name="color" id="color" class="form-control text-left" value="<?php echo $color; ?>" />
                </div>
            </div>
            <div class="form-row">
                <?php if (en_on($pdo)==1):?>
                <div class="form-group col-md-2">
                    <label for="nazev_en">Název (en)</label>
                    <input type="text" name="nazev_en" id="nazev_en" class="form-control text-left" value="<?php echo $nazev_en; ?>" />
                </div>
                <div class="form-group col-md-3">
                    <label for="popis_en">Popis (en)</label>
                    <input type="text" name="popis_en" id="popis_en" class="form-control text-left" value="<?php echo $popis_en; ?>" />
                </div>
                    <div class="form-group col-md-2">
                        <label for="page_en">Page (en)</label>
                        <input type="text" name="page_en" id="page_en" class="form-control text-left" value="<?php echo $page_en; ?>" />
                    </div>
                <?php endif;?>
                <div class="form-group col-md-2">
                    <input type="hidden" name="add" value="1" />
                    <label for="submit">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-primary">Vložit kategorii blogu</button>
                </div>
            </div>
        </form>
    <?php
    elseif($add == 1):
        blog_kat_add($pdo, $nazev_cz, $nazev_en, $poradi, $popis_cz, $popis_en, $page_cz, $page_en, $color, $visible);
    endif;
    ?>
</div>