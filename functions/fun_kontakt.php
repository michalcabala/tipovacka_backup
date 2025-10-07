<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function dotazy_vlozit ($pdo, $dotazy_kat, $name, $email, $text)
{
    $name = addslashes($name);
    $email = addslashes($email);
    $text = addslashes($text);

    $sql = "INSERT INTO dotazy (dotazy_kat, name, email, text) VALUES 
		(:dotazy_kat, :name, :email, :text)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['dotazy_kat'=>$dotazy_kat, 'name'=>$name, 'email'=>$email, 'text'=>$text]);
        unset ($_POST['add']);
        dotazy_info_send($pdo, $dotazy_kat, $name, $email, $text);

        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?send_ok=1";
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Dotaz nebyl odeslán</span></a>';
        echo $error;
    }
}

function dotazy_info_send ($pdo, $dotazy_kat, $name, $email, $text)
{

    error_reporting(E_STRICT | E_ALL);
    date_default_timezone_set('Etc/UTC');
    require ROOT_DIR."/lib/PHPMailer/src/Exception.php";
    require ROOT_DIR."/lib/PHPMailer/src/PHPMailer.php";
    require ROOT_DIR."/lib/PHPMailer/src/SMTP.php";

    $body1 = '
<!DOCTYPE html>
<html lang="en">
 <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title>Tipovačka HCPCEFANS.cz</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;">
<style>p {margin: 10px 5px;font-family:Calibri,Verdana,serif;font-size: 12px;}</style> 
<p>
    <strong>Z webu <a href="https://tipovacka.hcpcefans.cz">tipovacka.hcpcefans.cz</a> došel dotaz, tak na něj odpověz</strong>
</p>
<p>
    <strong>Jméno</strong>: '.$name.'<br />
    <strong>Email:</strong> '.$email.'<br />
    <p>Text: '.$text.'</p><br />
    </p>
</body></html>';
    $body2 = '
<!DOCTYPE html>
<html lang="en">
 <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
     <title>Tipovačka HCPCEFANS.cz</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;">
<style>p {margin: 10px 5px;font-family:Calibri,Verdana,serif;font-size: 12px;}</style> 
<p>
    <strong>Na webu <a href="https://tipovacka.hcpcefans.cz">tipovacka.hcpcefans.cz</a> jsi položil dotaz, děkujeme, ozveme se.</strong>
</p>
<p>
    <strong>Jméno</strong>: '.$name.'<br />
    <strong>Email:</strong> '.$email.'<br />
    <p>Text: '.$text.'</p><br />
    </p>
</body></html>';

    if ($_SERVER["SERVER_ADDR"]=="127.0.0.1" OR $_SERVER["SERVER_ADDR"]=="::1"):
        $config = parse_ini_file(ROOT_DIR."/ini/config_local.ini");
    else:
        $config = parse_ini_file(ROOT_DIR."/ini/config.ini");
    endif;
    $mail = new PHPMailer(true);
    $mail->IsSMTP();  					// k odeslání e-mailu použijeme SMTP server
    $mail->Host = $config['smtp_server'];  // zadáme adresu SMTP serveru
    $mail->SMTPAuth = true;           // nastavíme true v případě, že server vyžaduje SMTP autentizaci
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->SMTPKeepAlive = true; //SMTP connection will not close after each email sent, reduces SMTP overhead
    $mail->Username = $config['smtp_user'];   // uživatelské jméno pro SMTP autentizaci
    $mail->Password = $config['smtp_password'];           // heslo pro SMTP autentizaci
    $mail->AltBody = "Pro zobrazení této zprávy použijte kompatibilní HTML prohlížeč.";
    $mail->CharSet = "utf-8";   // nastavíme kódování, ve kterém odesíláme e-mail

    try {
        //zprava z webu adminovi
        $mail->setFrom($config['smtp_from']);
        $mail->AddAddress(dotazy_kat_email($pdo, $dotazy_kat));  // přidáme příjemce
        $mail->Subject = 'Dotaz z tipovacka.hcpcefans.cz';    // nastavíme předmět e-mailu
        $mail->msgHTML($body1);
        $mail->send();
        //vycisteni
        $mail->ClearAddresses();
        //potvrzeni zpravy odesilateli
        $mail->setFrom(dotazy_kat_email($pdo, $dotazy_kat));
        $mail->AddAddress($email);  // přidáme příjemce
        $mail->Subject = 'Děkujeme za dotaz na tipovacka.hcpcefans.cz';    // nastavíme předmět e-mailu
        $mail->msgHTML($body2);
        $mail->send();
    } catch (Exception $e) {
        echo 'Mailer chyba (' . htmlspecialchars($email) . ') ' . $mail->ErrorInfo . '<br>';
    }
}

function dotazy_kat_email ($pdo, $dotazy_kat)
{
    $sql = "SELECT email FROM dotazy_kat WHERE id = :dotazy_kat AND valid = 1";
    $res = $pdo->prepare($sql);
    $res->execute(['dotazy_kat' => $dotazy_kat]);
    $dev = $res->fetch();
    return $dev['email'];
}

function kontakt_user_email ($pdo, $login)
{
    $sql = "SELECT email FROM zdef_tipovacka_users WHERE login = :login AND valid = 1";
    $res = $pdo->prepare($sql);
    $res->execute(['login' => $login]);
    $dev = $res->fetch();
    return $dev['email'];
}

function kontakt_user_info_send ($pdo, $login)
{
    $sql = "SELECT info_send FROM zdef_tipovacka_users WHERE login = :login AND valid = 1";
    $res = $pdo->prepare($sql);
    $res->execute(['login' => $login]);
    $dev = $res->fetch();
    return $dev['info_send'];
}

function kontakt_user_update ($pdo, $login, $email, $password, $info_send)
{
    $password_sha1 = sha1($password);
    $sql1 = 'SELECT count(*) FROM zdef_tipovacka_users WHERE login <> :login AND email = :email';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['login'=>$login, 'email'=>$email]);
    $dev1 = $res1->fetchColumn();

    if ($dev1 == 0):
        if ($password == ''):
            $sql2 = 'UPDATE zdef_tipovacka_users SET email = :email, info_send = :info_send WHERE login = :login';
            $res2 = $pdo->prepare($sql2);
            $res2->execute(['login'=>$login, 'email'=>$email, 'info_send'=>$info_send]);
        else:
            $sql2 = 'UPDATE zdef_tipovacka_users SET email = :email, password = :password, info_send = :info_send WHERE login = :login';
            $res2 = $pdo->prepare($sql2);
            $res2->execute(['login'=>$login, 'email'=>$email, 'password'=>$password_sha1, 'info_send'=>$info_send]);
        endif;
        $url_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$url_parts[0]?send_ok=2";
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        //echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    else:
        $url_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$url_parts[0]?send_ok=3";
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        //echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    endif;
}