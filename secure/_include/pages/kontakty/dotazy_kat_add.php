<?php
global $pdo;

$nazev_cz = $_POST['nazev_cz'] ?? "";   $nazev_en = $_POST['nazev_en'] ?? "";
$email = $_POST['email'] ?? "";   $poradi = $_POST['poradi'] ?? 1;    $visible = $_POST['visible'] ?? 1;
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
                <?php if (en_on($pdo)==1):?>
                    <div class="form-group col-md-3">
                        <label for="nazev_en">Název (en)</label>
                        <input type="text" name="nazev_en" id="nazev_en" class="form-control text-left" value="<?php echo $nazev_en; ?>" />
                    </div>
                <?php endif;?>
                <div class="form-group col-md-3">
                    <label for="email">E-mail (více emailů oddělené středníkem)</label>
                    <input type="email" name="email" id="email" class="form-control text-left" value="<?php echo $email; ?>" />
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
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <input type="hidden" name="add" value="1" />
                    <label for="submit">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-primary">Vložit kategorii dotazu</button>
                </div>
            </div>
        </form>
    <?php
    elseif($add == 1):
        dotazy_kat_add($pdo, $nazev_cz, $nazev_en, $poradi, $email, $visible);
    endif;
    ?>
</div>