<?php
global $pdo;
$id = $_GET['edit'] ?? "";      $url_cz = $_POST['url_cz'] ?? "";   $nazev_cz = $_POST['nazev_cz'] ?? "";
$menu = $_POST['menu'] ?? 0;    $valid = $_POST['valid'] ?? 0;      $add = $_POST['add'] ?? 0;
?>

<div class="card-body">
<?php 
	if($add == 0): 
	$sql = "SELECT * FROM menu WHERE id = :id";
    $res = $pdo->prepare($sql);
    $res->execute(['id'=>$id]);
    $dev = $res->fetch();
    $url_cz = $dev['url_cz']; $nazev_cz = $dev['nazev_cz']; $menu = $dev['menu']; $valid = $dev["valid"];

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
            <div class="custom-control custom-switch">
                <input type="checkbox" name="valid" id="valid" class="custom-control-input" value="1" <?php if ($valid==1): echo "checked"; endif;?> />
                <label for="valid" class="custom-control-label">valid</label>
            </div>
            <div class="form-group col-md-2">
                <input type="hidden" name="add" value="2" />
                <label for="submit">&nbsp;</label>
                <button type="submit" class="form-control btn btn-success">Uložit menu</button>
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
            menu_edit ($pdo, $id, $url_cz, $nazev_cz, $menu, $valid);
        endif;
        ?>
    </div>