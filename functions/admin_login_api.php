<?php
global $pdo;
$qusr_logout = $_GET['qusr_logout'] ?? 0; $login_error = "";
$result = $_GET['send_ok'] ?? 0;
if (sp_hodnota($pdo, 'login_on')==0):  session_destroy(); $login_error = "Probíhá odstávka webu, zkuste později"; endif;

//odhlaseni, resp. pokud je nastaveny logout, tak se provede zniceni session
if ($qusr_logout == 1):
    session_destroy();
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    echo "<script type='text/javascript'>document.location.href='$url';</script>";
    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
endif;

//prihlaseni, pokud je odeslan formular prihlaseni
if (isset($_POST["qusr_login"])):
    // Použití funkce pro ověření uživatele
    $usernameOrEmail = $_POST["qusr_login"];
    $password = $_POST["qusr_pass"];
    $result = verifyUser($pdo, $usernameOrEmail, $password);
    if ($result==0):
        $login_error = '';
    else:
        $login_error = $result;
    endif;
endif;

//neprihlaseny uzivatel přihlašovací formulář
if ((!isset($_SESSION['qusr_logged'])) OR ($_SESSION['qusr_logged']<> 1)):
    echo '
	<div class="wrapper fadeInDown">
		<div id="formContent">
    		<div class="fadeIn first">
      			<img src="/images/_design/logo-tipovacka.svg" width="250px;" id="icon" alt="Tipovačka, HCPCEFANS.CZ" />
    		</div>
    		<p class="text-danger mt-1">'.$login_error.'</p>
		    <form action="" method="post" class="formLogin">
			    <input type="text" name="qusr_login" id="qusr_login" class="fadeIn second" placeholder="nickname nebo email" tabindex="1" accesskey="p" />	
			    <input type="password" name="qusr_pass" id="qusr_pass" class="fadeIn third" placeholder="heslo" tabindex="2" accesskey="s" />
			    <input type="submit" id="submit" class="fadeIn fourth" value="Přihlásit" tabindex="3" accesskey="l" />
		    </form>
		    <a href="https://forum.hcpcefans.cz" class="btn btn-sm btn-danger mb-1" type="button">
                Reset hesla proveďte na hlavní straně fóra
            </a>
		    <div id="formFooter">
      		    <span class="text-success text-xs">Přihlašovací údaje jsou shodné s fórem <a href="https://forum.hcpcefans.cz" title="Forum">forum.hcpcefans.cz</a><br> 
      		    Pokud se chcete účastnit tipovaček, registrujte se do fóra a následně stejné přihlašovací údaje, čili nickname nebo email a heslo využijte zde. </span>
		    </div>
		</div>
	</div>';
endif;



