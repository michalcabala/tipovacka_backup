<?php
function tipovacka_add ($pdo, $news_id, $nazev_cz, $nazev_en, $popis_cz, $popis_en, $datum_od, $datum_do, $datum_do_poradi, $tip_zapasy, $tip_poradi, $tip_otazky, $tip_zapasy_remizy, $aktivni): void
{
    $nazev_cz = addslashes($nazev_cz); $nazev_en = addslashes($nazev_en);
    $popis_cz = addslashes($popis_cz); $popis_en = addslashes($popis_en);
    $datum_od = format_date_db($datum_od); $datum_do = format_date_db($datum_do); $datum_do_poradi = format_date_db($datum_do_poradi);

    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO zdef_tipovacka (news_id, nazev_cz, nazev_en, popis_cz, popis_en, datum_od, datum_do, datum_do_poradi, tip_zapasy, tip_poradi, tip_otazky, tip_zapasy_remizy, aktivni, user_i, user_u) 
            VALUES (:news_id, :nazev_cz, :nazev_en, :popis_cz, :popis_en, :datum_od, :datum_do, :datum_do_poradi, :tip_zapasy, :tip_poradi, :tip_otazky, :tip_zapasy_remizy, :aktivni, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['news_id'=>$news_id, 'nazev_cz'=>$nazev_cz, 'nazev_en'=>$nazev_en, 'popis_cz'=>$popis_cz, 'popis_en'=>$popis_en, 'datum_od'=>$datum_od, 'datum_do'=>$datum_do,
            'datum_do_poradi'=>$datum_do_poradi, 'tip_zapasy'=>$tip_zapasy, 'tip_poradi'=>$tip_poradi, 'tip_otazky'=>$tip_otazky, 'tip_zapasy_remizy'=>$tip_zapasy_remizy, 'aktivni'=>$aktivni, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
            $lastid = $pdo->lastInsertId();
            $url1 = 'tip'.$lastid;
            $sql1 = 'UPDATE zdef_tipovacka SET url = :url WHERE id = :id';
            $res1 = $pdo->prepare($sql1);
            $res1->execute(['url'=>$url1, 'id'=>$lastid]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Tipovačka nebyla vložena</span></a>';
        echo $error;
    }
}

/**
 * @throws Exception
 */
function tipovacka_edit ($pdo, $edit, $url, $news_id, $nazev_cz, $nazev_en, $popis_cz, $popis_en, $datum_od, $datum_do, $datum_do_poradi, $tip_zapasy, $tip_poradi, $tip_otazky, $tip_zapasy_remizy, $aktivni, $valid, $soubor_str): void
{
    $nazev_cz = addslashes($nazev_cz); $nazev_en = addslashes($nazev_en);
    $popis_cz = addslashes($popis_cz); $popis_en = addslashes($popis_en);
    $datum_od = format_date_db($datum_od); $datum_do = format_date_db($datum_do); $datum_do_poradi = format_date_db($datum_do_poradi);
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE zdef_tipovacka 
            SET url = :url, news_id = :news_id, nazev_cz = :nazev_cz, nazev_en = :nazev_en, popis_cz = :popis_cz, popis_en = :popis_en, datum_od = :datum_od, datum_do = :datum_do, 
            datum_do_poradi = :datum_do_poradi, tip_zapasy = :tip_zapasy, tip_poradi = :tip_poradi, tip_otazky = :tip_otazky, tip_zapasy_remizy = :tip_zapasy_remizy, aktivni = :aktivni, valid = :valid, user_i = :qn_user_i, user_u = :qn_user_u 
            WHERE id = :edit";
    $res = $pdo->prepare($sql);
    if ($soubor_str == ''):
        $show=1;
    else:
        $dir_original = '../files/images/tipovacka/';
        $dir_small = '../files/images/tipovacka/small/';
        $file_orig = $dir_original.$soubor_str;
        $file_small = $dir_small.$soubor_str;

        //* vytvoreni originalu
        list($width, $height) = create_image($file_orig, sp_hodnota($pdo, 'pic_tipovacka_orig_width'), sp_hodnota($pdo, 'pic_tipovacka_orig_height'));
        if ($width && $height):
            image_resize($pdo, $file_orig, $width, $height);
        endif;

        //* vytvoreni thumbnailu
        list($width, $height) = create_image($file_small, sp_hodnota($pdo, 'pic_tipovacka_thumb_width'), sp_hodnota($pdo, 'pic_tipovacka_thumb_height'));
        if ($width && $height):
            image_resize($pdo, $file_small, $width, $height);
        endif;

        $sql1 = "UPDATE zdef_tipovacka SET image = :soubor_str WHERE id = :tipovacka_id";
        $res1 = $pdo->prepare($sql1);
        try {
            $res1->execute(['soubor_str'=>$soubor_str, 'tipovacka_id'=>$edit]);
            echo '<span class="warning">Obrázek tipovačky byl úspěšně uložen</span><br />';
        }
        catch (PDOException $e){
            $error = 'Data not updated: '. $e->getMessage();
            echo '<span class="warning">Obrázek tipovačky nebyl uložen.</span><br />';
            echo $error;
        }
        $show = 2;
    endif;

    try {
        $res->execute(['url'=>$url, 'news_id'=>$news_id, 'nazev_cz'=>$nazev_cz, 'nazev_en'=>$nazev_en, 'popis_cz'=>$popis_cz, 'popis_en'=>$popis_en, 'datum_od'=>$datum_od, 'datum_do'=>$datum_do,
            'datum_do_poradi'=>$datum_do_poradi, 'tip_zapasy'=>$tip_zapasy, 'tip_poradi'=>$tip_poradi, 'tip_otazky'=>$tip_otazky, 'tip_zapasy_remizy'=>$tip_zapasy_remizy, 'aktivni'=>$aktivni,
            'valid'=>$valid, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user, 'edit'=>$edit]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]$show";
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Tipovačka nebyla uložena</span></a>';
        echo $error;
    }
}

function tipovacka_vypis ($pdo, $limit, $valid, $tipdef): void
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT * FROM zdef_tipovacka WHERE valid = :valid ORDER BY datum_do DESC, id DESC LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        if($dev["tip_zapasy"] == 0): 	$tip_zapasy = 'NE';   else: $tip_zapasy = "ANO"; endif;
        if($dev["tip_poradi"] == 0): 	$tip_poradi = 'NE';   else: $tip_poradi = "ANO"; endif;
        if($dev["tip_otazky"] == 0): 	$tip_otazky = 'NE';   else: $tip_otazky = "ANO"; endif;
        if($dev["aktivni"] == 0): 	    $aktivni = 'NE';      else: $aktivni = "ANO"; endif;
        if($dev["tip_zapasy_remizy"] == 0): 	$tip_zapasy_remizy = 'NE';   else: $tip_zapasy_remizy = "ANO"; endif;
        if($dev["id"] == $tipdef):
            $trclass = 'text-danger';
            $tipdefset = '';
        else:
            $trclass = '';
            $tipdefset = '<a class="btn btn-warning btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=02&amp;tipdef='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-eye"></i></a>';
        endif;
        if($dev["image"] == ""):
            $image = 'NE';
            $image_del = '';
        else:
            $image = 'ANO';
            $image_del = '<a class="btn btn-danger btn-circle btn-sm" href="?section=01&amp;page=51&amp;sec_page=02&amp;image='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-icons"></i></a>';
        endif;


        echo '
        <tr class="'.$trclass.'">
            <td>'.$dev["id"].'</td>
            <td>'.$aktivni.'</td>
            <td>'.stripslashes($dev["nazev_cz"]).'</td>
            <td>'.format_date_www($dev["datum_od"]).'</td>
            <td>'.format_date_www($dev["datum_do"]).'</td>
            <td>'.$tip_zapasy.'</td>
            <td>'.$tip_zapasy_remizy.'</td>
            <td>'.$tip_poradi.'</td>
            <td>'.$tip_otazky.'</td>
            <td>'.format_date_www($dev["datum_do_poradi"]).'</td>
            <td>'.$dev["news_id"].'</td>
            <td>'.$image.'</td>
            <td class="text-center"> <!-- nastavit default -->
                '.$tipdefset.'</td>
            <td class="text-center"> <!-- upravit -->
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=02&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></a></td>
            <td class="text-center"> <!-- smazat ikonu -->
                '.$image_del.'
            </td>    
            <td class="text-center"> <!-- smazat -->
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=02&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function tipovacka_delete ($pdo, $id): void
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE zdef_tipovacka SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Tipovačka byla smazána</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Tipovačka nebyla smazána</span></a>';
        echo $error;
    }
}

function tipovacka_image_add ($tipovacka_id) :string
{
    $dir_original = '../files/images/tipovacka/';
    $dir_small = '../files/images/tipovacka/small/';

    if ($_FILES['userfile']['error'] == UPLOAD_ERR_NO_FILE):
        $soubor_str = '';
    else:
        $soubor_str = $tipovacka_id.'-'.text_str($_FILES['userfile']['name']);
        move_uploaded_file($_FILES['userfile']['tmp_name'], $dir_original.$soubor_str);
        copy ($dir_original.$soubor_str, $dir_small.$soubor_str);
    endif;
    return $soubor_str;
}

function tipovacka_image_delete ($pdo, $tipovacka_id): void
{
    $sql = "SELECT image FROM zdef_tipovacka WHERE id = :tipovacka_id";
    $res = $pdo->prepare($sql);
    $res->execute(['tipovacka_id'=>$tipovacka_id]);
    $dev = $res->fetch();

    $soubor = stripslashes($dev['image']);
    $delete_ico = unlink('../files/images/tipovacka/'.$soubor);
    $delete_ico_small = unlink('../files/images/tipovacka/small/'.$soubor);

    if ($delete_ico):
        echo '<span class="warning">Originál obrázek smazán</span><br />';
    endif;
    if ($delete_ico_small):
        echo '<span class="warning">Thumbnail obrázek smazán</span><br />';
    endif;

    $sql1 = 'UPDATE zdef_tipovacka SET image = :image WHERE id = :tipovacka_id';
    $res1 = $pdo->prepare($sql1);
    $image = "";
    $res1->execute(['image'=>$image, 'tipovacka_id'=>$tipovacka_id]);
}

function tipovacka_option_form ($pdo, $select): void
{
    $sql = "SELECT id, nazev_cz FROM zdef_tipovacka WHERE valid = 1 ORDER BY id DESC";
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

function tipovacka_teams_add ($pdo, $tipovacka_id, $nazev_cz, $nazev_en, $poradi, $poradi_final): void
{
    $nazev_cz = addslashes($nazev_cz);
    $nazev_en = addslashes($nazev_en);
    $qn_user = $_SESSION["qn_user"];
    $sql = "INSERT INTO zdef_tipovacka_teams (tipovacka_id, nazev_cz, nazev_en, poradi, poradi_final, user_i, user_u) 
            VALUES (:tipovacka_id, :nazev_cz, :nazev_en, :poradi, :poradi_final, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['tipovacka_id'=>$tipovacka_id, 'nazev_cz'=>$nazev_cz, 'nazev_en'=>$nazev_en, 'poradi'=>$poradi, 'poradi_final'=>$poradi_final, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Tým nebyl vložen</span></a>';
        echo $error;
    }
}

/**
 * @throws Exception
 */
function tipovacka_teams_edit ($pdo, $id, $tipovacka_id, $nazev_cz, $nazev_en, $poradi, $poradi_final, $valid, $soubor_str): void
{
    $nazev_cz = addslashes($nazev_cz);
    $nazev_en = addslashes($nazev_en);
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE zdef_tipovacka_teams SET tipovacka_id = :tipovacka_id, nazev_cz = :nazev_cz, nazev_en = :nazev_en, poradi = :poradi, poradi_final = :poradi_final, 
                 valid = :valid, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);

    if ($soubor_str == ''):
        $show=1;
    else:
        $dir_original = '../files/images/teams/';
        $dir_small = '../files/images/teams/small/';
        $file_orig = $dir_original.$soubor_str;
        $file_small = $dir_small.$soubor_str;

        //* vytvoreni originalu
        list($width, $height) = create_image($file_orig, sp_hodnota($pdo, 'pic_teams_orig_width'), sp_hodnota($pdo, 'pic_teams_orig_height'));
        if ($width && $height):
            image_resize($pdo, $file_orig, $width, $height);
        endif;

        //* vytvoreni thumbnailu
        list($width, $height) = create_image($file_small, sp_hodnota($pdo, 'pic_teams_thumb_width'), sp_hodnota($pdo, 'pic_teams_thumb_height'));
        if ($width && $height):
            image_resize($pdo, $file_small, $width, $height);
        endif;

        $sql1 = "UPDATE zdef_tipovacka_teams SET image = :soubor_str WHERE id = :teams_id";
        $res1 = $pdo->prepare($sql1);
        try {
            $res1->execute(['soubor_str'=>$soubor_str, 'teams_id'=>$id]);
            echo '<span class="warning">Obrázek týmu byl úspěšně uložen</span><br />';
        }
        catch (PDOException $e){
            $error = 'Data not updated: '. $e->getMessage();
            echo '<span class="warning">Obrázek týmu nebyl uložen.</span><br />';
            echo $error;
        }
        $show = 2;
    endif;

    try {
        $res->execute(['tipovacka_id'=>$tipovacka_id, 'nazev_cz'=>$nazev_cz, 'nazev_en'=>$nazev_en, 'poradi'=>$poradi, 'poradi_final'=>$poradi_final,
            'valid'=>$valid, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]$show";
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not updated: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Tým nebyl uložen</span></a>';
        echo $error;
    }
}

function tipovacka_teams_vypis ($pdo, $limit, $valid, $tipovacka_id): void
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;

    if ($tipovacka_id == 0):
        $sql = "SELECT tt.id, tt.poradi, tt.nazev_cz, tt.poradi_final, tt.image, t.nazev_cz as tipovacka 
            FROM zdef_tipovacka_teams tt LEFT OUTER JOIN zdef_tipovacka t on tt.tipovacka_id = t.id WHERE tt.valid = :valid 
            ORDER BY tt.poradi_final, tt.poradi LIMIT :sqllimit";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    else:
        $sql = "SELECT tt.id, tt.poradi, tt.nazev_cz, tt.poradi_final, tt.image, t.nazev_cz as tipovacka 
            FROM zdef_tipovacka_teams tt LEFT OUTER JOIN zdef_tipovacka t on tt.tipovacka_id = t.id WHERE tt.valid = :valid AND tt.tipovacka_id = :tipovacka_id 
            ORDER BY tt.poradi_final, tt.poradi LIMIT :sqllimit";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit, 'tipovacka_id'=>$tipovacka_id]);
    endif;

    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        if($dev["image"] == ""):
            $image = 'NE';
            $image_del = '';
        else:
            $image = 'ANO';
            $image_del = '<a class="btn btn-danger btn-circle btn-sm" href="?section=01&amp;page=51&amp;sec_page=03&amp;image='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-icons"></i></a>';
        endif;

        echo '
        <tr>
            <td>'.$dev["id"].'</td>
            <td>'.$dev["tipovacka"].'</td>
            <td>'.$dev["poradi"].'</td>
            <td>'.stripslashes($dev["nazev_cz"]).'</td>
            <td>'.$dev["poradi_final"].'</td>
            <td>'.$image.'</td>
            <td class="text-center"> <!-- upravit -->
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=03&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></a></td>
            <td class="text-center"> <!-- smazat ikonu -->
                '.$image_del.'
            </td>
            <td class="text-center"> <!-- smazat -->
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=03&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function tipovacka_teams_delete ($pdo, $id): void
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE zdef_tipovacka_teams SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Tým byl smazán</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Tým nebyl smazán</span></a>';
        echo $error;
    }
}

function tipovacka_teams_image_add ($teams_id) :string
{
    $dir_original = '../files/images/teams/';
    $dir_small = '../files/images/teams/small/';

    if ($_FILES['userfile']['error'] == UPLOAD_ERR_NO_FILE):
        $soubor_str = '';
    else:
        $soubor_str = $teams_id.'-'.text_str($_FILES['userfile']['name']);
        move_uploaded_file($_FILES['userfile']['tmp_name'], $dir_original.$soubor_str);
        copy ($dir_original.$soubor_str, $dir_small.$soubor_str);
    endif;
    return $soubor_str;
}

function tipovacka_teams_image_delete ($pdo, $teams_id): void
{
    $sql = "SELECT image FROM zdef_tipovacka_teams WHERE id = :teams_id";
    $res = $pdo->prepare($sql);
    $res->execute(['teams_id'=>$teams_id]);
    $dev = $res->fetch();

    $soubor = stripslashes($dev['image']);
    $delete_ico = unlink('../files/images/teams/'.$soubor);
    $delete_ico_small = unlink('../files/images/teams/small/'.$soubor);

    if ($delete_ico):
        echo '<span class="warning">Originál obrázek smazán</span><br />';
    endif;
    if ($delete_ico_small):
        echo '<span class="warning">Thumbnail obrázek smazán</span><br />';
    endif;

    $sql1 = 'UPDATE zdef_tipovacka_teams SET image = :image WHERE id = :teams_id';
    $res1 = $pdo->prepare($sql1);
    $image = "";
    $res1->execute(['image'=>$image, 'teams_id'=>$teams_id]);
}

function tipovacka_teams_vypocet ($pdo, $tipovacka_id): void
{
    $sql1 = 'SELECT * FROM zdef_tipovacka_tips_poradi WHERE valid = 1 AND tipovacka_id = :tipovacka_id';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['tipovacka_id'=>$tipovacka_id]);

    $stmt = $res1->fetchAll();
    if ($tipovacka_id == 0):
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Nelze přepočítat všechny tipovačky, vyberte defaultní.</span></a>';
    else:
        foreach ($stmt as $dev1)
        {
            $sql2 = 'SELECT poradi_final FROM zdef_tipovacka_teams WHERE valid = 1 AND tipovacka_id = :tipovacka_id AND id = :team_id';
            $res2 = $pdo->prepare($sql2);
            $res2->execute(['tipovacka_id'=>$tipovacka_id, 'team_id'=>$dev1['team_id']]);
            $poradi_final = $res2->fetchColumn();

            if ($poradi_final == 0 OR $dev1['poradi'] == 0):
                $body = 0;
            elseif ($dev1['poradi']==$poradi_final):
                $body = 10;
            elseif (($poradi_final-$dev1['poradi'])==1 OR ($poradi_final-$dev1['poradi'])==-1):
                $body = 5;
            elseif (($poradi_final-$dev1['poradi'])==2 OR ($poradi_final-$dev1['poradi'])==-2):
                $body = 3;
            else:
                $body = 0;
            endif;

            $sql3 = 'UPDATE zdef_tipovacka_tips_poradi SET body = :body WHERE id = :id AND valid = 1';
            $res3 = $pdo->prepare($sql3);
            $res3->execute(['body'=>$body, 'id'=>$dev1['id']]);
        }
        echo '<a href="#" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Pořadí týmů bylo přepočteno.</span></a>';
    endif;
}

function tipovacka_users_vypis ($pdo, $limit, $valid): void
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT * FROM zdef_tipovacka_users WHERE valid = :valid ORDER BY ts_i, id DESC LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        if($dev["active"] == 0): 	    $active = 'NE';   else: $active = "ANO"; endif;
        if($dev["blocked"] == 0): 	    $blocked = 'NE';   else: $blocked = "ANO"; endif;
        if($dev["info_send"] == 0): 	$info_send = 'NE';   else: $info_send = "ANO"; endif;

        $sql1 = 'SELECT datum FROM zdef_tipovacka_users_log WHERE login = :login ORDER BY id DESC LIMIT 1';
        $res1 = $pdo->prepare($sql1);
        $res1->execute(['login'=>$dev['login']]);
        $dev2 = $res1->fetchColumn() ?? '';

        echo '
        <tr>
            <td>'.$dev["id"].'</td>
            <td>'.stripslashes($dev["login"] ?? '').'</td>
            <td>'.stripslashes($dev["name"] ?? '').'</td>
            <td>'.stripslashes($dev["email"] ?? '').'</td>
            <td>'.$active.'</td>
            <td>'.$blocked.'</td>
            <td>'.$dev["xenforo"].'</td>
            <td>'.$info_send.'</td>
            <td>'.format_datetime_www($dev["exp_date"]).'</td>
            <td>'.$dev2.'</td>
            <td>'.format_datetime_www($dev["ts_i"]).'</td>
            <td class="text-center"> <!-- upravit -->
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=04&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></a></td>
            <td class="text-center"> <!-- smazat -->
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=04&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function tipovacka_users_add ($pdo, $login, $name, $email, $password, $active, $blocked, $phpbb, $info_send): void
{
    $login = addslashes($login); $name = addslashes($name);
    $email = addslashes($email); $pass_sha1 = sha1($password);
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO zdef_tipovacka_users (login, name, email, password, active, blocked, phpbb, info_send, user_i, user_u) 
            VALUES (:login, :name, :email, :password, :active, :blocked, :phpbb, :info_send, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['login'=>$login, 'name'=>$name, 'email'=>$email, 'password'=>$pass_sha1, 'active'=>$active, 'blocked'=>$blocked, 'phpbb'=>$phpbb, 'info_send'=>$info_send,
            'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Uživatel nebyl vložen</span></a>';
        echo $error;
        $_POST['add'] = 0;
    }
}

function tipovacka_users_edit ($pdo, $edit, $login, $name, $email, $password, $active, $blocked, $phpbb, $info_send, $valid): void
{
    $login = addslashes($login); $name = addslashes($name);
    $email = addslashes($email);
    $qn_user = $_SESSION["qn_user"];

    try {
        if ($password == ''):
            $sql = "UPDATE zdef_tipovacka_users SET login = :login, name = :name, email = :email, active = :active, blocked = :blocked, phpbb = :phpbb, info_send = :info_send,
                                valid = :valid, user_i = :qn_user_i, user_u = :qn_user_u WHERE id = :edit";
            $res = $pdo->prepare($sql);
            $res->execute(['login'=>$login, 'name'=>$name, 'email'=>$email, 'active'=>$active, 'blocked'=>$blocked, 'phpbb'=>$phpbb, 'info_send'=>$info_send, 'valid'=>$valid, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user, 'edit'=>$edit]);
        else:
            $pass_sha1 = sha1($password);
            $sql = "UPDATE zdef_tipovacka_users SET login = :login, name = :name, email = :email, password = :password, active = :active, blocked = :blocked, phpbb = :phpbb, info_send = :info_send,
                                valid = :valid, user_i = :qn_user_i, user_u = :qn_user_u WHERE id = :edit";
            $res = $pdo->prepare($sql);
            $res->execute(['login'=>$login, 'name'=>$name, 'email'=>$email, 'password'=>$pass_sha1, 'active'=>$active, 'blocked'=>$blocked, 'phpbb'=>$phpbb, 'info_send'=>$info_send, 'valid'=>$valid, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user, 'edit'=>$edit]);
        endif;
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Uživatel nebyl uložen</span></a>';
        echo $error;
        $_POST['add'] = 0;
    }
}

function tipovacka_users_delete ($pdo, $id): void
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE zdef_tipovacka_users SET valid = 0, name = login, login = '', email = '', user_u = :qn_user_u WHERE id = :id";
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

function tipovacka_zapasy_vypis ($pdo, $limit, $valid, $tipovacka_id): void
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;

    if ($tipovacka_id == 0):
        $sql = "SELECT tz.id as id, tz.poradi as poradi, tz.skupina as skupina, tz.team1_id as team1_id, tz.team2_id as team2_id, tz.team1_goals as team1_goals, tz.team2_goals as team2_goals,
            tz.datetime as datetime, tz.datetime_end as datetime_end, tz.koeficient as koeficient, tz.tip as tip, t.nazev_cz as tipovacka
            FROM zdef_tipovacka_zapasy tz INNER JOIN zdef_tipovacka t on tz.tipovacka_id = t.id 
            WHERE tz.valid = :valid 
            ORDER BY tz.tip=99 DESC, tz.datetime, tz.poradi LIMIT :sqllimit";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    else:
        $sql = "SELECT tz.id as id, tz.poradi as poradi, tz.skupina as skupina, tz.team1_id as team1_id, tz.team2_id as team2_id, tz.team1_goals as team1_goals, tz.team2_goals as team2_goals,
            tz.datetime as datetime, tz.datetime_end as datetime_end, tz.koeficient as koeficient, tz.tip as tip, t.nazev_cz as tipovacka
            FROM zdef_tipovacka_zapasy tz INNER JOIN zdef_tipovacka t on tz.tipovacka_id = t.id 
            WHERE tz.valid = :valid AND tz.tipovacka_id = :tipovacka_id
            ORDER BY tz.tip=99 DESC, tz.datetime, tz.poradi LIMIT :sqllimit";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit, 'tipovacka_id'=>$tipovacka_id]);
    endif;

    $stmt = $res->fetchAll();
    foreach ($stmt as $dev)
    {
        $zapas = tipovacka_team_name($pdo, $dev["team1_id"]).' - '.tipovacka_team_name($pdo, $dev["team2_id"]);
        $vysledek = $dev["team1_goals"].' : '.$dev["team2_goals"];
        echo '
        <tr>
            <td>'.$dev["id"].'</td>
            <td>'.$dev["tipovacka"].'</td>
            <td>'.$dev["poradi"].'</td>
            <td>'.$dev["skupina"].'</td>
            <td>'.$zapas.'</td>
            <td>'.$vysledek.'</td>
            <td>'.format_datetime_www($dev["datetime"]).'</td>
            <td>'.format_datetime_www($dev["datetime_end"]).'</td>
            <td>'.$dev["koeficient"].'</td>
            <td>'.$dev["tip"].'</td>
            <td class="text-center"> <!-- upravit -->
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=05&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></a></td>
            <td class="text-center"> <!-- smazat -->
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=05&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function tipovacka_zapasy_add ($pdo, $tipovacka_id, $poradi, $skupina, $team1_id, $team2_id, $team1_goals, $team2_goals, $datetime, $datetime_end, $koeficient, $tip): void
{
    $skupina = addslashes($skupina);
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO zdef_tipovacka_zapasy (tipovacka_id, poradi, skupina, team1_id, team2_id, team1_goals, team2_goals, datetime, datetime_end, koeficient, tip, user_i, user_u) 
            VALUES (:tipovacka_id, :poradi, :skupina, :team1_id, :team2_id, :team1_goals, :team2_goals, :datetime, :datetime_end, :koeficient, :tip, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['tipovacka_id'=>$tipovacka_id, 'poradi'=>$poradi, 'skupina'=>$skupina, 'team1_id'=>$team1_id, 'team2_id'=>$team2_id, 'team1_goals'=>$team1_goals, 'team2_goals'=>$team2_goals,
                        'datetime'=>$datetime, 'datetime_end'=>$datetime_end, 'koeficient'=>$koeficient, 'tip'=>$tip, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Zápas nebyl vložen</span></a>';
        echo $error;
        $_POST['add'] = 0;
    }
}

function tipovacka_zapasy_edit ($pdo, $edit, $tipovacka_id, $poradi, $skupina, $team1_id, $team2_id, $team1_goals, $team2_goals, $datetime, $datetime_end, $koeficient, $tip, $valid): void
{
    $skupina = addslashes($skupina);
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE zdef_tipovacka_zapasy SET tipovacka_id = :tipovacka_id, poradi = :poradi, skupina = :skupina, team1_id = :team1_id, team2_id = :team2_id, team1_goals = :team1_goals,
            team2_goals = :team2_goals, datetime = :datetime, datetime_end = :datetime_end, koeficient = :koeficient, tip = :tip, valid = :valid, user_i = :qn_user_i, user_u = :qn_user_u 
            WHERE id = :edit";
    $res = $pdo->prepare($sql);

    try {
        $res->execute(['tipovacka_id'=>$tipovacka_id, 'poradi'=>$poradi, 'skupina'=>$skupina, 'team1_id'=>$team1_id, 'team2_id'=>$team2_id, 'team1_goals'=>$team1_goals,
            'team2_goals'=>$team2_goals, 'datetime'=>$datetime, 'datetime_end'=>$datetime_end, 'koeficient'=>$koeficient, 'tip'=>$tip, 'qn_user_i'=>$qn_user, 'valid'=>$valid, 'qn_user_u'=>$qn_user, 'edit'=>$edit]);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        tipovacka_zapasy_vypocet ($pdo, $edit, $tip, $team1_goals, $team2_goals, $koeficient);
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Zápas nebyl uložen</span></a>';
        echo $error;
        $_POST['add'] = 0;
    }
}

function tipovacka_zapasy_vypocet ($pdo, $edit, $tip, $team1_goals, $team2_goals, $koeficient): void
{
    $sql1 = 'SELECT * FROM zdef_tipovacka_tips_zapasy WHERE valid = 1 AND zapas_id = :zapas_id';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['zapas_id'=>$edit]);

    $stmt = $res1->fetchAll();
    foreach ($stmt as $dev1)
    {
        if ($tip == $dev1['tip'] AND $team1_goals == $dev1['team1_goals'] AND $team2_goals == $dev1['team2_goals']):
            $body = 3 * $koeficient;
        elseif ($tip == $dev1['tip'] AND  ($team1_goals-$team2_goals) == ($dev1['team1_goals']-$dev1['team2_goals'])):
            $body = 2 * $koeficient;
        elseif ($tip == $dev1['tip']):
            $body = 1 * $koeficient;
        else:
            $body = 0;
        endif;

        $sql2 = 'UPDATE zdef_tipovacka_tips_zapasy SET body = :body, koeficient = :koeficient WHERE id = :id AND valid = 1';
        $res2 = $pdo->prepare($sql2);
        $res2->execute(['body'=>$body, 'koeficient'=>$koeficient, 'id'=>$dev1['id']]);
    }
}

function tipovacka_zapasy_delete ($pdo, $id): void
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE zdef_tipovacka_zapasy SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Zápas byl smazán</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Zápas nebyl smazán</span></a>';
        echo $error;
    }
}

function tipovacka_users_rel_vypis ($pdo, $limit, $valid, $tipovacka_id): void
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;

    if ($tipovacka_id == 0):
        $sql = "SELECT ur.id as id, ur.user_id as user_id, ur.tipovacka_id as tipovacka_id, ur.registered as registered, ur.body_zapasy as body_zapasy, ur.body_poradi as body_poradi, 
            ur.body_otazky, ur.body_celkem as body_celkem, ur.ts_i as ts_i, u.login as login, t.nazev_cz as tipovacka
            FROM zdef_tipovacka_users_rel ur 
                INNER JOIN zdef_tipovacka_users u on ur.user_id = u.id 
                INNER JOIN zdef_tipovacka t on ur.tipovacka_id = t.id
            WHERE ur.valid = :valid 
            ORDER BY t.nazev_cz, u.login LIMIT :sqllimit";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    else:
        $sql = "SELECT ur.id as id, ur.user_id as user_id, ur.tipovacka_id as tipovacka_id, ur.registered as registered, ur.body_zapasy as body_zapasy, ur.body_poradi as body_poradi, 
            ur.body_otazky, ur.body_celkem as body_celkem, ur.ts_i as ts_i, u.login as login, t.nazev_cz as tipovacka
            FROM zdef_tipovacka_users_rel ur 
                INNER JOIN zdef_tipovacka_users u on ur.user_id = u.id 
                INNER JOIN zdef_tipovacka t on ur.tipovacka_id = t.id
            WHERE ur.valid = :valid AND ur.tipovacka_id = :tipovacka_id
            ORDER BY ur.body_celkem DESC, u.login LIMIT :sqllimit";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit, 'tipovacka_id'=>$tipovacka_id]);
    endif;

    $stmt = $res->fetchAll();
    foreach ($stmt as $dev)
    {
        if ($dev['registered']==0):
            $registered = 'NE';
            $blocked = '<a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=06&amp;act='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-edit"></i></a>';
        else:
            $registered = 'ANO';
            $blocked = '<a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=06&amp;block='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-edit"></i></a>';
        endif;
        echo '
        <tr>
            <td>'.$dev["id"].'</td>
            <td>'.$dev["tipovacka"].'</td>
            <td>'.$dev["login"].'</td>
            <td>'.$dev["body_zapasy"].'</td>
            <td>'.$dev["body_poradi"].'</td>
            <td>'.$dev["body_otazky"].'</td>
            <td>'.$dev["body_celkem"].'</td>
            <td>'.$registered.'</td>
            <td>'.format_datetime_www($dev["ts_i"]).'</td>
            <td class="text-center"> <!-- blokovat/povolit -->
                '.$blocked.'</td>
            <td class="text-center"> <!-- smazat -->
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=06&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function tipovacka_users_rel_delete ($pdo, $id): void
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE zdef_tipovacka_users_rel SET valid = 0, registered = 0, user_u = :qn_user_u WHERE id = :id";
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

function tipovacka_users_rel_disable ($pdo, $id): void
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE zdef_tipovacka_users_rel SET registered = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Uživatel byl zablokován</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Uživatel nebyl zablokován</span></a>';
        echo $error;
    }
}

function tipovacka_users_rel_enable ($pdo, $id): void
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE zdef_tipovacka_users_rel SET registered = 1, valid = 1, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Uživatel byl povolen</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Uživatel nebyl povolen</span></a>';
        echo $error;
    }
}

function tipovacka_users_rel_prepocet ($pdo, $tipovacka_id): void
{
    $sql1 = 'SELECT * FROM zdef_tipovacka_users_rel WHERE tipovacka_id = :tipovacka_id AND valid = 1';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['tipovacka_id'=>$tipovacka_id]);
    $stmt = $res1->fetchAll();
    if ($tipovacka_id == 0):
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Nelze přepočítat všechny tipovačky, vyberte defaultní.</span></a>';
    else:
        foreach ($stmt as $dev1)
        {
            $sql2 = 'SELECT SUM(body) FROM zdef_tipovacka_tips_zapasy WHERE tipovacka_id = :tipovacka_id AND user_id = :user_id AND valid = 1';
            $res2 = $pdo->prepare($sql2);
            $res2->execute(['tipovacka_id'=>$tipovacka_id, 'user_id'=>$dev1['user_id']]);
            $dev2 = $res2->fetchColumn();

            $sql3 = 'SELECT sum(body) FROM zdef_tipovacka_tips_poradi WHERE tipovacka_id = :tipovacka_id AND user_id = :user_id AND valid = 1';
            $res3 = $pdo->prepare($sql3);
            $res3->execute(['tipovacka_id'=>$tipovacka_id, 'user_id'=>$dev1['user_id']]);
            $dev3 = $res3->fetchColumn();

            $body_celkem = $dev2 + $dev3;

            $sql4 = 'UPDATE zdef_tipovacka_users_rel SET body_zapasy = :body_zapasy, body_poradi = :body_poradi, body_celkem = :body_celkem WHERE id = :id';
            $res4 = $pdo->prepare($sql4);
            $res4->execute(['body_zapasy'=>$dev2, 'body_poradi'=>$dev3, 'body_celkem'=>$body_celkem, 'id'=>$dev1['id']]);
        }
        echo '<div class="btn btn-success btn-icon-split w-25 text-left">
              <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Uživatelé tipovačky byly přepočteni.</span></div>';
    endif;

}

function tipovacka_tips_poradi_vypis ($pdo, $limit, $valid, $tipovacka_id): void
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;

    if ($tipovacka_id == 0):
        $sql = "SELECT zttp.id as id, zttp.poradi as poradi, zttp.body as body, zt.nazev_cz as tipovacka, ztu.login as login, ztt.nazev_cz as team, ztt.poradi_final as poradi_final
            FROM zdef_tipovacka_tips_poradi zttp 
                INNER JOIN zdef_tipovacka zt on zttp.tipovacka_id = zt.id
                INNER JOIN zdef_tipovacka_users ztu on zttp.user_id = ztu.id 
                INNER JOIN zdef_tipovacka_teams ztt on zttp.team_id = ztt.id
            WHERE zttp.valid = :valid 
            ORDER BY zttp.poradi, zttp.id LIMIT :sqllimit";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    else:
        $sql = "SELECT zttp.id as id, zttp.poradi as poradi, zttp.body as body, zt.nazev_cz as tipovacka, ztu.login as login, ztt.nazev_cz as team, ztt.poradi_final as poradi_final
            FROM zdef_tipovacka_tips_poradi zttp
                INNER JOIN zdef_tipovacka zt on zttp.tipovacka_id = zt.id
                INNER JOIN zdef_tipovacka_users ztu on zttp.user_id = ztu.id 
                INNER JOIN zdef_tipovacka_teams ztt on zttp.team_id = ztt.id
            WHERE zttp.valid = :valid AND zttp.tipovacka_id =:tipovacka_id
            ORDER BY zttp.poradi, zttp.id LIMIT :sqllimit";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit, 'tipovacka_id'=>$tipovacka_id]);
    endif;

    $stmt = $res->fetchAll();
    foreach ($stmt as $dev)
    {
        if ($valid == 0):
            $undelete = '<a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=12&amp;undel='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-edit"></i></a>';
        else:
            $undelete = '';
        endif;
        echo '
        <tr>
            <td>'.$dev["id"].'</td>
            <td>'.$dev["tipovacka"].'</td>
            <td>'.$dev["login"].'</td>
            <td>'.$dev["team"].'</td>
            <td>'.$dev["poradi"].'</td>
            <td>'.$dev["poradi_final"].'</td>
            <td>'.$dev["body"].'</td>
            <td class="text-center"> <!-- smazat -->
                '.$undelete.'</td>
            <td class="text-center"> <!-- smazat -->
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=12&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function tipovacka_tips_poradi_delete ($pdo, $id): void
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE zdef_tipovacka_tips_poradi SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Tip byl smazán</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Tip nebyl smazán</span></a>';
        echo $error;
    }
}

function tipovacka_tips_poradi_undelete ($pdo, $id): void
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE zdef_tipovacka_tips_poradi SET valid = 1, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Tip byl obnoven</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Tip nebyl obnoven</span></a>';
        echo $error;
    }
}

function tipovacka_tips_zapasy_vypis ($pdo, $limit, $valid, $tipovacka_id, $dupl): void
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;

    if ($dupl == 1):
        $sql = "SELECT zttz.id as id, zttz.team1_goals as team1_goals, zttz.team2_goals as team2_goals, zttz.body as body, zttz.tip as tip, zt.nazev_cz as tipovacka, zttz.ts_u as ts_u,
                ztu.login as login, ztz.team1_id as team1_id, ztz.team2_id as team2_id, ztz.poradi as poradi, ztz.skupina as skupina, ztz.datetime as datetime, ztz.team1_goals as vysl_team1_goals, ztz.team2_goals as vysl_team2_goals
            FROM zdef_tipovacka_tips_zapasy zttz 
                INNER JOIN zdef_tipovacka zt on zttz.tipovacka_id = zt.id
                INNER JOIN zdef_tipovacka_users ztu on zttz.user_id = ztu.id 
                INNER JOIN zdef_tipovacka_zapasy ztz on zttz.zapas_id = ztz.id
            WHERE (select count(*) from zdef_tipovacka_tips_zapasy b where b.tipovacka_id = zttz.tipovacka_id and b.user_id = zttz.user_id and b.zapas_id = zttz.zapas_id) > 1
            ORDER BY ztu.login, ztz.poradi, zttz.id";
        $res = $pdo->prepare($sql);
        $res->execute();
    elseif ($tipovacka_id == 0):
        $sql = "SELECT zttz.id as id, zttz.team1_goals as team1_goals, zttz.team2_goals as team2_goals, zttz.body as body, zttz.tip as tip, zt.nazev_cz as tipovacka, zttz.ts_u as ts_u,
                ztu.login as login, ztz.team1_id as team1_id, ztz.team2_id as team2_id, ztz.poradi as poradi, ztz.skupina as skupina, ztz.datetime as datetime, ztz.team1_goals as vysl_team1_goals, ztz.team2_goals as vysl_team2_goals
            FROM zdef_tipovacka_tips_zapasy zttz 
                INNER JOIN zdef_tipovacka zt on zttz.tipovacka_id = zt.id
                INNER JOIN zdef_tipovacka_users ztu on zttz.user_id = ztu.id 
                INNER JOIN zdef_tipovacka_zapasy ztz on zttz.zapas_id = ztz.id
            WHERE zttz.valid = :valid 
            ORDER BY ztu.login, ztz.poradi, zttz.id LIMIT :sqllimit";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    else:
        $sql = "SELECT zttz.id as id, zttz.team1_goals as team1_goals, zttz.team2_goals as team2_goals, zttz.body as body, zttz.tip as tip, zt.nazev_cz as tipovacka, zttz.ts_u as ts_u,
                ztu.login as login, ztz.team1_id as team1_id, ztz.team2_id as team2_id, ztz.poradi as poradi, ztz.skupina as skupina, ztz.datetime as datetime, ztz.team1_goals as vysl_team1_goals, ztz.team2_goals as vysl_team2_goals
            FROM zdef_tipovacka_tips_zapasy zttz 
                INNER JOIN zdef_tipovacka zt on zttz.tipovacka_id = zt.id
                INNER JOIN zdef_tipovacka_users ztu on zttz.user_id = ztu.id 
                INNER JOIN zdef_tipovacka_zapasy ztz on zttz.zapas_id = ztz.id
            WHERE zttz.valid = :valid AND zttz.tipovacka_id = :tipovacka_id
            ORDER BY ztu.login, ztz.poradi, zttz.id LIMIT :sqllimit";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit, 'tipovacka_id'=>$tipovacka_id]);
    endif;

    $stmt = $res->fetchAll();
    foreach ($stmt as $dev)
    {
        if ($valid == 0):
            $undelete = '<a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=11&amp;undel='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-edit"></i></a>';
        else:
            $undelete = '';
        endif;
        $zapas = tipovacka_team_name($pdo, $dev['team1_id']).' : '.tipovacka_team_name($pdo, $dev['team2_id']);
        $tip = $dev['team1_goals'].':'.$dev['team2_goals'];
        $vysledek = $dev['vysl_team1_goals'].':'.$dev['vysl_team2_goals'];
        echo '
        <tr>
            <td>'.$dev["id"].'</td>
            <td>'.$dev["tipovacka"].'</td>
            <td>'.$dev["login"].'</td>
            <td>'.$dev["poradi"].'</td>
            <td>'.$dev["skupina"].'</td>
            <td>'.$zapas.'</td>
            <td>'.$tip.'</td>
            <td>'.$vysledek.'</td>
            <td>'.$dev['tip'].'</td>
            <td>'.$dev["body"].'</td>
            <td class="text-center"> <!-- smazat -->
                '.$undelete.'</td>
            <td class="text-center"> <!-- smazat -->
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=51&amp;sec_page=11&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
            <td><small>'.format_datetime_www($dev["ts_u"]).'</small></td>
        </tr>';
    }
}

function tipovacka_tips_zapasy_delete ($pdo, $id): void
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE zdef_tipovacka_tips_zapasy SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Tip byl smazán</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Tip nebyl smazán</span></a>';
        echo $error;
    }
}

function tipovacka_tips_zapasy_undelete ($pdo, $id): void
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE zdef_tipovacka_tips_zapasy SET valid = 1, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Tip byl obnoven</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Tip nebyl obnoven</span></a>';
        echo $error;
    }
}

function tipovacka_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM zdef_tipovacka WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}

function tipovacka_name ($pdo, $id)
{
    $sql = "SELECT nazev_cz FROM zdef_tipovacka WHERE id = :id AND valid = 1";
    $res = $pdo->prepare($sql);
    $res->execute(['id' => $id]);
    $dev = $res->fetch();
    return $dev['nazev_cz'] ?? 'vše';
}

function tipovacka_team_name ($pdo, $id)
{
    $sql = "SELECT nazev_cz FROM zdef_tipovacka_teams WHERE id = :id AND valid = 1";
    $res = $pdo->prepare($sql);
    $res->execute(['id' => $id]);
    $dev = $res->fetch();
    return $dev['nazev_cz'] ?? '';
}

function tipovacka_teams_count ($pdo, $valid, $tipovacka_id)
{
    if ($tipovacka_id == 0):
        $sql = "SELECT count(*) FROM zdef_tipovacka_teams WHERE valid = :valid";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid]);
    else:
        $sql = "SELECT count(*) FROM zdef_tipovacka_teams WHERE valid = :valid AND tipovacka_id = :tipovacka_id";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'tipovacka_id'=>$tipovacka_id]);
    endif;
    return $res->fetchColumn();
}

function tipovacka_teams_option_form ($pdo, $select, $tipovacka_id): void
{
    $sql = "SELECT id, poradi, nazev_cz FROM zdef_tipovacka_teams WHERE valid = 1 AND tipovacka_id = :tipovacka_id ORDER BY poradi, nazev_cz";
    $res = $pdo->prepare($sql);
    $res->execute(['tipovacka_id'=>$tipovacka_id]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        $id = $dev['id'];
        $poradi= $dev['poradi'];
        $nazev_cz = stripslashes($dev['nazev_cz']);
        if ($select == $id):
            echo '<option value='.$id.' selected="selected">'.$poradi.'&nbsp;-&nbsp;'.$nazev_cz.'</option>';
        else:
            echo '<option value='.$id.'>'.$poradi.'&nbsp;-&nbsp;'.$nazev_cz.'</option>';
        endif;
        echo "\n";
    }
}

function tipovacka_users_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM zdef_tipovacka_users WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}

function tipovacka_users_rel_count ($pdo, $valid, $tipovacka_id)
{
    if ($tipovacka_id == 0):
        $sql = "SELECT count(*) FROM zdef_tipovacka_users_rel WHERE valid = :valid";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid]);
    else:
        $sql = "SELECT count(*) FROM zdef_tipovacka_users_rel WHERE valid = :valid AND tipovacka_id = :tipovacka_id";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'tipovacka_id'=>$tipovacka_id]);
    endif;
    return $res->fetchColumn();
}

function tipovacka_tips_poradi_count ($pdo, $valid, $tipovacka_id)
{
    if ($tipovacka_id == 0):
        $sql = "SELECT count(*) FROM zdef_tipovacka_tips_poradi WHERE valid = :valid";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid]);
    else:
        $sql = "SELECT count(*) FROM zdef_tipovacka_tips_poradi WHERE valid = :valid AND tipovacka_id = :tipovacka_id";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'tipovacka_id'=>$tipovacka_id]);
    endif;
    return $res->fetchColumn();
}

function tipovacka_tips_zapasy_count ($pdo, $valid, $tipovacka_id)
{
    if ($tipovacka_id == 0):
        $sql = "SELECT count(*) FROM zdef_tipovacka_tips_zapasy WHERE valid = :valid";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid]);
    else:
        $sql = "SELECT count(*) FROM zdef_tipovacka_tips_zapasy WHERE valid = :valid AND tipovacka_id = :tipovacka_id";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'tipovacka_id'=>$tipovacka_id]);
    endif;
    return $res->fetchColumn();
}

function tipovacka_zapasy_count ($pdo, $valid, $tipovacka_id)
{
    if ($tipovacka_id == 0):
        $sql = "SELECT count(*) FROM zdef_tipovacka_zapasy WHERE valid = :valid";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid]);
    else:
        $sql = "SELECT count(*) FROM zdef_tipovacka_zapasy WHERE valid = :valid AND tipovacka_id = :tipovacka_id";
        $res = $pdo->prepare($sql);
        $res->execute(['valid'=>$valid, 'tipovacka_id'=>$tipovacka_id]);
    endif;
    return $res->fetchColumn();
}

function tipovacka_zapasy_nextporadi ($pdo, $tipovacka_id): int
{
    if ($tipovacka_id == 0):
        $nextporadi = 0;
    else:
        $sql = "SELECT max(poradi) FROM zdef_tipovacka_zapasy WHERE valid = 1 AND tipovacka_id = :tipovacka_id";
        $res = $pdo->prepare($sql);
        $res->execute(['tipovacka_id'=>$tipovacka_id]);
        $nextporadi = $res->fetchColumn()+1;
    endif;
    return $nextporadi;
}