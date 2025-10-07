<?php
global $pdo;
$name = $_POST['name'] ?? "";   $email = $_POST['email'] ?? ""; $add = $_POST['add'] ?? 0;
?>
<div class="card-body">
    <?php
    if($add == 0):
        ?>
        <form method="post">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="name">Jméno, popis</label>
                    <input type="text" name="name" id="name" class="form-control text-left" value="<?php echo $name; ?>" />
                </div>
                <div class="form-group col-md-3">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" class="form-control text-left" value="<?php echo $email; ?>" />
                </div>
                <div class="form-group col-md-4">
                    <input type="hidden" name="add" value="1" />
                    <label for="submit">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-primary">Vložit uživatele novinek</button>
                </div>
            </div>
        </form>
    <?php
    elseif($add == 1):
        news_users_add($pdo, $name, $email);
    endif;
    ?>
</div>