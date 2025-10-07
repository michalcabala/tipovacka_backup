<?php
function stattexty_add ($pdo, $cislo, $nazev_cz, $text_cz, $galerie_id, $col)
{
    $nazev_cz = addslashes($nazev_cz);
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO stat_texty (cislo, nazev_cz, text_cz, galerie_id, col, user_i, user_u) VALUES 
		(:cislo, :nazev_cz, :text_cz, :galerie_id, :col, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['cislo'=>$cislo, 'nazev_cz'=>$nazev_cz, 'text_cz'=>$text_cz, 'galerie_id'=>$galerie_id, 'col'=>$col, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Statický text nebyl vložen</span></a>';
        echo $error;
    }
}

function stattexty_vypis ($pdo, $limit, $valid)
{

    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT * FROM stat_texty WHERE valid = :valid ORDER BY cislo LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        if($dev["galerie_id"] == 0): 	$galerie_id = 'NE'; else: $galerie_id = $dev["galerie_id"]; endif;
        if(en_on($pdo)==0):
            $en_edit = "";
        else:
            $en_edit='<a class="btn btn-primary btn-circle btn-sm" href="index.php?section=01&amp;page=02&amp;sec_page=02&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;lang=en&amp;show=2">
                <i class="fas fa-edit"></i></a>';
        endif;
	    echo '<tr>
            <td>'.$dev["id"].'</td>
    		<td>'.stripslashes($dev["nazev_cz"]).'</td>
            <td>'.$dev["cislo"].'</td>
            <td>'.$dev["col"].'</td>
            <td>'.$galerie_id.'</td>
            <td class="text-center"> <!-- editace EN -->'.$en_edit.'</td>
            <td class="text-center">
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=02&amp;sec_page=02&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></td>
            <td class="text-center">
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=02&amp;sec_page=02&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></td>
        </tr>';
    }
}

function stattexty_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE stat_texty SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Statický text byl smazán</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Statický text nebyl smazán</span></a>';
        echo $error;
    }
}

function stattexty_edit ($pdo, $id, $cislo, $nazev, $text, $galerie_id, $col, $lang, $valid)
{
    $nazev = addslashes($nazev);
    $text = addslashes($text);
    $qn_user = $_SESSION["qn_user"];

    if($lang == "cz"):
	    $sql = "UPDATE stat_texty SET cislo = :cislo, nazev_cz = :nazev, text_cz = :text, galerie_id = :galerie_id, col = :col, valid = :valid, user_u = :qn_user_u WHERE id = :id";
    else:
        $sql = "UPDATE stat_texty SET cislo = :cislo, nazev_en = :nazev, text_en = :text, galerie_id = :galerie_id, col = :col, valid = :valid, user_u = :qn_user_u WHERE id = :id";
    endif;
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['cislo'=>$cislo, 'nazev'=>$nazev, 'text'=>$text, 'galerie_id'=>$galerie_id, 'col'=>$col, 'valid'=>$valid, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not edited: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Statický text nebyl změněn</span></a>';
        echo $error;
    }
}

function statvyrazy_add ($pdo, $cislo, $cz, $en, $menu)
{
    $cz = addslashes($cz);
    $en = addslashes($en);
    $qn_user = $_SESSION["qn_user"];

    $sql = 'INSERT INTO stat_vyrazy (cislo, cz, en, menu, user_i, user_u) VALUES (:cislo, :cz, :en, :menu, :qn_user_i, :qn_user_u)';
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['cislo'=>$cislo, 'cz'=>$cz, 'en'=>$en, 'menu'=>$menu, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Statický výraz nebyl vložen</span></a>';
        echo $error;
    }
}

function statvyrazy_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT sv.id, sv.cislo, m.url_cz as menu, sv.cz, sv.en FROM stat_vyrazy sv LEFT OUTER JOIN menu m ON sv.menu = m.id WHERE sv.valid = :valid ORDER BY sv.cislo LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        echo '<tr>
            <td>'.$dev["id"].'</td>
            <td>'.$dev["cislo"].'</td>
            <td>'.$dev["menu"].'</td>
    		<td>'.stripslashes($dev["cz"]).'</td>
            <td>'.stripslashes($dev["en"]).'</td>
            <td class="text-center">
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=02&amp;sec_page=03&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></td>
            <td class="text-center">
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=02&amp;sec_page=03&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></td>
        </tr>';

    }
}

function statvyrazy_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE stat_vyrazy SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Statický výraz byl smazán</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Statický výraz nebyl smazán</span></a>';
        echo $error;
    }
}

function statvyrazy_edit ($pdo, $id, $cislo, $cz, $en, $menu, $valid)
{
    $cz = addslashes($cz);
    $en = addslashes($en);
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE stat_vyrazy SET cislo = :cislo, cz = :cz, en = :en, menu = :menu, valid = :valid, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['cislo'=>$cislo, 'cz'=>$cz, 'en'=>$en, 'menu'=>$menu, 'valid'=>$valid, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not edited: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Statický výraz nebyl změněn</span></a>';
        echo $error;
    }
}

function stattexty_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM stat_texty WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}

function statvyrazy_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM stat_vyrazy WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}

function statvyrazy_cislomax ($pdo)
{
    $sql = "SELECT max(cislo) FROM stat_vyrazy";
    $res = $pdo->prepare($sql);
    $res->execute();
    return $res->fetchColumn();
}

function statvyrazy_menu_option_form ($pdo, $select)
{
    $sql = "SELECT id, url_cz FROM menu WHERE valid = 1 ORDER BY menu";
    $res = $pdo->prepare($sql);
    $res->execute();
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        $id = $dev['id'];
        $url_cz = stripslashes($dev['url_cz']);
        if ($select == $id):
            echo '<option value='.$id.' selected="selected">'.$id.'&nbsp;-&nbsp;'.$url_cz.'</option>';
        else:
            echo '<option value='.$id.'>'.$id.'&nbsp;-&nbsp;'.$url_cz.'</option>';
        endif;
        echo "\n";
    }
}
