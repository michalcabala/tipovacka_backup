<?php
function blog_kat_add ($pdo, $nazev_cz, $nazev_en, $poradi, $popis_cz, $popis_en, $page_cz, $page_en, $color, $visible)
{
    $nazev_cz = addslashes($nazev_cz);
    $nazev_en = addslashes($nazev_en);
    $popis_cz = addslashes($popis_cz);
    $popis_en = addslashes($popis_en);
    $color = addslashes($color);
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO blog_kat (poradi, nazev_cz, nazev_en, popis_cz, popis_en, page_cz, page_en, color, visible, user_i, user_u) VALUES 
		(:poradi, :nazev_cz, :nazev_en, :popis_cz, :popis_en, :page_cz, :page_en, :color, :visible, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['poradi'=>$poradi, 'nazev_cz'=>$nazev_cz, 'nazev_en'=>$nazev_en, 'popis_cz'=>$popis_cz, 'popis_en'=>$popis_en, 'page_cz'=>$page_cz, 'page_en'=>$page_en,
            'color'=>$color, 'visible'=>$visible, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Kategorie blogu nebyla vložena</span></a>';
        echo $error;
    }
}

function blog_kat_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT * FROM blog_kat WHERE valid = :valid ORDER BY poradi LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        if ($dev['visible']==0): $visible = "NE"; else: $visible = "ANO"; endif;
	echo '<tr>
        	<td>'.$dev["id"].'</td>
            <td>'.stripslashes($dev["nazev_cz"]).'</td>
            <td>'.stripslashes($dev["page_cz"]).'</td>
            <td>'.$dev["poradi"].'</td>
            <td>'.$visible.'</td>
            <td>'.$dev["color"].'</td>
            <td class="text-center">
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=04&amp;sec_page=03&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></a></td>
            <td class="text-center">
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=04&amp;sec_page=03&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function blog_kat_edit ($pdo, $id, $nazev_cz, $nazev_en, $poradi, $popis_cz, $popis_en, $page_cz, $page_en, $color, $visible, $valid)
{
    $nazev_cz = addslashes($nazev_cz);
    $nazev_en = addslashes($nazev_en);
    $popis_cz = addslashes($popis_cz);
    $popis_en = addslashes($popis_en);
    $color = addslashes($color);
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE blog_kat SET poradi = :poradi, nazev_cz = :nazev_cz, nazev_en = :nazev_en, popis_cz = :popis_cz, popis_en = :popis_en, page_cz = :page_cz, page_en = :page_en, 
                    color = :color, visible = :visible,	valid = :valid, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['poradi'=>$poradi, 'nazev_cz'=>$nazev_cz, 'nazev_en'=>$nazev_en, 'popis_cz'=>$popis_cz, 'popis_en'=>$popis_en, 'page_cz'=>$page_cz, 'page_en'=>$page_en,
            'color'=>$color, 'visible'=>$visible, 'valid'=>$valid, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not edited: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Kategorie blogu nebyla uložena</span></a>';
        echo $error;
    }
}

function blog_kat_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE blog_kat SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Kategorie blogu byla smazána</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Kategorie blogu nebyla smazána</span></a>';
        echo $error;
    }
}

function blog_kat_option_form ($pdo, $select)
{
    $sql = "SELECT id, nazev_cz FROM blog_kat WHERE valid = 1 ORDER BY poradi";
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

function blog_add ($pdo, $datum, $blog_kat, $nazev_cz, $perex_cz, $text_cz, $galerie_id, $visible, $fav)
{
    $nazev_cz = addslashes($nazev_cz);
    $perex_cz = addslashes($perex_cz);
    $text_cz = addslashes($text_cz);
    $url_cz = text_str(addslashes($nazev_cz)).'-'.$datum;
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO blog (datum, url_cz, blog_kat, nazev_cz, perex_cz, text_cz, galerie_id, visible, fav, user_i, user_u) VALUES (:datum, :url_cz, :blog_kat, :nazev_cz, :perex_cz, :text_cz, :galerie_id, :visible, :fav, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['datum'=>$datum, 'url_cz'=>$url_cz, 'blog_kat'=>$blog_kat, 'nazev_cz'=>$nazev_cz, 'perex_cz'=>$perex_cz, 'text_cz'=>$text_cz,
            'galerie_id'=>$galerie_id, 'visible'=>$visible, 'fav'=>$fav, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Blog nebyl vložen</span></a>';
        echo $error;
    }
}

function blog_edit ($pdo, $id, $datum, $blog_kat, $nazev, $perex, $text, $galerie_id, $visible, $fav, $lang, $url, $valid)
{
    $nazev = addslashes($nazev);
    $perex = addslashes($perex);
    $text = addslashes($text);
    $qn_user = $_SESSION["qn_user"];

    if ($lang == "cz"):
	    $sql = "UPDATE blog SET url_cz = :url, datum = :datum, blog_kat = :blog_kat, nazev_cz = :nazev, perex_cz = :perex, text_cz = :text, galerie_id = :galerie_id, 
                visible = :visible, fav = :fav, valid = :valid, user_u = :qn_user_u WHERE id = :id";
    else:
        $sql = "UPDATE blog SET url_en = :url, datum = :datum, blog_kat = :blog_kat, nazev_en = :nazev, perex_en = :perex,text_en = :text, galerie_id = :galerie_id, 
                visible = :visible, fav = :fav, valid = :valid, user_u = :qn_user_u WHERE id = :id";
    endif;
    $res = $pdo->prepare($sql);

    try {
        $res->execute(['url'=>$url, 'datum'=>$datum, 'blog_kat'=>$blog_kat, 'nazev'=>$nazev, 'perex'=>$perex, 'text'=>$text, 'galerie_id'=>$galerie_id, 'visible'=>$visible, 'fav'=>$fav,
           'valid'=>$valid, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not updated: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Blog nebyl uložen</span></a>';
        echo $error;
    }
}

function blog_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT b.id, b.url_cz, b.nazev_cz, b.datum, b.blog_kat, b.galerie_id, b.visible, b.fav, bk.nazev_cz as kat FROM blog b, blog_kat bk WHERE bk.id = b.blog_kat AND b.valid = :valid ORDER BY b.datum DESC, b.id DESC LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        if($dev["galerie_id"] == 0): 	$galerie_id = 'NE'; else: $galerie_id = $dev["galerie_id"]; endif;
        if($dev["fav"] == 0): 	        $fav = 'NE';        else: $fav = "ANO"; endif;
        if($dev["visible"] == 0):       $visible = 'NE'; elseif($dev["visible"] == 1): $visible = "CZ/EN"; elseif($dev["visible"] == 2): $visible = "CZ"; elseif($dev["visible"] == 3): $visible = "EN"; else: $visible=''; endif;
        if(en_on($pdo)==0):
            $en_edit = "";
        else:
            $en_edit='<a class="btn btn-primary btn-circle btn-sm" href="index.php?section=01&amp;page=04&amp;sec_page=02&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;lang=en&amp;show=2">
                <i class="fas fa-edit"></i></a>';
        endif;
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/cz/index/blog/".$dev["url_cz"];

        echo '
        <tr>
            <td>'.$dev["id"].'</td>
            <td>'.stripslashes($dev["kat"]).'</td>
            <td>'.stripslashes($dev["nazev_cz"]).'</td>
            <td>'.format_date_www($dev["datum"]).'</td>
            <td>'.$fav.'</td>
            <td>'.$galerie_id.'</td>
            <td>'.$visible.'</td>
            <td class="text-center"> <!-- nahled -->
                <a class="btn btn-primary btn-circle btn-sm" href="'.$url.'" target="_blank">
                <i class="fas fa-external-link-alt"></i></a></td>
            <td class="text-center"> <!-- editace EN -->'.$en_edit.'</td>
            <td class="text-center"> <!-- upravit -->
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=04&amp;sec_page=02&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></a></td>
            <td class="text-center"> <!-- smazat -->
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=04&amp;sec_page=02&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function blog_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE blog SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Blog byl smazán</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Blog nebyl smazán</span></a>';
        echo $error;
    }
}

function blog_kat_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM blog_kat WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}
function blog_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM blog WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}

