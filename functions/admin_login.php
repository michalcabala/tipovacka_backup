<?php
global $pdo;
$qusr_logout = $_GET['qusr_logout'] ?? 0; $login_error = "";
$send_ok = $_GET['send_ok'] ?? 0;
if (sp_hodnota($pdo, 'login_on')==0):  session_destroy(); $login_error = "Probíhá odstávka webu, zkuste později"; endif;

//odhlaseni, resp. pokud je nastaveny logout, tak se provede zniceni session
if ($qusr_logout == 1):
    session_destroy();
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    echo "<script type='text/javascript'>document.location.href='$url';</script>";
    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
endif;

//odeslani zapomenuteho hesla - overeni uzivatele, generace klice, exp_date
if (isset($_POST['qusr_login_reset'])):
    $qusr_login_reset = $_POST['qusr_login_reset'];
    $sql1 = 'SELECT count(*) FROM zdef_tipovacka_users WHERE (login = :login OR email = :email) AND active = 1 AND blocked = 0 AND valid = 1';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['login'=>$qusr_login_reset, 'email'=>$qusr_login_reset]);
    if ($res1->fetchColumn()==1):
        tusers_password_resend ($pdo, $qusr_login_reset);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/cz/index?send_ok=1";
    else:
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/cz/index?send_ok=2";
    endif;
    echo "<script type='text/javascript'>document.location.href='$url';</script>";
    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
endif;

//zmena hesla z reset formulare
if (isset($_POST['qusr_new_id'])):
    $qusr_new_id = $_POST['qusr_new_id'] ?? '';
    $qusr_new_pass = sha1($_POST['qusr_new_pass']);
    $sql2 = 'UPDATE zdef_tipovacka_users SET password = :password, reset_link_token = null WHERE id = :id';
    $res2 = $pdo->prepare($sql2);
    try {
        $res2->execute(['password'=>$qusr_new_pass, 'id'=>$qusr_new_id]);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/cz/index?send_ok=4";
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Heslo nebylo změněno.</span></a>';
        echo $error;
    }
endif;

//prihlaseni, pokud je odeslan formular prihlaseni
if (isset($_POST["qusr_login"])):
    $qusr_login = $_POST["qusr_login"];
    $qusr_pass = sha1($_POST["qusr_pass"]);
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/cz/index/home";
    $sql3 = "SELECT count(*) FROM zdef_tipovacka_users WHERE login = :login AND password = :pass AND active = 1 AND blocked = 0 AND valid = 1";
    $res3 = $pdo->prepare($sql3);
    $res3->execute(['login' => $qusr_login, 'pass' => $qusr_pass]);

    if ($res3->fetchColumn()==1):
        $_SESSION["qusr_logged"] = 1;
        zdef_tipovacka_users_log($pdo, $qusr_login, 1);
        $_SESSION["qusr_user"] = $qusr_login;
            $sql4 = "SELECT id FROM zdef_tipovacka_users WHERE login = :login AND valid = 1";
            $res4 = $pdo->prepare($sql4);
            $res4->execute(['login' => $qusr_login]);
            $dev4 = $res4->fetch();
            $_SESSION["qusr_id"] = $dev4['id'];
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    else:
        $send_ok = 3;
    endif;
endif;

switch ($send_ok){
    case 1: $login_error = "<p class='text-danger mt-1'>Na email Vám byly poslány informace k obnovení hesla.</p>\n"; break;
    case 2: $login_error = "<p class='text-danger mt-1'>Email nebo login nebyl nalezen.</p>\n"; break;
    case 3: $login_error = "<p class='text-danger mt-1'>Neplatné přihlašovací údaje.</p>\n"; break;
    case 4: $login_error = "<p class='text-danger mt-1'>Vaše heslo bylo změněno, můžete se přihlásit.</p>\n"; break;
}

//neprihlaseny uzivatel
if (((!isset($_SESSION['qusr_logged'])) OR ($_SESSION['qusr_logged']<> 1)) AND !isset($_GET['passchange'])):
    echo '
	<div class="wrapper fadeInDown">
		<div id="formContent">
    		<div class="fadeIn first">
      			<img src="/images/_design/logo-tipovacka.svg" width="250px;" id="icon" alt="Tipovačka, HCPCEFANS.CZ" />
    		</div>
    		'.$login_error.'
		    <form action="" method="post" class="formLogin">
			    <input type="text" name="qusr_login" id="qusr_login" class="fadeIn second" placeholder="login" tabindex="1" accesskey="p" />	
			    <input type="password" name="qusr_pass" id="qusr_pass" class="fadeIn third" placeholder="heslo" tabindex="2" accesskey="s" />
			    <input type="submit" id="submit" class="fadeIn fourth" value="Přihlásit" tabindex="3" accesskey="l" />
		    </form>
		    <button class="btn btn-sm btn-danger mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReset" aria-expanded="false" aria-controls="collapseReset">
                Odeslat zapomenuté heslo
            </button>
            <div class="collapse" id="collapseReset">
		        <form action="" method="post" class="formLogin">
			        <input type="text" name="qusr_login_reset" id="qusr_login_reset" class="fadeIn second" placeholder="login nebo email" tabindex="1" accesskey="p" />	
			        <input type="submit" id="submit" class="fadeIn fourth" value="Odeslat" tabindex="3" accesskey="l" />
		        </form>
		    </div>
		    <div id="formFooter">
      		    <span class="text-success text-xs">tipovacka.hcpcefans.cz</span>
		    </div>
		</div>
	</div>';
endif;

//reset hesla
if (isset($_GET['passchange']) AND !isset($_POST['qusr_new_id']) AND ((!isset($_SESSION['qusr_logged'])) OR ($_SESSION['qusr_logged']<> 1))):
    $token = $_GET['passchange'];
    $sql5 = "SELECT * FROM zdef_tipovacka_users WHERE reset_link_token = :token AND active = 1 AND blocked = 0 AND valid = 1";
    $res5 = $pdo->prepare($sql5);
    $res5->execute(['token' => $token]);
    $dev5 = $res5->fetch() ?? '';

    date_default_timezone_set('Europe/Prague');
    $date = date('Y-m-d H:i:s', time());
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

    if ($dev5 == '' OR $dev5['exp_date']<=$date):
        echo '	
	        <div class="wrapper fadeInDown">
		        <div id="formContent">
    		        <div class="fadeIn first">
      			        <img src="/images/_design/logo-tipovacka.svg" width="250px;" id="icon" alt="Tipovačka, HCPCEFANS.CZ" />
    		        </div>
    		        <p>Platnost resetovacího linku vypršela, <br /><a href="'.$url.'" title="Přihlášení">odešlete požadavek znovu</a></p>
		            <div id="formFooter">
      		            <span class="text-success text-xs">created by tm</span>
		            </div>
		        </div>
	        </div>';
    else:
        echo '
	        <div class="wrapper fadeInDown">
		        <div id="formContent">
    		        <div class="fadeIn first">
      			        <img src="/images/_design/logo-tipovacka.svg" width="250px;" id="icon" alt="Tipovačka, HCPCEFANS.CZ" />
    		        </div>
    		        '.$login_error.'
		            <form action="" method="post" class="formLogin">
			            <input type="text" name="login" id="login" disabled class="fadeIn second" placeholder="'.$dev5['login'].'" tabindex="1" accesskey="p" />
			            <input type="text" name="email" id="email" disabled class="fadeIn second" placeholder="'.$dev5['email'].'" tabindex="1" accesskey="p" />
			            <input type="hidden" name="qusr_new_id" id="qusr_new_id" value="'.$dev5['id'].'" />	
			            <input type="password" name="qusr_new_pass" id="qusr_new_pass" class="fadeIn third" pattern=".{6,}" required title="6 znaků minimum" placeholder="nové heslo" tabindex="2" accesskey="s" />
			            <input type="submit" id="submit" class="fadeIn fourth" value="Změnit heslo" tabindex="3" accesskey="l" />
		            </form>
		            <div id="formFooter">
      		            <span class="text-success text-xs">tipovacka.hcpcefans.cz</span>
		            </div>
		        </div>
	        </div>';
    endif;
endif;


