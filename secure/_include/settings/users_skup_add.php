<?php
global $pdo;

$nazev_cz = $_POST['nazev_cz'] ?? "";   $poradi = $_POST['poradi'] ?? 1;    $add = $_POST['add'] ?? 0;
?>
<div class="card-body">
    <?php
    if($add == 0):
        ?>
        <form method="post">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="nazev_cz">Název (cz)</label>
                    <input type="text" name="nazev_cz" id="nazev_cz" class="form-control text-left" value="<?php echo $nazev_cz; ?>" />
                </div>
                <div class="form-group col-md-1">
                    <label for="poradi">Pořadí</label>
                    <input type="number" name="poradi" id="poradi" class="form-control text-left" value="<?php echo $poradi; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <input type="hidden" name="add" value="1" />
                    <label for="submit">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-primary">Vložit skupinu uživatelů</button>
                </div>
            </div>
        </form>
    <?php
    elseif($add == 1):
        users_skup_add($pdo, $nazev_cz, $poradi);
    endif;
    ?>
</div>