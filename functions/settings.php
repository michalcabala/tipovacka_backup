<?php
if (!isset($_GET['lang'])): 	 				//jazyk, ktery jsme zjistili od uzivatele, a ktery uzivatel pouziva
    $lang = 'cz';                               //defaultni jazyk, pokud neni zjisteny
	$zjisteny_jazyk = 'cz';
	$moje_jazyky = array(0 => 'cz', 'en');	 	//zjistime, jestli je zjisteny jazyk v podporovanych jazycich
	if (in_array($zjisteny_jazyk, $moje_jazyky)):		 //pokud ano, presmerujeme uzivatele na tuto jazykovou verzi
		Header('Location: https://'. $_SERVER['HTTP_HOST'].'/'.$zjisteny_jazyk.'/index');
	else:
		//pokud ne, presmerujme jej na defaultne nastaveny jazykovou mutaci
		Header('Location: https://'.$_SERVER['HTTP_HOST'].'/'.$moje_jazyky[0].'/index');
	endif;
else: 
	$lang = $_GET['lang'];
endif;
