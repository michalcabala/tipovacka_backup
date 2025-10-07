<?php
global $pdo;
$cz = $_POST['cz'] ?? ""; $en = $_POST['en'] ?? ""; $menu = $_POST['menu'] ?? 0; $cislo = $_POST['cislo'] ?? statvyrazy_cislomax($pdo) + 10;
$add = $_POST['add'] ?? 0;

if ($add==1):
    $sql_pre = "SELECT count(*) FROM stat_vyrazy WHERE cislo = :cislo AND valid = 1";
    $res_pre = $pdo->prepare($sql_pre);
    $res_pre->execute(['cislo'=>$cislo]);
    $dev_pre = $res_pre->fetchColumn();
    if ($dev_pre > 0):
        $add = 0;
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Statický výraz nebyl vložen, duplicitní číslo</span></a>';
    endif;
endif;
?>

<div class="card-body">
    <?php
    if($add == 0):
        ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="cislo">Číslo textu</label>
                    <input type="text" name="cislo" id="cislo" class="form-control text-left" value="<?php echo $cislo; ?>" />
                </div>

                <div class="form-group col-md-5">
                    <label for="cz">Statický výraz (cz)</label>
                    <input type="text" name="cz" id="cz" class="form-control text-left" value="<?php echo $cz; ?>" />
                </div>
                <div class="form-group col-md-5">
                    <label for="en">Statický výraz (en)</label>
                    <input type="text" name="en" id="en" class="form-control text-left" value="<?php echo $en; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="menu">Menu</label>
                    <select name="menu" id="menu" class="custom-select">
                        <?php statvyrazy_menu_option_form($pdo, $menu);?>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <input type="hidden" name="add" value="1" />
                    <label for="submit">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-primary">Vložit statický výraz</button>
                </div>
            </div>
        </form>

    <?php
    elseif($add == 1):
        statvyrazy_add ($pdo, $cislo, $cz, $en, $menu);
    endif;
    ?>
</div>