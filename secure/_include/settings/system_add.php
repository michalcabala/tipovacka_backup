<?php
global $pdo;

$typ = $_POST['typ'] ?? "";     $popis_cz = $_POST['popis_cz'] ?? "";       $name = $_POST['name'] ?? "";
$hodnota = $_POST['hodnota'] ?? 0;  $hodnota_text = $_POST['hodnota_text'] ?? "";   $add = $_POST['add'] ?? 0;
?>
<div class="card-body">
<?php
	if($add == 0):
?>
<form method="post">
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="typ">Typ systémové hodnoty</label>
            <select name="typ" id="typ" class="custom-select">
                <option value="admin">admin</option>
                <option value="main">main</option>
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
        <div class="form-group col-md-2">
            <input type="hidden" name="add" value="1" />
            <label for="submit">&nbsp;</label>
            <button type="submit" class="form-control btn btn-primary">Vložit systémovou proměnou</button>
        </div>
    </div>
</form>
<?php 
	elseif($add == 1): 
	settings_add ($pdo, $typ, $name, $popis_cz, $hodnota, $hodnota_text);
	endif;
?>
</div>