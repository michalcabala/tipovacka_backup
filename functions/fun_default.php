<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Funkce pro ověření uživatele pomocí API
function verifyUser($pdo, $usernameOrEmail, $password)
{
    $return = 0;
    $curl = curl_init();
    // Nastavení CURL možností
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://forum.hcpcefans.cz/api/auth/',  // Endpoint pro ziskani informaci o uzivateli.
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'login=' . urlencode($usernameOrEmail) . '&password=' . urlencode($password) . '&api_bypass_permissions=1',
        CURLOPT_HTTPHEADER => array(
            'XF-Api-Key: DOLyKs3-XXy4trvY0__9mcWzg__ncdRe',
            'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

    // Odeslání požadavku a získání odpovědi
    $response = curl_exec($curl);

    // Zkontrolování chyb CURL
    if (curl_errno($curl)):
        echo 'CURL error: ' . curl_error($curl);
        echo "chyba";
        curl_close($curl);
        //return $return;
    endif;

    // Získání HTTP kódu odpovědi
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //echo $httpCode;

    // Výpis odpovědi pro ladění
    //echo "Response: " . $response . "\n";
    curl_close($curl);

    // Zpracování odpovědi
    if ($httpCode == 200):
        $result = json_decode($response, true);
        if (isset($result['success']) && $result['success'] && !$result['user']['is_banned']): //uživatel oveřen, není zabanovan
            $qusr_login = $result['user']['username'];
            $qusr_email = $result['user']['email'];
            $qusr_xenforo = $result['user']['user_id'];
            $qusr_messcount = $result['user']['message_count'];

            if ($qusr_messcount>=3):
                $_SESSION["qusr_logged"] = 1;
                $_SESSION["qusr_user"] = $qusr_login;
                $sql = 'INSERT INTO zdef_tipovacka_users (login, email, xenforo, active)
                        VALUES (:login, :email, :xenforo, 1)
                        ON DUPLICATE KEY UPDATE
                            xenforo = IF(valid = 1 AND xenforo <> VALUES(xenforo), VALUES(xenforo), xenforo),
                            email = IF(valid = 1 AND email <> VALUES(email), VALUES(email), email),
                            active = 1';
                $res = $pdo->prepare($sql);
                $res->execute(['login' => $qusr_login, 'email' => $qusr_email, 'xenforo' => $qusr_xenforo]);
                $return = 1;

                $sql2 = "SELECT id FROM zdef_tipovacka_users WHERE login = :login AND valid = 1";
                $res2 = $pdo->prepare($sql2);
                $res2->execute(['login' => $qusr_login]);
                $dev2 = $res2->fetch();
                $_SESSION["qusr_id"] = $dev2['id'];
            else:
                $return = "Uživatel nemá na fóru aktivitu";
            endif;
        elseif ($result['user']['is_banned']):
            $return = "Uživatel je zablokován";
        else:
            $return = "Nesprávný email, nickname nebo heslo";
        endif;
    else:
        //echo 'HTTP Code: ' . $httpCode . "\n";
        //echo 'Full Response: ' . $response . "\n";  // Výpis celé odpovědi
        $decodedResponse = json_decode($response, true);
        $return = $decodedResponse['errors'][0]['message'];
    endif;

    return $return;
}

//funkce pro logovani prihlaseni
function zdef_tipovacka_users_log ($pdo, $login, $web): void
{
    $ip = $_SERVER["REMOTE_ADDR"];
    date_default_timezone_set('Europe/Prague');
    $datum = Date("d.m.Y-H.i.s");

    $sql = "INSERT INTO zdef_tipovacka_users_log (login, ip, datum, web) VALUES (:login, :ip, :datum, :web)";
    $res = $pdo->prepare($sql);
    $res->execute(['login' => $login, 'ip' => $ip, 'datum' => $datum, 'web' => $web]);
}

function sv_all ($pdo, $lang): array
{
    $sv[] = '';
    $sql = "SELECT * FROM stat_vyrazy WHERE valid = 1";
    $res = $pdo->prepare($sql);
    $res->execute();
    $stmt = $res->fetchAll();
    if ($lang == 'cz'):
        foreach ($stmt as $dev) {
            $sv[$dev["cislo"]] = stripslashes($dev['cz']);
        }
    else:
        foreach ($stmt as $dev) {
            $sv[$dev["cislo"]] = stripslashes($dev['en']);
        }
    endif;
    return $sv;
}

function sv ($pdo, $lang, $sv) :string
{
    if ($lang == 'cz'):
        $sql = "SELECT cz FROM stat_vyrazy WHERE cislo = :sv AND valid = 1";
        $res = $pdo->prepare($sql);
        $res->execute(['sv' => $sv]);
        $dev = $res->fetch();
        $sv = stripslashes($dev['cz']);
   else:
        $sql = "SELECT en FROM stat_vyrazy WHERE cislo = :sv AND valid = 1";
        $res = $pdo->prepare($sql);
        $res->execute(['sv' => $sv]);
        $dev = $res->fetch();
        $sv = stripslashes($dev['en']);
   endif;
   return $sv;
}

function st ($pdo, $lang, $st)
{
    if ($lang == 'cz'):
        $sql = "SELECT text_cz FROM stat_texty WHERE cislo = :st AND valid = 1";
        $res = $pdo->prepare($sql);
        $res->execute(['st' => $st]);
        $dev = $res->fetch();
        $st = stripslashes($dev['text_cz']);
    else:
        $sql = "SELECT text_en FROM stat_texty WHERE cislo = :st AND valid = 1";
        $res = $pdo->prepare($sql);
        $res->execute(['st' => $st]);
        $dev = $res->fetch();
        $st = stripslashes($dev['text_en']);
    endif;
    echo $st;
}

function get_date (): string
{
    return Date("d.m.Y");
}

function get_date_file ()
{
    return Date("d-m-Y");
}

function format_date_db ($datum): string
{
    if (preg_match('~^([0-9]+)\\.([0-9]+)\\.([0-9]+)$~', $datum, $match)):
        $datum_iso = sprintf("%d-%02d-%02d", $match[3], $match[2], $match[1]);
    else:
        $datum_iso = $datum;
    endif;
    return $datum_iso;
}

function format_date_www ($datum)
{
    return preg_replace('~^([0-9]+)-0?([0-9]+)-0?([0-9]+)~', '\\3.\\2.\\1', $datum);
}

function format_datetime_www ($datetime)
{
    return date('d.m.Y H:i:s', strtotime($datetime));
}

function format_datetimemin_www ($datetime)
{
    return date('d.m.Y H:i', strtotime($datetime));
}

function format_datetime_db ($datetime)
{
    return date("Y-m-d H:i:s",strtotime(str_replace('.','-',$datetime)));
}

//funkce pro prekodovani ceskych znaku
function text_str ($name)
{
$convertTable = array (
        'á' => 'a', 'Á' => 'A', 'ä' => 'a', 'Ä' => 'A', 'č' => 'c',
        'Č' => 'C', 'ď' => 'd', 'Ď' => 'D', 'é' => 'e', 'É' => 'E',
        'ě' => 'e', 'Ě' => 'E', 'ë' => 'e', 'Ë' => 'E', 'í' => 'i',
        'Í' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ľ' => 'l', 'Ľ' => 'L',
        'ĺ' => 'l', 'Ĺ' => 'L', 'ň' => 'n', 'Ň' => 'N', 'ń' => 'n',
        'Ń' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ö' => 'o', 'Ö' => 'O',
        'ř' => 'r', 'Ř' => 'R', 'ŕ' => 'r', 'Ŕ' => 'R', 'š' => 's',
        'Š' => 'S', 'ś' => 's', 'Ś' => 'S', 'ť' => 't', 'Ť' => 'T',
        'ú' => 'u', 'Ú' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ü' => 'u',
        'Ü' => 'U', 'ý' => 'y', 'Ý' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y',
        'ž' => 'z', 'Ž' => 'Z', 'ź' => 'z', 'Ź' => 'Z',
    );


$name_str = $name;
$name_str = strtr($name_str, $convertTable); 
$name_str = preg_replace('~[^\\pL0-9_.]+~u', '-', $name_str);
$name_str = trim($name_str, "-");
$name_str = iconv("utf-8", "us-ascii//TRANSLIT", $name_str);
$name_str = strtolower($name_str);
    return preg_replace('~[^-a-z0-9_.]+~', '', $name_str);
}

//funkce pro zjisteni hodnoty dle nazvu systemove promenne
function sp_hodnota ($pdo, $sp)
{
	$sql = "SELECT hodnota FROM settings WHERE name = :sp AND valid = 1";
    $res = $pdo->prepare($sql);
    $res->execute(['sp' => $sp]);
    $dev = $res->fetch();
	return $dev['hodnota'];
}

//funkce pro zjisteni hodnoty dle nazvu systemove promenne
function sp_hodnota_text ($pdo, $sp)
{
    $sql = "SELECT hodnota_text FROM settings WHERE name = :sp AND valid = 1";
    $res = $pdo->prepare($sql);
    $res->execute(['sp' => $sp]);
    $dev = $res->fetch();
    return $dev['hodnota_text'];
}

//funkce
function tusers_password_resend ($pdo, $qusr_login_reset) {
    $token = md5($qusr_login_reset).rand(10,9999);
    date_default_timezone_set('Europe/Prague');
    $exp_format = mktime(
        date("H")+4, date("i"), date("s"), date("m") ,date("d"), date("Y")
    );
    $exp_date = date("Y-m-d H:i:s",$exp_format);
    $sql = "UPDATE zdef_tipovacka_users SET reset_link_token = :token, exp_date = :exp_date WHERE login = :login OR email = :email AND blocked = 0 AND valid = 1";
    $res = $pdo->prepare($sql);
    $res->execute(['token'=>$token, 'exp_date'=>$exp_date, 'login'=>$qusr_login_reset, 'email'=>$qusr_login_reset]);

    $sql1 = "SELECT * FROM zdef_tipovacka_users WHERE login = :login OR email = :email AND blocked = 0 AND valid = 1";
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['login'=>$qusr_login_reset, 'email'=>$qusr_login_reset]);
    $dev1 = $res1->fetch();

    error_reporting(E_STRICT | E_ALL);
    require ROOT_DIR."/lib/PHPMailer/src/Exception.php";
    require ROOT_DIR."/lib/PHPMailer/src/PHPMailer.php";
    require ROOT_DIR."/lib/PHPMailer/src/SMTP.php";
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/cz/index?passchange=$token";

    $body1 = '
<!DOCTYPE html>
<html lang="en">
 <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title>Tipovačka hcpcefans.cz</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;">
<style>p {margin: 10px 5px;font-family:Calibri,Verdana,serif;font-size: 12px;}</style> 
<p>
    <strong>Z webu <a href="https://tipovacka.hcpcefans.cz">tipovacka.hcpcefans.cz</a> byl vygenerován požadavek na odeslání zapomenutého hesla.</strong>
</p>
<p>
    <strong>Login</strong>: '.$dev1["login"].'<br />
    <strong>E-Mail</strong>: '.$dev1["email"].'<br />
    <strong>Link pro generaci nového hesla:</strong> <a href="'.$url.'" title="Reset hesla">zde si resetujte Vašeho heslo</a> <br />
    <strong>Platnost:</strong> '.format_datetime_www($exp_date).'<br />
    <p>Pokud jsi požadavek neodeslal, omlouváme se. Děkujeme. <br />
    <strong>Tým fóra HCPCEFANS.cz</strong>
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
    //$mail->SMTPKeepAlive = true; //SMTP connection will not close after each email sent, reduces SMTP overhead
    $mail->Username = $config['smtp_user'];   // uživatelské jméno pro SMTP autentizaci
    $mail->Password = $config['smtp_password'];           // heslo pro SMTP autentizaci
    $mail->AltBody = "Pro zobrazení této zprávy použijte kompatibilní HTML prohlížeč.";
    $mail->CharSet = "utf-8";   // nastavíme kódování, ve kterém odesíláme e-mail

    try {
        //zprava z webu adminovi
        $mail->setFrom($config['smtp_from']);
        $mail->AddAddress($dev1["email"]);  // přidáme příjemce
        $mail->Subject = 'Obnova hesla tipovacka.hcpcefans.cz';    // nastavíme předmět e-mailu
        $mail->msgHTML($body1);
        $mail->send();
    } catch (Exception $e) {
        echo 'Mailer chyba (' . htmlspecialchars($dev1["email"]) . ') ' . $mail->ErrorInfo . '<br>';
    }

}

function galerie_view ($pdo, $lang, $galerie_id)
{
    $sql = "SELECT * FROM galerie WHERE id = :id AND valid = 1";
    $res = $pdo->prepare($sql);
    $res->execute(['id' => $galerie_id]);
    $dev = $res->fetch();

    if ($lang == 'en'):
        echo '<div class="border-0 fs-5 stattext mb-2">'.$dev['text_en'].'</div>';
    else:
        echo '<div class="border-0 fs-5 stattext mb-2">'.$dev['text_cz'].'</div>';
    endif;

    echo '
             <div ID="gallery" data-nanogallery2=\'{
             "itemsBaseURL": "/images/_galerie/'.$galerie_id.'-galerie/",
             "thumbnailWidth": "auto",
             "thumbnailHeight": 200,
             "thumbnailLabel": {"display": "false","position": "overImageOnBottom"},
             "thumbnailBorderHorizontal": 0,
             "thumbnailBorderVertical": 0,
             "thumbnailAlignment": "center",
             "thumbnailOpenImage": true,
             "viewerTheme": "dark"}\'>';


    $sql1 = 'SELECT * FROM galerie_photo WHERE valid = 1 AND galerie_id = :id ORDER BY poradi, id';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['id'=>$galerie_id]);
    $stmt = $res1->fetchAll();

    foreach ($stmt as $dev1)
    {
        if ($lang == 'en'):
            if ($dev1['nazev_en']==''): $nazev = $dev['nazev_en']; else: $nazev = $dev1['nazev_en']; endif;
        else:
            if ($dev1['nazev_cz']==''): $nazev = $dev['nazev_cz']; else: $nazev = $dev1['nazev_cz']; endif;
        endif;

        echo '<a href="'.$dev1['soubor'].'" data-ngthumb="'.$dev1['soubor'].'" data-ngdesc="" title="'.$nazev.'">'.$nazev.'</a>';
        }
    echo '</div>';

}