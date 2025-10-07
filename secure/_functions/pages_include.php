<?php
global $pdo;
$main_menu = 0;
$second_menu = 0;
$sec_text = 0;
$en = en_on($pdo);
$section = $page = $sec_page = $sec_text = "";

if(isset($_SESSION["qn_user"])):	
	$qn_user = $_SESSION["qn_user"];
	$sql = "SELECT name, prava FROM users WHERE login=:qn_user";
	$res = $pdo->prepare($sql);
	$res->execute(['qn_user' => $qn_user]);
	$dev = $res->fetch();
		$_SESSION["user_name"] = $dev["name"];
		$_SESSION["user_prava"] = $dev["prava"];
else:	
	$user = "";		
endif;

$user_prava = $_SESSION["user_prava"];

if ($_SESSION["user_prava"] == 1): //nejvyssi opravneni, systemovy uzivatel
	$section = $_GET['section'] ?? '03';
    $page = $_GET['page'] ?? '01';
    $sec_page = $_GET['sec_page'] ?? '01';
elseif($_SESSION["user_prava"] == 2): //nejvyssi opravneni, bezny uzivatel
	$section = $_GET['section'] ?? '03';
    $page = $_GET['page'] ?? '01';
    $sec_page = $_GET['sec_page'] ?? '01';
endif;

switch ($section)
{
	case "01": $main_menu = "mm_all";
	switch ($page)
	{	case "01":
        switch ($sec_page)
        {	case "02":	$sec_text = "pages/news/news_vypis";				break;
            case "03":	$sec_text = "pages/news/news_typ";					break;
            case "05":	$sec_text = "pages/news/news_users";				break;
            case "06":	$sec_text = "pages/news/news_info_send";			break;}
        break;
		case "02":
			switch ($sec_page)
			{	case "02":	$sec_text = "pages/stattexty/stattexty_vypis";		break;
				case "03":	$sec_text = "pages/stattexty/statvyrazy_vypis";		break;}
		break;
        case "03":
            switch ($sec_page)
            {	case "02":	$sec_text = "pages/galerie/galerie_vypis";			break;
                case "03":	$sec_text = "pages/galerie/galerie_typ";			break;
                case "05":	$sec_text = "pages/galerie/galerie_photo";			break;
                case "06":	$sec_text = "pages/galerie/galerie_photo_add";		break;}
            break;
        case "04":
            switch ($sec_page)
            {	case "02":	$sec_text = "pages/blog/blog_vypis";			break;
                case "03":	$sec_text = "pages/blog/blog_kat";  			break;}
            break;
		case "09":
			switch ($sec_page)
			{	case "02":	$sec_text = "pages/contacts_lide_vypis";	break;
				case "03":	$sec_text = "pages/contacts_lide_cat";		break;
                case "52":  $sec_text = "pages/kontakty/dotazy_vypis";  break;
                case "53":  $sec_text = "pages/kontakty/dotazy_kat";    break;}
			break;
        //project pages
		case "51":
			switch ($sec_page)
			{	case "02":	$sec_text = "pages/tipovacka/tipovacka_vypis";		break;
				case "03":	$sec_text = "pages/tipovacka/tipovacka_teams";		break;
                case "04":	$sec_text = "pages/tipovacka/tipovacka_users";		break;
                case "05":	$sec_text = "pages/tipovacka/tipovacka_zapasy";		break;
                case "06":	$sec_text = "pages/tipovacka/tipovacka_users_rel";  		break;
                case "11":	$sec_text = "pages/tipovacka/tipovacka_tips_zapasy";		break;
                case "12":	$sec_text = "pages/tipovacka/tipovacka_tips_poradi";		break;}
			break;
	}
	break;
	case "02": $main_menu = "mm_system";
	switch ($page)
	{	case "01":
			switch ($sec_page)
			{	case "02":	$sec_text = "settings/users_vypis";			break;
				case "03":	$sec_text = "settings/users_skup";			break;
				case "05":	$sec_text = "settings/users_log";			break;}
		break;
		case "02":
			switch ($sec_page)
			{	case "01":	$sec_text = "settings/system_add";			break;
				case "02":	$sec_text = "settings/system_vypis";		break;
				case "03":	$sec_text = "settings/menu_vypis";			break;
				case "04":	$sec_text = "settings/menu_users_skup";		break;}
		break;}
	break;
	case "03": $main_menu = "mm_dashboard";
	switch ($page)
	{	case "01":
			switch ($sec_page)
			{	case "01":	$sec_text = "dashboard/dashboard_main";		break;}
		break;}
	break;}

					