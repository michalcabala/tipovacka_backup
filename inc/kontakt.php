<?php
include ROOT_DIR."/functions/fun_kontakt.php";
$dotazy_kat = $_POST['dotazy_kat'] ?? sp_hodnota($pdo, 'dotazy_kat-default');
$name= $_POST['name'] ?? ""; $email = $_POST['email'] ?? ""; $text = $_POST['text'] ?? ""; $validate = $_POST['validate'] ?? 0;
$email = $_POST['email'] ?? ""; $password = $_POST["password"] ?? ""; $info_send = $_POST['info_send'] ?? 0;
$send = $_POST['send'] ?? 0; $send_ok = $_GET['send_ok'] ?? 0;

if($send == 1 AND $validate == 6): dotazy_vlozit($pdo, $dotazy_kat, $name, $email, $text); endif;
if($send == 2): kontakt_user_update($pdo, $_SESSION['qusr_user'], $email, $password, $info_send); endif;

?>
<?php if ($menu <> 200):?>
<div class="container-xxl bg-dark epal-header mb-0">
    <div class="container text-center my-0 pt-5 mt-1 pb-1">
        <h1 class="display-6 text-light mb-0 animated slideInDown">Kontakt</h1>
    </div>
</div>
<?php endif;?>
<section class="page-section" id="kontakt">
    <div class="container p-0">
        <?php if ($menu <> 200):?>
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Vaše registrační údaje</h2>
            <h3 class="section-subheading text-muted">Upravte, změňte si Vaše údaje.</h3>
        </div>

        <div class="row g-4">
            <div class="col-md-12 mb-3 wow fadeIn" data-wow-delay="0.1s">
                <form method="post" action="">
                    <?php
                    if ($send_ok == 2):
                        echo '<span class="btn btn-success">Uživatel upraven</span><br /><br />';
                    elseif($send_ok == 3):
                        echo '<span class="btn btn-success">E-mail je již v databázi a nelze ho uložit, zadejte jiný.</span><br /><br />';
                    endif;?>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="login" name="login" disabled value="<?php echo $_SESSION['qusr_user'] ?>" placeholder="<?php echo $_SESSION['qusr_user'];?>">
                                <label for="login">Váš login</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="email" class="form-control" disabled id="email" name="email" value="<?php echo kontakt_user_email($pdo, $_SESSION['qusr_user']);?>" placeholder="<?php echo kontakt_user_email($pdo, $_SESSION['qusr_user']);?>">
                                <label for="email">Váš e-mail</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" pattern=".{6,}" title="6 znaků minimum" value="" placeholder="prázdné = beze změny">
                                <label for="password">Heslo (prázdné = bez změny)</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="checkbox" class="form-check-input" id="info_send" name="info_send" <?php if (kontakt_user_info_send($pdo, $_SESSION['qusr_user'])==1): echo "checked"; endif;?>  value="1">
                                <label for="info_send" class="p-4">Posílat info</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="hidden" name="send" value="2" />
                                <input type="submit" name="submit" class="btn btn-danger w-100 py-3" value="Uložit" title="Uložit">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php endif;?>

        <div class="text-center">
            <h2 class="section-heading text-uppercase">Kontaktujte nás</h2>
            <h3 class="section-subheading text-muted">Našli jste chybu, něco se špatně zobrazuje, dejte vědět.</h3>
        </div>
        <div class="row g-4">
            <div class="col-md-6 wow fadeIn" data-wow-delay="0.1s">
               <img src="/images/_design/kontakt_left-image.png" class="img-fluid" alt="Kontakt" />
            </div>
            <div class="col-md-6">
                <div class="">
                    <?php
                    if ($send == 1 AND $validate <> 6):
                        echo '<span class="btn btn-danger">'.$sv[5141].'</span><br /><br />';
                        $validate = 0;
                    endif;
                    if ($send_ok == 1):
                        echo '<span class="btn btn-success">'.$sv[5151].'</span><br /><br />';
                    endif;?>
                    <form method="post" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $sv[5091];?>">
                                    <label for="name"><?php echo $sv[5091];?></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $sv[5101];?>">
                                    <label for="email"><?php echo $sv[5101];?></label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="<?php echo $sv[5121];?>" id="message" name="text" style="height: 150px"><?php echo $text; ?></textarea>
                                    <label for="message"><?php echo $sv[5121];?></label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <label for="number"></label><input type="number" class="form-control" id="number" name="validate" placeholder="<?php echo $sv[5111];?>">
                                    <label for="email"><?php echo $sv[5111];?></label>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" name="send" value="1" />
                                <input type="submit" name="submit" class="btn btn-danger w-100 py-3" title="<?php echo $sv[5131];?>">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- Contact End -->