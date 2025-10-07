<?php
$section = $_GET['section'] ?? 'home';
$category = $_GET['category'] ?? '';
$text = $_GET['text'] ?? '';
$menu = $stattext = $pagetitle = $thirdmenu = 0;
$sv = sv_all($pdo, $lang);

//echo 'category '.$category;
//echo '<br/>section '.$section;
//echo '<br/>text '.$text;

switch ($section)
{
//pro cesky jazyk
    case "home":	    $page = "home";         $pagetitle = 100; $menu = 200;	break;
    case "news":	    $page = "news";         $pagetitle = 110; $menu = 210;
        switch ($category)
        {   case "": $secmenu = 210; break;
            case "obecne": $secmenu = 211; break;
            case "tipovacky": $secmenu = 212; break;}
        break;
    case "tipovacky":
        $pagetitle = 120; $menu = 220;
        if ($category == ''): $page = "tipovacky"; else: $page = "tipovackydet"; $tipid = $category; endif;
        switch ($text)
        {	case "zapasy":	    $sec_text = "zapasy";           $thirdmenu = 2210;   	break;
            case "tabulka": 	$sec_text = "tabulka";          $thirdmenu = 2220;	    break;
            case "otazky":	    $sec_text = "otazky";	        $thirdmenu = 2230;      break;
            case "vysledky":	$sec_text = "vysledky";	        $thirdmenu = 2250;      break;
            case "zapasyupl":	$sec_text = "zapasy_uplynule";  $thirdmenu = 2260;		break;
            case "stats":	    $sec_text = "stats";            $thirdmenu = 2280;      break;
            case "pravidla":	$sec_text = "pravidla";         $thirdmenu = 2290;      break;}
        break;
    case "kontakt":	            $page = "kontakt";      $pagetitle = 140; $menu = 240;	break;
    case "pravidlauzivani":     $page = "text";         $pagetitle = 198; $menu = 298;  $stattext = 980;	break;
    case "cookies":             $page = "text";         $pagetitle = 299; $menu = 299;  $stattext = 990;	break;
//pro english

}

