<?php
global $pdo;

$id = $_GET['edit'] ?? "";      $typ = $_POST['typ'] ?? "";     $popis_cz = $_POST['popis_cz'] ?? "";
$name = $_POST['name'] ?? "";   $hodnota = $_POST['hodnota'] ?? 0; $hodnota_text = $_POST['hodnota_text'] ?? "";
$valid = $_POST['valid'] ?? 0;  $add = $_POST['add'] ?? 0;
?>
<div class="card-body">
<?php 
	if($add == 0): 
	$sql = "SELECT * FROM settings WHERE id = :id";
    $res = $pdo->prepare($sql);
    $res->execute(['id'=>$id]);
    $dev = $res->fetch();
    $typ = $dev["typ"]; $name = $dev["name"]; $popis_cz = $dev["popis_cz"]; $hodnota = $dev["hodnota"]; $hodnota_text = $dev["hodnota_text"]; $valid = $dev["valid"];

?>
    <form method="post">
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="typ">Typ systémové hodnoty</label>
                <select name="typ" id="typ" class="custom-select">
                    <option value="admin" <?php if($typ=="admin"): echo "selected";endif;?>>admin</option>
                    <option value="main" <?php if($typ=="main"): echo "selected";endif;?>>main</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="name">Systémový název hodnoty</label>
                <input type="text" name="name" id="name" class="form-control text-left" value="<?php echo $name; ?>" />
            </div>
            <div class="form-group col-md-4">
                <label for="popis_cz">Popis systémové hodnoty</label>
                <input type="text" name="popis_cz" id="popis_cz" class="form-control text-left" value="<?php echo $popis_cz; ?>" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="hodnota">Hodnota</label>
                <input type="number" name="hodnota" id="hodnota" class="form-control text-left" value="<?php echo $hodnota; ?>" />
            </div>
            <div class="form-group col-md-4">
                <label for="hodnota_text">Hodnota - text</label>
                <input type="text" name="hodnota_text" id="hodnota_text" class="form-control text-left" value="<?php echo $hodnota_text; ?>" />
            </div>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="valid" id="valid" class="custom-control-input" value="1" <?php if ($valid==1): echo "checked"; endif;?> />
                <label for="valid" class="custom-control-label">valid</label>
            </div>
            <div class="form-group col-md-2">
                <input type="hidden" name="add" value="2" />
                <label for="submit">&nbsp;</label>
                <button type="submit" class="form-control btn btn-success">Uložit systémovou proměnou</button>
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
            settings_edit ($pdo, $id, $typ, $name, $popis_cz, $hodnota, $hodnota_text, $valid);
        endif;
        ?>
    </div>