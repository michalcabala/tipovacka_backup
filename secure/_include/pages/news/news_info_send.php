<?php
global $pdo;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$send = $_GET['send'] ?? 0;

    $sql1 = "SELECT * FROM news WHERE id = :send";
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['send'=>$send]);
    $dev1 = $res1->fetch();

    $sql2 = "SELECT nazev_cz FROM news_typ WHERE id = :news_typ";
    $res2 = $pdo->prepare($sql2);
    $res2->execute(['news_typ'=>$dev1['news_typ']]);
    $dev2 = $res2->fetch();

    $year = date("Y");
    $body1 = '
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" lang="cs">
         <head>
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
          <title>TIPOVAČKA HCPCEFANS :: newsletter</title>
          <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            </head>
            <body style="margin: 0; padding: 0;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="700">
             <tr><td align="center" bgcolor="#ffffff" style="padding: 0 0 0 0;">
                    <a href="https://' . $_SERVER["SERVER_NAME"] . '/cz/index/news/' . $dev1["url_cz"] . '" style="font-family: Calibri, sans-serif; font-size: 12px;">Zobrazte si novinku v prohlížeči</a>
                    
               </td></tr>
               <tr><td bgcolor="#ffffff" style="padding: 0 20 0 20; color: #4c4c4c; font-family: Calibri, sans-serif; font-size: 12px;"><br />
       ' . stripslashes($dev1["text_cz"]) . '
             </td></tr>
             <tr><td bgcolor="#ee4c50" style="padding: 0 0 20 30;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                     <tr><td style="color: #ffffff; font-family: Calibri, sans-serif; font-size: 14px;">
                        &reg; TIPOVACKA.HCPCEFANS.CZ :: ' . $year . '<br/>
                        </td><td align="right">
                        </td></tr>
                    </table>
                </td></tr>
            </table>
            </body>
            </html>
';

    date_default_timezone_set('Europe/Prague');
    error_reporting(E_STRICT | E_ALL);
    require SEC_DIR."/vendor/phpmailer/src/Exception.php";
    require SEC_DIR."/vendor/phpmailer/src/PHPMailer.php";
    require SEC_DIR."/vendor/phpmailer/src/SMTP.php";
    $emails_send = 0;

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
    $mail->SMTPKeepAlive = true; //SMTP connection will not close after each email sent, reduces SMTP overhead
    $mail->Username = $config['smtp_user'];   // uživatelské jméno pro SMTP autentizaci
    $mail->Password = $config['smtp_password'];           // heslo pro SMTP autentizaci
    $mail->AltBody = "Pro zobrazení této zprávy použijte kompatibilní HTML prohlížeč.";
    $mail->CharSet = "utf-8";   // nastavíme kódování, ve kterém odesíláme e-mail

    //$sql3 = "SELECT * FROM news_users WHERE valid = 1 AND registered = 1";
    $sql3 = "SELECT email FROM zdef_tipovacka_emails";
    //$sql3 = "SELECT DISTINCT ztu.email as email FROM zdef_tipovacka_users_rel ztur INNER JOIN zdef_tipovacka_users ztu on ztu.id = ztur.user_id WHERE ztu.valid = 1 AND ztur.valid = 1 AND ztu.blocked = 0";
    $res3 = $pdo->prepare($sql3);
    $res3->execute();
    $stmt3 = $res3->fetchAll();

    foreach ($stmt3 as $row3) {
        try {
            $mail->addAddress($row3['email']);
        } catch (Exception $e) {
            echo 'Chybná adresa přeskočena: ' . htmlspecialchars($row3['email']) . '<br>';
            continue;
        }
        try {
            $mail->setFrom($config['smtp_from']);
            $mail->Subject = 'Novinky z tipovaček: '.$dev1['nazev_cz'];    // nastavíme předmět e-mailu
            $mail->msgHTML($body1);
            $mail->send();
            //echo 'Zpráva odeslána na: ' . htmlspecialchars($row['email']) . '<br>';
        } catch (Exception $e) {
            echo 'Mailer chyba (' . htmlspecialchars($row3['email']) . ') ' . $mail->ErrorInfo . '<br>';
            //Reset the connection to abort sending this message
            //The loop will continue trying to send to the rest of the list
            $mail->getSMTPInstance()->reset();
        }
        //Clear all addresses and attachments for the next iteration
        $mail->clearAddresses();
        $mail->clearAttachments();
        $emails_send = $emails_send + 1;
    }

    $date_info_send = format_date_db(get_date());
    $sql4 = "UPDATE news SET info_send = :date_info_send WHERE id = :id";
    $res4 = $pdo->prepare($sql4);
    try {
        $res4->execute(['date_info_send' => $date_info_send, 'id' => $send]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Zpráva odeslána na tento počet e-mailů: ' . $emails_send . '</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Zpráva nebyla odeslána.</span></a>';
        echo $error;
    }
