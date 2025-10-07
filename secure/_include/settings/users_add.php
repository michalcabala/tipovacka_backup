<?php
global $pdo;

$name = $_POST['name'] ?? "";           $login = $_POST['login'] ?? "";     $password = $_POST['password'] ?? "";   $popis_cz = $_POST['popis_cz'] ?? "";
$popis_en = $_POST['popis_en'] ?? "";   $admin = $_POST['admin'] ?? 0;  $prava = $_POST['prava'] ?? 2;  $skup_id = $_POST['skup_id'] ?? 1;
$dealer_kod = $_POST['dealer_kod'] ?? ""; $ridic_kod = $_POST['ridic_kod'] ?? ""; $email = $_POST['email'] ?? ""; $add = $_POST['add'] ?? 0;
?>
<div class="card-body">
    <?php
    if($add == 0):
        ?>
        <form method="post">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="name">Příjmení, jméno</label>
                    <input type="text" name="name" id="name" class="form-control text-left" value="<?php echo $name; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="login">Login</label>
                    <input type="text" name="login" id="login" class="form-control text-left" value="<?php echo $login; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control text-left" value="<?php echo $password; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="skup_id">Skupina</label>
                    <select name="skup_id" id="skup_id" class="custom-select">
                        <?php users_skup_option_form($pdo, $skup_id);?>
                    </select>
                </div>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="admin" id="admin" class="custom-control-input" value="1" <?php if ($admin==1): echo "checked"; endif;?> />
                    <label for="admin" class="custom-control-label">admin</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="popis_cz">Popis (cz)</label>
                    <input type="text" name="popis_cz" id="popis_cz" class="form-control text-left" value="<?php echo $popis_cz; ?>" />
                </div>
                <?php if (en_on($pdo)==1):?>
                    <div class="form-group col-md-2">
                        <label for="popis_en">Popis (en)</label>
                        <input type="text" name="popis_en" id="popis_en" class="form-control text-left" value="<?php echo $popis_en; ?>" />
                    </div>
                <?php endif;?>
                <div class="form-group col-md-2">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" class="form-control text-left" value="<?php echo $email; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="dealer_kod">Kód dealera</label>
                    <input type="text" name="dealer_kod" id="dealer_kod" class="form-control text-left" value="<?php echo $dealer_kod; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="ridic_kod">Kód řidiče</label>
                    <input type="text" name="ridic_kod" id="ridic_kod" class="form-control text-left" value="<?php echo $ridic_kod; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <label for="prava">Oprávnění administrace</label>
                    <input type="number" name="prava" id="prava" class="form-control text-left" value="<?php echo $prava; ?>" />
                </div>
                <div class="form-group col-md-2">
                    <input type="hidden" name="add" value="1" />
                    <label for="submit">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-primary">Vložit uživatelský účet</button>
                </div>
            </div>
        </form>
    <?php
    elseif($add == 1):
        users_add ($pdo, $name, $login, $password, $popis_cz, $popis_en, $admin, $prava, $skup_id, $dealer_kod, $ridic_kod, $email);
    endif;
    ?>
</div>