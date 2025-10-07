<?php
//funkce pro logovani prihlaseni
function users_log ($pdo, $login, $web): void
{
    $ip = $_SERVER["REMOTE_ADDR"];
    date_default_timezone_set('Europe/Prague');
    $datum = Date("d.m.Y-H.i.s");

    $sql = "INSERT INTO users_log (login, ip, datum, web) VALUES (:login, :ip, :datum, :web)";
    $res = $pdo->prepare($sql);
    $res->execute(['login' => $login, 'ip' => $ip, 'datum' => $datum, 'web' => $web]);

}

//funkce pro zjisteni podpory en v administraci
function en_on ($pdo)
{
	$sql = "SELECT hodnota FROM settings WHERE name = 'admin_en' AND valid = 1";
	$res = $pdo->prepare($sql);
    $res->execute();
	return $res->fetchColumn();
}

function get_date (): string
{
    return Date("d.m.Y");
}

function get_date_file (): string
{
    return Date("d-m-Y");
}

function format_date_db ($datum)
{
    if (preg_match('~^([0-9]+)\\.([0-9]+)\\.([0-9]+)$~', $datum, $match)):
        $datum_iso = sprintf("%d-%02d-%02d", $match[3], $match[2], $match[1]);
    else:
        $datum_iso = $datum;
    endif;

return $datum_iso;
}

function format_date_www ($datum): array|string|null
{
    return preg_replace('~^([0-9]+)-0?([0-9]+)-0?([0-9]+)~', '\\3.\\2.\\1', $datum);
}

function format_datetime_www ($datetime): string
{
    return date('d.m.Y H:i:s', strtotime($datetime ?? '') );
}

function format_datetime_db ($datetime): string
{
    return date("Y-m-d H:i:s",strtotime(str_replace('.','-',$datetime)));
}

//funkce pro prekodovani ceskych znaku
function text_str ($name): array|string|null
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

//funkce pro generaci hesla
function generace_hesla($length): string
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);

    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }

    return $result;
}

//funkce pro přehled hodnoty 0/1 na NE/ANO
function anone ($hodnota): string
{
    if ($hodnota == 0):
        $result = "NE";
    else:
        $result = "ANO";
    endif;
    return $result;
}