<?php
global $pdo;

$login = $_POST['login'] ?? ""; $name = $_POST['name'] ?? ""; $email = $_POST['email'] ?? ""; $password = $_POST['password'] ?? "";
$active = $_POST['active'] ?? 0; $blocked = $_POST['blocked'] ?? 0; $phpbb = $_POST['phpbb'] ?? 0; $info_send = $_POST['info_send'] ?? 0;
$add = $_POST['add'] ?? 0;
?>
<div class="card-body">
    <?php
    if($add == 1): tipovacka_users_add($pdo, $login, $name, $email, $password, $active, $blocked, $phpbb, $info_send); endif;
        ?>
        <form method="post">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="login">Login</label>
                    <input type="text" name="login" id="login" class="form-control text-left" value="<?php echo $login; ?>" />
                </div>
                <div class="form-group col-md-4">
                    <label for="name">Jméno uživatele</label>
                    <input type="text" name="name" id="name" class="form-control text-left" value="<?php echo $name; ?>" />
                </div>
                <div class="form-group col-md-3">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" class="form-control text-left" value="<?php echo $email; ?>" />
                </div>
                <div class="form-group col-md-3">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control text-left" value="<?php echo $password; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="active">Aktivní</label>
                    <input type="checkbox" name="active" id="active" <?php if ($active==1): echo "checked"; endif;?>  value="1" class="form-control text-left" />
                </div>
                <div class="form-group col-md-2">
                    <label for="blocked">Blokovaný</label>
                    <input type="checkbox" name="blocked" id="blocked" <?php if ($blocked==1): echo "checked"; endif;?>  value="1" class="form-control text-left" />
                </div>
                <div class="form-group col-md-2">
                    <label for="phpbb">Z fóra hcpce</label>
                    <input type="checkbox" name="phpbb" id="phpbb" <?php if ($phpbb==1): echo "checked"; endif;?>  value="1" class="form-control text-left" />
                </div>
                <div class="form-group col-md-2">
                    <label for="info_send">Info o zápasech</label>
                    <input type="checkbox" name="info_send" id="info_send" <?php if ($info_send==1): echo "checked"; endif;?>  value="1" class="form-control text-left" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <input type="hidden" name="add" value="1" />
                    <label for="submit">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-primary">Vložit uživatele</button>
                </div>
            </div>
        </form>
</div>