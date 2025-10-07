<?php
function dotazy_kat_add ($pdo, $nazev_cz, $nazev_en, $poradi, $email, $visible)
{
    $nazev_cz = addslashes($nazev_cz);
    $nazev_en = addslashes($nazev_en);
    $email = addslashes($email);
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO dotazy_kat (poradi, nazev_cz, nazev_en, email, visible, user_i, user_u) VALUES 
		(:poradi, :nazev_cz, :nazev_en, :email, :visible, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['poradi'=>$poradi, 'nazev_cz'=>$nazev_cz, 'nazev_en'=>$nazev_en, 'email'=>$email, 'visible'=>$visible, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Kategorie dotazů nebyla vložena</span></a>';
        echo $error;
    }
}

function dotazy_kat_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT * FROM dotazy_kat WHERE valid = :valid ORDER BY poradi LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        if ($dev['visible']==0): $visible = "NE"; else: $visible = "ANO"; endif;
	echo '<tr>
        	<td>'.$dev["id"].'</td>
            <td>'.stripslashes($dev["nazev_cz"]).'</td>
            <td>'.$dev["poradi"].'</td>
            <td>'.$visible.'</td>
            <td>'.stripslashes($dev["email"]).'</td>
            <td class="text-center">
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=09&amp;sec_page=53&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></a></td>
            <td class="text-center">
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=09&amp;sec_page=53&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function dotazy_kat_edit ($pdo, $id, $nazev_cz, $nazev_en, $poradi, $email, $visible, $valid)
{
    $nazev_cz = addslashes($nazev_cz);
    $nazev_en = addslashes($nazev_en);
    $email = addslashes($email);
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE dotazy_kat SET poradi = :poradi, nazev_cz = :nazev_cz, nazev_en = :nazev_en, email = :email, visible = :visible,
		valid = :valid, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['poradi'=>$poradi, 'nazev_cz'=>$nazev_cz, 'nazev_en'=>$nazev_en, 'email'=>$email, 'visible'=> $visible, 'valid'=>$valid,
        'qn_user_u'=>$qn_user, 'id'=>$id]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not edited: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Kategorie dotazů nebyla vložena</span></a>';
        echo $error;
    }
}

function dotazy_kat_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE dotazy_kat SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Kategorie dotazů byla smazána</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Kategorie dotazů nebyla smazána</span></a>';
        echo $error;
    }
}

function dotazy_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT d.id, d.datum, d.name, d.text, d.email, d.mobil, dk.nazev_cz as kat 
            FROM dotazy d LEFT OUTER JOIN dotazy_kat dk on d.dotazy_kat = dk.id WHERE d.valid = :valid ORDER BY d.datum DESC LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        $text = stripslashes($dev['text']);
        echo '
        <tr>
            <td>'.$dev["id"].'</td>
            <td>'.format_datetime_www($dev["datum"]).'</td>
            <td>'.$dev["kat"].'</td>
            <td>'.stripslashes($dev["name"]).'</td>
            <td><button type="button" class="btn btn-md btn-danger" data-toggle="popover" title="Dotaz" data-placement="bottom" data-trigger="focus" data-content="'.$text.'">Zobraz si dotaz</button></td>
            <td>'.stripslashes($dev["email"]).'</td>
            <td>'.stripslashes($dev["mobil"]).'</td>
            <td class="text-center"> <!-- smazat -->
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=09&amp;sec_page=52&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function dotazy_delete ($pdo, $id)
{
    $sql = "UPDATE dotazy SET valid = 0 WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Dotaz byl smazán</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Dotaz nebyl smazán</span></a>';
        echo $error;
    }
}

function dotazy_kat_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM dotazy_kat WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}
function dotazy_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM dotazy WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}

