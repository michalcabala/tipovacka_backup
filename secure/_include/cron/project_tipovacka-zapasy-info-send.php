<?php
//https://tipovacka.hcpcefans.local/secure/_include/cron/project_tipovacka-zapasy-info-send.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
define('SEC_DIR', realpath(__DIR__.'/../..'));
define('ROOT_DIR', realpath(__DIR__.'/../../..'));


if ($_SERVER["SERVER_ADDR"]=="127.0.0.1" OR $_SERVER["SERVER_ADDR"]=="::1"):
    $config = parse_ini_file(SEC_DIR."/ini/config_local.ini");
    $host = $config['host'];
    $db   = $config['dbname'];
    $user = $config['user'];
    $pass = '';
    $charset = 'utf8mb4';
else:
    $config = parse_ini_file(SEC_DIR."/ini/config.ini");
    $host = $config['host'];
    $db   = $config['dbname'];
    $user = $config['user'];
    $pass = $config['password'];
    $charset = 'utf8mb4';
endif;

//pripojeni k databazovemu systemu
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
date_default_timezone_set('Europe/Prague');
$ted = date('Y-m-d 00:00:00', time());
$zitra = date('Y-m-d 00:00:00', strtotime('+1 day', time()));

$sql1 = 'SELECT count(*) FROM zdef_tipovacka_zapasy WHERE valid = 1 AND datetime >:ted AND datetime <:zitra AND tip = 99';
$res1 = $pdo->prepare($sql1);
$res1->execute(['ted'=>$ted, 'zitra'=>$zitra]);
$dev1 = $res1->fetchColumn() ?? 0;

if ($dev1 <> 0):
    $sql2 = 'SELECT DISTINCT tipovacka_id FROM zdef_tipovacka_zapasy WHERE valid = 1 AND datetime >= :ted AND datetime <= :zitra AND tip = 99';
    $res2 = $pdo->prepare($sql2);
    $res2->execute(['ted'=>$ted, 'zitra'=>$zitra]);
    $stmt2 = $res2->fetchAll();

    error_reporting(E_STRICT | E_ALL);
    require ROOT_DIR."/lib/PHPMailer/src/Exception.php";
    require ROOT_DIR."/lib/PHPMailer/src/PHPMailer.php";
    require ROOT_DIR."/lib/PHPMailer/src/SMTP.php";

    if ($_SERVER["SERVER_ADDR"]=="127.0.0.1" OR $_SERVER["SERVER_ADDR"]=="::1"):
        $config = parse_ini_file(SEC_DIR."/ini/config_local.ini");
    else:
        $config = parse_ini_file(SEC_DIR."/ini/config.ini");
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

    foreach ($stmt2 as $dev2)
    {
        $sql3 = 'SELECT ztz.id as id, ztz.skupina as skupina, ztz.team1_id as team1_id, ztz.team2_id as team2_id, ztz.datetime as datetime, ztz.datetime_end as datetime_end,
                    ztt1.nazev_cz as team1, ztt2.nazev_cz as team2, ztt1.image as image1, ztt2.image as image2
                    FROM zdef_tipovacka_zapasy ztz 
                    LEFT JOIN zdef_tipovacka_teams ztt1 on ztz.team1_id = ztt1.id  
                    LEFT JOIN zdef_tipovacka_teams ztt2 on ztz.team2_id = ztt2.id
                    WHERE ztz.tipovacka_id = :tipovacka_id AND ztz.valid = 1 AND ztz.datetime >= :ted AND ztz.datetime <= :zitra AND tip = 99 ORDER BY ztz.datetime, ztz.poradi';
        $res3 = $pdo->prepare($sql3);
        $res3->execute(['tipovacka_id'=>$dev2['tipovacka_id'], 'ted'=>$ted, 'zitra'=>$zitra]);
        $stmt3 = $res3->fetchAll();

        $sql4 = 'SELECT nazev_cz FROM zdef_tipovacka WHERE id = :tipovacka_id AND valid = 1';
        $res4 = $pdo->prepare($sql4);
        $res4->execute(['tipovacka_id'=>$dev2['tipovacka_id']]);
        $dev4 = $res4->fetch();

        $table1 = '<table style="margin:10px;">
                    <tr><th style="background-color: #87cefa; padding: 5px; font-size: 10px;">Datum a čas</th>
	                <th style="background-color: #87cefa; padding: 5px; font-size: 10px;">&nbsp;</th>
	                <th style="background-color: #87cefa; padding: 5px; font-size: 10px;">Domácí</th>
	                <th style="background-color: #87cefa; padding: 5px; font-size: 10px;"></th>
	                <th style="background-color: #87cefa; padding: 5px; font-size: 10px;">Hosté</th>
	                <th style="background-color: #87cefa; padding: 5px; font-size: 10px;">&nbsp;</th>
	                <th style="background-color: #87cefa; padding: 5px; font-size: 10px;">Konec tipu</th></tr>';
        foreach ($stmt3 as $dev3)
        {
            $datum = date('d.m.Y H:i', strtotime($dev3['datetime']));
            $datum_end = date('d.m.Y H:i', strtotime($dev3['datetime_end']));
            $table1.= '<tr>
                <td>'.$datum.'</td>
                <td style="padding: 5px 10px;"><img src="https://tipovacka.hcpcefans.cz/files/images/teams/small/'.$dev3['image1'].'" alt="'.$dev3['team1'].'"></td>
                <td><strong>'.$dev3['team1'].'</strong></td>
                <td> : </td>
                <td><strong>'.$dev3['team2'].'</strong></td>
                <td style="padding: 5px 10px;"><img src="https://tipovacka.hcpcefans.cz/files/images/teams/small/'.$dev3['image2'].'" alt="'.$dev3['team2'].'" ></td>
                <td>'.$datum_end.'</td>
                </tr>';
        }
        $table1.= '</table>';
        $body1 = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml" lang="cs">
                 <head>
                  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                  <title>Tipovačka HCPCEFANS</title>
                  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                    </head>
                    <body style="margin: 10px; padding: 0;">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="700">
                     <tr><td align="center" bgcolor="#ffffff" style="padding: 0 0 0 0;">
                            <img src="https://tipovacka.hcpcefans.cz/images/_design/kontakt_left-image.png" alt="Tipovačka HCPCEFANS.cz" width="600px" style="display: block;" />
                        </td></tr>
                        <tr><td align="center" style="padding: 10px;">Dnes se hrají tyto zápasy tipovačky <strong>'.$dev4['nazev_cz'].'</strong>, tak nezapomeňte tipovat na <a href="https://tipovacka.hcpcefans.cz">https://tipovacka.hcpcefans.cz</a></td></tr>
                        <tr><td>'.$table1.'</td></tr>
                        <tr><td align="center" style="padding: 10px;">Pokud si nepřejete dostávat upozornění, změňte si Vaše nastavení v sekci Kontakt na <a href="https://tipovacka.hcpcefans.cz">https://tipovacka.hcpcefans.cz</a> a zrušte si volbu Posílat info.</td></tr>
                    </table>
                    </body>
                    </html>';

        $sql5 = 'SELECT ztu.email as email FROM zdef_tipovacka_users ztu 
                 LEFT JOIN zdef_tipovacka_users_rel ztur on ztu.id = ztur.user_id
                 WHERE ztu.info_send = 1 AND ztur.registered = 1 AND ztur.tipovacka_id = :tipovacka_id';
        $res5 = $pdo->prepare($sql5);
        $res5->execute(['tipovacka_id'=>$dev2['tipovacka_id']]);
        $stmt5 = $res5->fetchAll();

        foreach ($stmt5 as $dev5)
        {
            try {
                $mail->addAddress($dev5['email']);
            } catch (Exception $e) {
                echo 'Chybná adresa přeskočena: ' . htmlspecialchars($dev5['email']) . '<br>';
                continue;
            }
            try {
                $mail->setFrom($config['smtp_from']);
                $mail->Subject = 'Dnešní zápasy '.$dev4['nazev_cz'];    // nastavíme předmět e-mailu
                $mail->msgHTML($body1);
                $mail->send();
                echo 'Zpráva odeslána na: ' . htmlspecialchars($dev5['email']) . '<br>';
            } catch (Exception $e) {
                echo 'Mailer chyba (' . htmlspecialchars($dev5['email']) . ') ' . $mail->ErrorInfo . '<br>';
                //Reset the connection to abort sending this message
                //The loop will continue trying to send to the rest of the list
                $mail->getSMTPInstance()->reset();
            }
            //Clear all addresses and attachments for the next iteration
            $mail->clearAddresses();
            $mail->clearAttachments();
        }
    }
else:
    echo 'Není žádný zápas k dispozici';
endif;

