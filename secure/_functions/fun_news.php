<?php
function news_typ_add ($pdo, $nazev_cz, $nazev_en, $poradi, $popis_cz, $popis_en, $page_cz, $page_en, $color)
{
    $nazev_cz = addslashes($nazev_cz);
    $nazev_en = addslashes($nazev_en);
    $popis_cz = addslashes($popis_cz);
    $popis_en = addslashes($popis_en);
    $color = addslashes($color);
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO news_typ (poradi, nazev_cz, nazev_en, popis_cz, popis_en, page_cz, page_en, color, user_i, user_u) VALUES 
		(:poradi, :nazev_cz, :nazev_en, :popis_cz, :popis_en, :color, :page_cz, :page_en, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['poradi'=>$poradi, 'nazev_cz'=>$nazev_cz, 'nazev_en'=>$nazev_en, 'popis_cz'=>$popis_cz, 'popis_en'=>$popis_en, 'color'=>$color,
            'page_cz'=>$page_cz, 'page_en'=>$page_en, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Typ novinky nebyl vložen</span></a>';
        echo $error;
    }
}

function news_typ_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT * FROM news_typ WHERE valid = :valid ORDER BY poradi LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
	echo '<tr>
        	<td>'.$dev["id"].'</td>
            <td>'.stripslashes($dev["nazev_cz"]).'</td>
            <td>'.stripslashes($dev["page_cz"]).'</td>
            <td>'.$dev["poradi"].'</td>
            <td>'.$dev["color"].'</td>
            <td class="text-center">
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=01&amp;sec_page=03&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></a></td>
            <td class="text-center">
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=01&amp;sec_page=03&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function news_typ_edit ($pdo, $id, $nazev_cz, $nazev_en, $poradi, $popis_cz, $popis_en, $page_cz, $page_en, $color, $valid)
{
    $nazev_cz = addslashes($nazev_cz);
    $nazev_en = addslashes($nazev_en);
    $popis_cz = addslashes($popis_cz);
    $popis_en = addslashes($popis_en);
    $color = addslashes($color);
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE news_typ SET poradi = :poradi, nazev_cz = :nazev_cz, nazev_en = :nazev_en, popis_cz = :popis_cz, popis_en = :popis_en, page_cz = :page_cz, page_en = :page_en, color = :color,
		valid = :valid, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['poradi'=>$poradi, 'nazev_cz'=>$nazev_cz, 'nazev_en'=>$nazev_en, 'popis_cz'=>$popis_cz, 'popis_en'=>$popis_en, 'page_cz'=>$page_cz, 'page_en'=>$page_en, 'color'=>$color, 'valid'=>$valid,
        'qn_user_u'=>$qn_user, 'id'=>$id]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not edited: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Typ novinek nebyl uložen</span></a>';
        echo $error;
    }
}

function news_typ_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE news_typ SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Typ novinky byl smazán</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Typ novinky nebyl smazán</span></a>';
        echo $error;
    }
}

function news_typ_option_form ($pdo, $select)
{
    $sql = "SELECT id, nazev_cz FROM news_typ WHERE valid = 1 ORDER BY poradi";
    $res = $pdo->prepare($sql);
    $res->execute();
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        $id = $dev['id'];
        $nazev_cz = stripslashes($dev['nazev_cz']);
        if ($select == $id):
            echo '<option value='.$id.' selected="selected">'.$id.'&nbsp;-&nbsp;'.$nazev_cz.'</option>';
        else:
            echo '<option value='.$id.'>'.$id.'&nbsp;-&nbsp;'.$nazev_cz.'</option>';
        endif;
        echo "\n";
    }
}

function news_add ($pdo, $datum, $news_typ, $nazev_cz, $perex_cz, $text_cz, $galerie_id, $visible, $soubor)
{
    $nazev_cz = addslashes($nazev_cz);
    $text_cz = addslashes($text_cz);
    $url_cz = text_str(addslashes($nazev_cz)).'-'.$datum;
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO news (datum, url_cz, news_typ, nazev_cz, perex_cz, text_cz, galerie_id, visible, user_i, user_u) VALUES (:datum, :url_cz, :news_typ, :nazev_cz, :perex_cz, :text_cz, :galerie_id, :visible, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['datum'=>$datum, 'url_cz'=>$url_cz, 'news_typ'=>$news_typ, 'nazev_cz'=>$nazev_cz, 'perex_cz'=>$perex_cz, 'text_cz'=>$text_cz, 'galerie_id'=>$galerie_id, 'visible'=>$visible, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        if($soubor <> ""):
            $dir_original = '../files/images/news_ico/';
            $dir_small = '../files/images/news_ico/small/';
            $file_orig = ''.$dir_original.''.$soubor.'';
            $file_small = ''.$dir_small.''.$soubor.'';
            //* vytvoreni originalu
            list($width, $height) = create_thumbnail($file_orig, sp_hodnota($pdo, 'pic_news_orig_width'), sp_hodnota($pdo, 'pic_news_orig_height'));
            if ($width && $height):
                image_resize($pdo, $file_orig, $width, $height);
            endif;

            //* vytvoreni thumbnailu
            list($width, $height) = create_thumbnail($file_small, sp_hodnota($pdo, 'pic_news_small_width'), sp_hodnota($pdo, 'pic_news_small_height'));
            if ($width && $height):
                image_resize($pdo, $file_small, $width, $height);
            endif;
        else:
            echo 'Soubor nebyl připojen, bude použit defaultní.<br />';
        endif;
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Novinka nebyla vložena</span></a>';
        echo $error;
    }
}

//funkce pro zjisteni max id v novinkach
function news_maxid ($pdo)
{
	$sql = "SELECT MAX(id) FROM news WHERE valid = 1";
    $res = $pdo->prepare($sql);
    $res->execute();
	return $res->fetchColumn();
}

//funkce pro pridani fotografie k novince
function news_photo_add ($pdo, $news_maxid)
{
    $dir_original = '../files/images/news_ico/';
    $dir_small = '../files/images/news_ico/small/';

    if ($_FILES['userfile']['error'] == UPLOAD_ERR_NO_FILE):
        $soubor_str = "";
    else:
	    $soubor_str = text_str($_FILES['userfile']['name']);
	    if(move_uploaded_file($_FILES['userfile']['tmp_name'], ''.$dir_original.''.$soubor_str.'' )):
	        copy (''.$dir_original.''.$soubor_str.'',''.$dir_small.''.$soubor_str.'' );
	    else:
    		echo "Nastala chyba, zkuste upload znova";
	    endif;

	    $sql = "UPDATE news SET news_ico = :soubor_str WHERE id = :news_maxid";
        $res = $pdo->prepare($sql);
        try {
            $res->execute(['soubor_str'=>$soubor_str, 'news_maxid'=>$news_maxid]);
            echo '<span class="warning">Ikona novinky byla úspěšně uložena</span><br />';

        }
        catch (PDOException $e){
            $error = 'Data not updated: '. $e->getMessage();
            echo '<span class="warning">Ikona novinky nebyla uložena</span><br />';
            echo $error;
        }
    endif;
    return $soubor_str;
}

function news_edit ($pdo, $id, $datum, $news_typ, $nazev, $perex, $text, $galerie_id, $visible, $lang, $url, $valid, $soubor)
{
    $nazev = addslashes($nazev);
    $text = addslashes($text);
    $qn_user = $_SESSION["qn_user"];

    if ($lang == "cz"):
	    $sql = "UPDATE news SET url_cz = :url, datum = :datum, news_typ = :news_typ, nazev_cz = :nazev, perex_cz = :perex, text_cz = :text, galerie_id = :galerie_id, visible = :visible, 
                valid = :valid, user_u = :qn_user_u WHERE id = :id";
    else:
        $sql = "UPDATE news SET url_en = :url, datum = :datum, news_typ = :news_typ, nazev_en = :nazev, perex_en = :perex, text_en = :text, galerie_id = :galerie_id, visible = :visible, 
                valid = :valid, user_u = :qn_user_u WHERE id = :id";
    endif;
    $res = $pdo->prepare($sql);

    try {
        $res->execute(['url'=>$url, 'datum'=>$datum, 'news_typ'=>$news_typ, 'nazev'=>$nazev, 'perex'=>$perex, 'text'=>$text, 'galerie_id'=>$galerie_id, 'visible'=>$visible,
           'valid'=>$valid, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        if($soubor <> ""):
            $dir_original = '../files/images/news_ico/';
            $dir_small = '../files/images/news_ico/small/';
            $file_orig = ''.$dir_original.''.$soubor.'';
            $file_small = ''.$dir_small.''.$soubor.'';
            //* vytvoreni originalu
            list($width, $height) = create_thumbnail($file_orig, sp_hodnota($pdo,'pic_news_orig_width'), sp_hodnota($pdo, 'pic_news_orig_height'));
            if ($width && $height):
                image_resize($pdo, $file_orig, $width, $height);
            endif;

            //* vytvoreni thumbnailu
            list($width, $height) = create_thumbnail($file_small, sp_hodnota($pdo, 'pic_news_small_width'), sp_hodnota($pdo, 'pic_news_small_height'));
            if ($width && $height):
                image_resize($pdo, $file_small, $width, $height);
            endif;
        else:
            echo 'Soubor nebyl připojen, bude použit defaultní.<br />';
        endif;
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not updated: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Novinka nebyla uložena</span></a>';
        echo $error;
    }
}

function news_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT n.id, n.url_cz, n.nazev_cz, n.datum, n.news_ico, n.news_typ, n.galerie_id, n.visible, nt.nazev_cz as typ, n.info_send FROM news n, news_typ nt WHERE nt.id = n.news_typ AND n.valid = :valid ORDER BY n.datum DESC, n.id DESC LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        if($dev["news_ico"] == ""):
            $news_ico = 'NE';
            $news_ico_odkaz = '';
        else:
            $news_ico = 'ANO';
            $news_ico_odkaz = '<a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=01&amp;sec_page=02&amp;icon='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-icons"></i></a>';
        endif;
        if($dev["galerie_id"] == 0): 	$galerie_id = 'NE'; else: $galerie_id = $dev["galerie_id"]; endif;
        if($dev["visible"] == 0):       $visible = 'NE'; elseif($dev["visible"] == 1): $visible = "CZ/EN"; elseif($dev["visible"] == 2): $visible = "CZ"; elseif($dev["visible"] == 3): $visible = "EN"; else: $visible=''; endif;
        if(en_on($pdo)==0):
            $en_edit = "";
        else:
            $en_edit='<a class="btn btn-primary btn-circle btn-sm" href="index.php?section=01&amp;page=01&amp;sec_page=02&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;lang=en&amp;show=2">
                <i class="fas fa-edit"></i></a>';
        endif;
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/cz/index/news/".$dev["url_cz"];
        if($dev["info_send"]== '0000-00-00'): $info_send = "NE"; else: $info_send = format_date_www($dev["info_send"]); endif;

        echo '
        <tr>
            <td>'.$dev["id"].'</td>
            <td>'.stripslashes($dev["typ"]).'</td>
            <td>'.stripslashes($dev["nazev_cz"]).'</td>
            <td>'.format_date_www($dev["datum"]).'</td>
            <td>'.$news_ico.'</td>
            <td>'.$galerie_id.'</td>
            <td>'.$visible.'</td>
            <td>'.$info_send.'</td>
            <td class="text-center"> <!-- nahled -->
                <a class="btn btn-primary btn-circle btn-sm" href="'.$url.'" target="_blank">
                <i class="fas fa-external-link-alt"></i></a></td>
            <td class="text-center"> <!-- editace EN -->'.$en_edit.'</td>
            <td class="text-center"> <!-- upravit -->
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=01&amp;sec_page=02&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></a></td>
            <td class="text-center"> <!-- odeslat news -->
                <a class="btn btn-warning btn-circle btn-sm" href="index.php?section=01&amp;page=01&amp;sec_page=06&amp;send='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-share"></i></a></td>
            <td class="text-center"> <!-- smazat ikonu -->
                '.$news_ico_odkaz.'
                </td>
            <td class="text-center"> <!-- smazat -->
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=01&amp;sec_page=02&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function news_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE news SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Novinka byla smazána</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Novinka nebyla smazána</span></a>';
        echo $error;
    }
}

//funkce pro smazani fotografie
function news_ico_delete ($pdo, $ico_del)
{

	$sql = "SELECT news_ico FROM news WHERE id = :ico_del";
    $res = $pdo->prepare($sql);
    $res->execute(['ico_del'=>$ico_del]);
    $dev = $res->fetchColumn();

	$soubor = stripslashes($dev['news_ico']);
	$delete_ico = unlink('../files/images/news_ico/'.$soubor.'');
	$delete_ico_small = unlink('../files/images/news_ico/small/'.$soubor.'');

    if ($delete_ico):
	    echo '<span class="warning">Originál ikony smazán</span><br />';
    endif;
    if ($delete_ico_small):
	    echo '<span class="warning">Thumbnail ikony smazán</span><br />';
    endif;

    $sql1 = 'UPDATE news SET news_ico = :news_ico WHERE id = :ico_del';
    $res1 = $pdo->prepare($sql1);
    $news_ico = "";
    $res1->execute(['news_ico'=>$news_ico, 'ico_del'=>$ico_del]);
}

//funkce pro pridani uzivatele novinek
function news_users_add ($pdo, $name, $email)
{
    $name = addslashes($name);
    $email = addslashes($email);
    $datum_od = format_date_db(get_date());
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO news_users (name, email, datum_od, registered, user_i, user_u) VALUES 
		(:name, :email, :datum_od, 1, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['name'=>$name, 'email'=>$email, 'datum_od'=>$datum_od, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Uživatel novinky nebyl vložen</span></a>';
        echo $error;
    }
}

//funkce pro vymazani uzivatele prihlaseneho k odberu novinek
function news_users_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE news_users SET registered = 0, valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Uživatel byl smazán</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Uživatel nebyl smazán</span></a>';
        echo $error;
    }
}

//funkce pro ukonceni odberu uzivatele prihlaseneho k odberu novinek
function news_users_end ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];
    $datum_do = format_date_db(get_date());

    $sql = "UPDATE news_users SET datum_do = :datum_do, registered = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['datum_do'=>$datum_do, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Uživatel byl ukončen</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not edited: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Uživatel nebyl ukončen</span></a>';
        echo $error;
    }
}

//funkce pro ukonceni odberu uzivatele prihlaseneho k odberu novinek
function news_users_renew ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];
    $datum_od = format_date_db(get_date());
    $datum_do = '0000:00:00';

    $sql = 'UPDATE news_users SET datum_od = :datum_od, registered = 1, datum_do = :datum_do, valid = 1, user_u = :qn_user_u WHERE id = :id';
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['datum_od'=>$datum_od, 'datum_do'=>$datum_do, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Uživatel byl obnoven</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not edited: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Uživatel nebyl obnoven</span></a>';
        echo $error;
    }
}

//funkce pro vypis uzivatelu prihlasenych k odberu
function news_users_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;

    $sql = "SELECT * FROM news_users WHERE valid = :valid ORDER BY datum_od DESC LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        if ($dev["registered"]==1): $registered = "ANO"; else: $registered = "NE"; endif;
        echo "<tr>";
        echo "\n";
        echo '<td>'.$dev["id"].'</td>';
        echo "\n";
        echo '<td>'.stripslashes($dev["name"]).'</td>';
        echo "\n";
        echo '<td>'.$dev["email"].'</td>';
        echo "\n";
        echo '<td>'.format_date_www($dev["datum_od"]).'</td>';
        echo "\n";
        echo '<td>'.format_date_www($dev["datum_do"]).'</td>';
        echo "\n";
        echo '<td>'.$registered.'</td>';
        echo "\n";
        echo '<td class="text-center">
            <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=01&amp;sec_page=05&amp;end='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
            <i class="fas fa-edit"></i></td>';
        echo "\n";
        echo '<td class="text-center">
            <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=01&amp;sec_page=05&amp;renew='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
            <i class="fas fa-edit"></i></td>';
        echo "\n";
        echo '<td class="text-center">
            <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=01&amp;sec_page=05&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
            <i class="fas fa-trash"></i></td>';
        echo '</tr>';
        echo "\n";
    }
}

//funkce pro zkopirovani CZ do EN
function news_copytoen ($pdo, $id)
{
    $sql = "SELECT * FROM news WHERE id = :id";
    $res = $pdo->prepare($sql);
    $res->execute(['id'=>$id]);
    $dev = $res->fetchAll();

	$en_nazev_cz = addslashes($dev["nazev_cz"]);
	$en_perex_cz = addslashes($dev["perex_cz"]);
	$en_text_cz = addslashes($dev["text_cz"]);

    $sql1 = "UPDATE news SET nazev_en = :en_nazev_cz, perex_en = :en_perex_cz, text_en = :en_text_cz WHERE id = :id";
    $res1 = $pdo->prepare($sql1);
    try {
        $res1->execute(['en_nazev_cz'=>$en_nazev_cz, 'en_perex_cz'=>$en_perex_cz, 'en_text_cz'=>$en_text_cz, 'id'=>$id]);
        echo '<span class="warning">Novinka byla úspěšně zkopírována z CZ do EN</span><br />';
        unset ($_POST['add']);
    }
    catch (PDOException $e){
        $error = 'Data not copied: '. $e->getMessage();
        echo '<span class="warning">Novinka nebyla zkopírována z CZ do EN</span><br />';
        echo $error;
     }
}

function news_typ_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM news_typ WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}
function news_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM news WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}

function news_users_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM news_users WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}
