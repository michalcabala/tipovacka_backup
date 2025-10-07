<?php
global $pdo;
$login_error = '';

//odhlaseni, resp. pokud je nastaveny logout, tak se provede zniceni session
$qn_logout = $_GET['qn_logout'] ?? 0;
if ($qn_logout == 1):
    session_destroy();
    header("location: /secure/");
endif;

//prihlaseni, pokud je odeslan formular
if (isset($_POST["qn_login"])):
    $qn_login = $_POST["qn_login"];
    $qn_pass = sha1($_POST["qn_pass"]);
    $sql = "SELECT count(*) FROM users WHERE login = :login AND password = :pass AND valid = 1";
    $res = $pdo->prepare($sql);
    $res->execute(['login' => $qn_login, 'pass' => $qn_pass]);

    if ($res->fetchColumn()==1):
        $_SESSION["qn_logged"] = 1;
        users_log ($pdo, $qn_login, 1);
        $_SESSION["qn_user"] = $qn_login;
    endif;
endif;

//neprihlaseny uzivatel
if ((!isset($_SESSION['qn_logged'])) OR ($_SESSION['qn_logged']<> 1)):
    if (isset($_POST["qn_login"])):
        $login_error = "<p class='text-danger'>Neplatné přihlašovací údaje.</p>\n";
    endif;
    echo '
	<div class="wrapper fadeInDown">
		'.$login_error.'
		<div id="formContent">

    		<div class="fadeIn first">
      			<img src="img/admin_logo_long.gif" id="icon" alt="Administrace, www.sokotop.cz" />
    		</div>
			
		<form action="" method="post">
			<input type="text" name="qn_login" id="qn_login" class="fadeIn second" placeholder="login" tabindex="1" accesskey="p" />	
			<input type="password" name="qn_pass" id="qn_pass" class="fadeIn third" placeholder="password" tabindex="2" accesskey="s" />
			<input type="submit" id="submit" class="fadeIn fourth" value="Přihlásit" tabindex="3" accesskey="l" />
		</form>
		<div id="formFooter">
      		<span class="text-success text-xs"><strong>created by <a href="mailto:tom.jirecek@gmail.com" alt="Bc. Tomáš Jireček">Bc. Tomáš Jireček</a></strong></span>
		</div>
		</div>
	</div>';
    exit; //zabraneni zobrazovani dalsich veci
endif;


