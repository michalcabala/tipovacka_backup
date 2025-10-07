<?php
global $pdo;

$id = $_GET['edit'] ?? "";      $nazev_cz = $_POST['nazev_cz'] ?? "";   $nazev_en = $_POST['nazev_en'] ?? "";
$popis_cz = $_POST['popis_cz'] ?? "";   $popis_en = $_POST['popis_en'] ?? "";   $poradi = $_POST['poradi'] ?? 1;
$color = $_POST['color'] ?? ""; $valid = $_POST['valid'] ?? 0;  $add = $_POST['add'] ?? 0;
?>

<div class="card-body">
    <?php
    if($add == 0):
        $sql = "SELECT * FROM galerie_typ WHERE id = :id";
        $res = $pdo->prepare($sql);
        $res->execute(['id'=>$id]);
        $dev = $res->fetch();
        $nazev_cz = $dev["nazev_cz"]; $nazev_en = $dev["nazev_en"]; $poradi = $dev["poradi"]; $popis_cz = $dev["popis_cz"]; $popis_en = $dev["popis_en"]; $color = $dev["color"];
        $valid = $dev["valid"];

        ?>
    <form method="post">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="nazev_cz">Název (cz)</label>
                <input type="text" name="nazev_cz" id="nazev_cz" class="form-control text-left" value="<?php echo $nazev_cz; ?>" />
            </div>
            <div class="form-group col-md-4">
                <label for="popis_cz">Popis (cz)</label>
                <input type="text" name="popis_cz" id="popis_cz" class="form-control text-left" value="<?php echo $popis_cz; ?>" />
            </div>
            <div class="form-group col-md-1">
                <label for="poradi">Pořadí</label>
                <input type="number" name="poradi" id="poradi" class="form-control text-left" value="<?php echo $poradi; ?>" />
            </div>
            <div class="form-group col-md-2">
                <label for="color">Color</label>
                <input type="text" name="color" id="color" class="form-control text-left" value="<?php echo $color; ?>" />
            </div>
        </div>
        <div class="form-row">
            <?php if (en_on($pdo)==1):?>
                <div class="form-group col-md-4">
                    <label for="nazev_en">Název (en)</label>
                    <input type="text" name="nazev_en" id="nazev_en" class="form-control text-left" value="<?php echo $nazev_en; ?>" />
                </div>
                <div class="form-group col-md-4">
                    <label for="popis_en">Popis (en)</label>
                    <input type="text" name="popis_en" id="popis_en" class="form-control text-left" value="<?php echo $popis_en; ?>" />
                </div>
            <?php endif;?>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="valid" id="valid" class="custom-control-input" value="1" <?php if ($valid==1): echo "checked"; endif;?> />
                <label for="valid" class="custom-control-label">valid</label>
            </div>
            <div class="form-group col-md-2">
                <input type="hidden" name="add" value="2" />
                <label for="submit">&nbsp;</label>
                <button type="submit" class="form-control btn btn-primary">Uložit typ novinek</button>
            </div>
            <div class="col-md-12 small">
                Založeno: <?php echo format_datetime_www($dev['ts_i']); ?>;
                Založil: <?php echo $dev['user_i']; ?>;
                Upraveno: <?php echo format_datetime_www($dev['ts_u']); ?>;
                Upravil: <?php echo $dev['user_u']; ?>
            </div>
        </div>
    <?php
    elseif($add == 2):
        galerie_typ_edit($pdo, $id, $nazev_cz, $nazev_en, $poradi, $popis_cz, $popis_en, $color, $valid);
    endif;
    ?>
</div>