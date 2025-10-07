<?php
global $pdo;
$url_cz = $_POST['url_cz'] ?? "";       $nazev_cz = $_POST['nazev_cz'] ?? "";   $menu = $_POST['menu'] ?? 0;
$add = $_POST['add'] ?? 0;
?>
<div class="card-body">
<?php
	if($add == 0):
?>
<form method="post">
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="menu">Číslo menu</label>
            <input type="number" name="menu" id="menu" class="form-control text-left" value="<?php echo $menu; ?>" />
        </div>
        <div class="form-group col-md-3">
            <label for="url_cz">URL (cz)</label>
            <input type="text" name="url_cz" id="url_cz" class="form-control text-left" value="<?php echo $url_cz; ?>" />
        </div>
        <div class="form-group col-md-4">
            <label for="nazev_cz">Název (cz)</label>
            <input type="text" name="nazev_cz" id="nazev_cz" class="form-control text-left" value="<?php echo $nazev_cz; ?>" />
        </div>
        <div class="form-group col-md-2">
            <input type="hidden" name="add" value="1" />
            <label for="submit">&nbsp;</label>
            <button type="submit" class="form-control btn btn-primary">Vložit menu</button>
        </div>
    </div>
</form>
<?php 
	elseif($add == 1): 
	menu_add ($pdo, $url_cz, $nazev_cz, $menu);
	endif;
?>
</div>