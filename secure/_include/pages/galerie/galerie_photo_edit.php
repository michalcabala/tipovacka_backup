<?php
global $pdo;
$lang = $_GET['lang'] ?? "cz"; $edit = $_GET['edit'] ?? 0;

$nazev_cz = $_POST['nazev_cz'] ?? ""; $nazev_en = $_POST['nazev_en'] ?? ""; $poradi = $_POST['poradi'] ?? 0;
$add = $_POST['add'] ?? 0;
?>

<div class="card-body">
    <?php
    if($add == 0):
        $sql = "SELECT * FROM galerie_photo WHERE id = :edit";
        $res = $pdo->prepare($sql);
        $res->execute(['edit'=>$edit]);
        $dev = $res->fetch();
        $nazev_cz = $dev["nazev_cz"]; $nazev_en = $dev["nazev_en"]; $poradi = $dev["poradi"];
        ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="nazev_cz">Název fotografie (CZ)</label>
                    <input type="text" name="nazev_cz" id="nazev_cz" class="form-control text-left" value="<?php echo $nazev_cz; ?>" />
                </div>
                <div class="form-group col-md-4">
                    <label for="nazev_en">Název fotografie (EN)</label>
                    <input type="text" name="nazev_en" id="nazev_en" class="form-control text-left" value="<?php echo $nazev_en; ?>" />
                </div>
                <div class="form-group col-md-1">
                    <label for="poradi">Pořadí</label>
                    <input type="text" name="poradi" id="poradi" class="form-control text-left" value="<?php echo $poradi; ?>" />
                </div>
                <div class="form-group col-md-3">
                    <input type="hidden" name="add" value="2" />
                    <label for="submit">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-primary">Upravit fotografii</button>
                </div>
            </div>
            <div class="col-md-12 small">
                Založeno: <?php echo format_datetime_www($dev['ts_i']); ?>;
                Založil: <?php echo $dev['user_i']; ?>;
                Upraveno: <?php echo format_datetime_www($dev['ts_u']); ?>;
                Upravil: <?php echo $dev['user_u']; ?>
            </div>
        </form>

    <?php
    elseif($add == 2):
        galerie_photo_edit ($pdo, $edit, $nazev_cz, $nazev_en, $poradi);
    endif;
    ?>
</div>