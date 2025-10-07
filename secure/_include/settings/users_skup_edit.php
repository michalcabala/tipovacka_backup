<?php
global $pdo;

$id = $_GET['edit'] ?? "";  $nazev_cz = $_POST['nazev_cz'] ?? "";   $poradi = $_POST['poradi'] ?? 1;    $valid = $_POST['valid'] ?? 0;
$add = $_POST['add'] ?? 0;
?>
<div class="card-body">
    <?php
    if($add == 0):
        $sql = "SELECT * FROM users_skup WHERE id = :id";
        $res = $pdo->prepare($sql);
        $res->execute(['id'=>$id]);
        $dev = $res->fetch();
        $nazev_cz = $dev["nazev_cz"]; $poradi = $dev["poradi"]; $valid = $dev["valid"];

        ?>
    <form method="post">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="nazev_cz">Název (cz)</label>
                <input type="text" name="nazev_cz" id="nazev_cz" class="form-control text-left" value="<?php echo $nazev_cz; ?>" />
            </div>
            <div class="form-group col-md-2">
                <label for="poradi">Pořadí</label>
                <input type="number" name="poradi" id="poradi" class="form-control text-left" value="<?php echo $poradi; ?>" />
            </div>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="valid" id="valid" class="custom-control-input" value="1" <?php if ($valid==1): echo "checked"; endif;?> />
                <label for="valid" class="custom-control-label">valid</label>
            </div>
            <div class="form-group col-md-2">
                <input type="hidden" name="add" value="2" />
                <label for="submit">&nbsp;</label>
                <button type="submit" class="form-control btn btn-primary">Uložit skupinu uživatelů</button>
            </div>
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
        users_skup_edit($pdo, $id, $nazev_cz, $poradi, $valid);
    endif;
    ?>
</div>