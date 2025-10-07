<?php
function galerie_typ_add ($pdo, $nazev_cz, $nazev_en, $poradi, $popis_cz, $popis_en, $color)
{
    $nazev_cz = addslashes($nazev_cz);
    $nazev_en = addslashes($nazev_en);
    $popis_cz = addslashes($popis_cz);
    $popis_en = addslashes($popis_en);
    $color = addslashes($color);
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO galerie_typ (poradi, nazev_cz, nazev_en, popis_cz, popis_en, color, user_i, user_u) VALUES 
		(:poradi, :nazev_cz, :nazev_en, :popis_cz, :popis_en, :color, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['poradi'=>$poradi, 'nazev_cz'=>$nazev_cz, 'nazev_en'=>$nazev_en, 'popis_cz'=>$popis_cz, 'popis_en'=>$popis_en, 'color'=>$color, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Typ galerie nebyl vložen</span></a>';
        echo $error;
    }
}

function galerie_typ_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT * FROM galerie_typ WHERE valid = :valid ORDER BY poradi LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        echo '<tr>
        	<td>'.$dev["id"].'</td>
            <td>'.stripslashes($dev["nazev_cz"]).'</td>
            <td>'.$dev["poradi"].'</td>
            <td>'.$dev["color"].'</td>
            <td class="text-center">
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=03&amp;sec_page=03&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></a></td>
            <td class="text-center">
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=03&amp;sec_page=03&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function galerie_typ_edit ($pdo, $id, $nazev_cz, $nazev_en, $poradi, $popis_cz, $popis_en, $color, $valid)
{
    $nazev_cz = addslashes($nazev_cz);
    $nazev_en = addslashes($nazev_en);
    $popis_cz = addslashes($popis_cz);
    $popis_en = addslashes($popis_en);
    $color = addslashes($color);
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE galerie_typ SET poradi = :poradi, nazev_cz = :nazev_cz, nazev_en = :nazev_en, popis_cz = :popis_cz, popis_en = :popis_en, color = :color,
		valid = :valid, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['poradi'=>$poradi, 'nazev_cz'=>$nazev_cz, 'nazev_en'=>$nazev_en, 'popis_cz'=>$popis_cz, 'popis_en'=>$popis_en, 'color'=>$color, 'valid'=>$valid,
            'qn_user_u'=>$qn_user, 'id'=>$id]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not edited: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Typ galerie nebyl uložen</span></a>';
        echo $error;
    }
}

function galerie_typ_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE galerie_typ SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Typ galerie byl smazán</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Typ galerie nebyl smazán</span></a>';
        echo $error;
    }
}

function galerie_typ_option_form ($pdo, $select)
{
    $sql = "SELECT id, nazev_cz FROM galerie_typ WHERE valid = 1 ORDER BY poradi";
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

function galerie_add ($pdo, $datum, $galerie_typ, $nazev_cz, $text_cz, $popis_cz, $visible)
{
    $nazev_cz = addslashes($nazev_cz);
    $text_cz = addslashes($text_cz);
    $popis_cz = addslashes($popis_cz);
    $url_cz = text_str(addslashes($nazev_cz)).'-'.$datum;
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO galerie (datum, url_cz, galerie_typ, nazev_cz, text_cz, popis_cz, visible, user_i, user_u) VALUES (:datum, :url_cz, :galerie_typ, :nazev_cz, :text_cz, :popis_cz, :visible, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['datum'=>$datum, 'url_cz'=>$url_cz, 'galerie_typ'=>$galerie_typ, 'nazev_cz'=>$nazev_cz, 'text_cz'=>$text_cz, 'popis_cz'=>$popis_cz, 'visible'=>$visible, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        $insertId = $pdo->lastInsertId();
        unset ($_POST['add']);
        umask(0000);
        if(mkdir ('../images/_galerie/'.$insertId.'-galerie')):
            echo '<span class="warning">Adresář "'.$insertId.'-galerie" pro ukládání fotek byl úspěšně vytvořen</span><br />';
        else:
            echo '<span class="warning">Adresář pro ukládání fotek nebyl vytvořen</span><br />';
        endif;
        umask(0000);
        if(mkdir ('../images/_galerie/'.$insertId.'-galerie/small')):
            echo '<span class="warning">Adresář "'.$insertId.'-galerie/small" pro náhledy byl úspěšně vytvořen</span><br />';
        else:
            echo '<span class="warning">Adresář pro náhledy nebyl vytvořen</span><br />';
        endif;
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Galerie nebyla vložena</span></a>';
        echo $error;
    }
}

function galerie_edit ($pdo, $id, $datum, $galerie_typ, $nazev, $text, $popis_cz, $visible, $lang, $url, $valid)
{
    $nazev = addslashes($nazev);
    $text = addslashes($text);
    $popis_cz = addslashes($popis_cz);
    $qn_user = $_SESSION["qn_user"];

    if ($lang == "cz"):
        $sql = "UPDATE galerie SET url_cz = :url, datum = :datum, galerie_typ = :galerie_typ, nazev_cz = :nazev, text_cz = :text, popis_cz = :popis_cz, visible = :visible, 
                valid = :valid, user_u = :qn_user_u WHERE id = :id";
    else:
        $sql = "UPDATE galerie SET url_en = :url, datum = :datum, galerie_typ = :galerie_typ, nazev_en = :nazev, text_en = :text, popis_cz = :popis_cz, visible = :visible, 
                valid = :valid, user_u = :qn_user_u WHERE id = :id";
    endif;
    $res = $pdo->prepare($sql);

    try {
        $res->execute(['url'=>$url, 'datum'=>$datum, 'galerie_typ'=>$galerie_typ, 'nazev'=>$nazev, 'text'=>$text, 'popis_cz'=>$popis_cz, 'visible'=>$visible,
            'valid'=>$valid, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not updated: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Galerie nebyla uložena</span></a>';
        echo $error;
    }
}

function galerie_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT g.id, g.url_cz, g.nazev_cz, g.datum, g.galerie_typ, g.visible, gt.nazev_cz as typ FROM galerie g, galerie_typ gt WHERE gt.id = g.galerie_typ AND g.valid = :valid ORDER BY g.datum DESC, g.id DESC LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        if($dev["visible"] == 0):       $visible = 'NE'; elseif($dev["visible"] == 1): $visible = "CZ/EN"; elseif($dev["visible"] == 2): $visible = "CZ"; elseif($dev["visible"] == 3): $visible = "EN"; else: $visible=''; endif;
        if(en_on($pdo)==0):
            $en_edit = "";
        else:
            $en_edit='<a class="btn btn-primary btn-circle btn-sm" href="index.php?section=01&amp;page=03&amp;sec_page=02&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;lang=en&amp;show=2">
                <i class="fas fa-edit"></i></a>';
        endif;
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/cz/index/galerie/".$dev["url_cz"];
        $galerie_photo = galerie_photo_count($pdo, $dev["id"]);

        echo '
        <tr>
            <td>'.$dev["id"].'</td>
            <td>'.stripslashes($dev["typ"]).'</td>
            <td>'.stripslashes($dev["nazev_cz"]).'</td>
            <td>'.format_date_www($dev["datum"]).'</td>
            <td>'.$galerie_photo.'</td>
            <td>'.$visible.'</td>
            <td class="text-center"> <!-- zobrazit fotografie -->
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=03&amp;sec_page=05&amp;view='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-eye"></i></a></td>
            <td class="text-center"> <!-- nahled -->
                <a class="btn btn-primary btn-circle btn-sm" href="'.$url.'" target="_blank">
                <i class="fas fa-external-link-alt"></i></a></td>
            <td class="text-center"> <!-- editace EN -->'.$en_edit.'</td>
            <td class="text-center"> <!-- upravit -->
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=01&amp;page=03&amp;sec_page=02&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></a></td>
            <td class="text-center"> <!-- pridat foto -->
                <a class="btn btn-warning btn-circle btn-sm" href="index.php?section=01&amp;page=03&amp;sec_page=06&amp;photo='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-images"></i></a></td>
            <td class="text-center"> <!-- smazat -->
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=01&amp;page=03&amp;sec_page=02&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
        </tr>';
    }
}

function galerie_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE galerie SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Galerie byla smazána</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Galerie nebyla smazána</span></a>';
        echo $error;
    }
}

//funkce pro presun fotografii z temp slozky do slozky galerie
function galerie_photo_move ($pdo, $galerie_id)
{
    $dir_galerie = '../images/_galerie/'.$galerie_id.'-galerie/';
    $dir_galerie_small = '../images/_galerie/'.$galerie_id.'-galerie/small/';
    $dir_temp = SEC_DIR."/_include/pages/galerie/temp/";

    $galerie_orig_width = sp_hodnota($pdo, 'galerie_orig_width');
    $galerie_orig_height = sp_hodnota($pdo, 'galerie_orig_height');
    $galerie_thumb_width = sp_hodnota($pdo, 'galerie_thumb_width');
    $galerie_thumb_height = sp_hodnota($pdo, 'galerie_thumb_height');
    $qn_user = $_SESSION['qn_user'];

    $sql = "SELECT id, soubor FROM galerie_photo WHERE galerie_id = 999999 ORDER BY id ";
    $res = $pdo->prepare($sql);
    $res->execute();
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        $id = $dev['id'];
        $soubor = $dev['soubor'];
        copy($dir_temp . $soubor, $dir_galerie . $soubor);
        rename($dir_temp . $soubor, $dir_galerie_small . $soubor);

        $file_orig = $dir_galerie . $soubor;
        $file_thumb = $dir_galerie_small . $soubor;

        //* vytvoreni originalu
        list($width, $height) = create_thumbnail($file_orig, $galerie_orig_width, $galerie_orig_height);
        if ($width && $height):
            image_resize($pdo, $file_orig, $width, $height);

        endif;

        //* vytvoreni thumbnailu
        list($width, $height) = create_thumbnail($file_thumb, $galerie_thumb_width, $galerie_thumb_height);
        if ($width && $height):
            image_resize($pdo, $file_thumb, $width, $height);

        endif;

        $sql1 = "UPDATE galerie_photo SET galerie_id = :galerie_id, user_i = :qn_user_u, user_u = :qn_user_i WHERE id = :id";
        $res1 = $pdo->prepare($sql1);
        $res1->execute(['galerie_id'=>$galerie_id, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user, 'id'=>$id]);
    }
}

//funkce pro vytvoreni fotografie
function create_image($file_in, $max_x = 0, $max_y = 0): array
{
	list($width, $height) = getimagesize($file_in);
    if (!$width || !$height) {
        return array(0, 0);
    }
    if ($max_x && $width > $max_x) {
        $height = round($height * $max_x / $width);
        $width = $max_x;
    }
    if ($max_y && $height > $max_y) {
        $width = round($width * $max_y / $height);
        $height = $max_y;
    }
    return array($width, $height);
}

//funkce pro zmenseni fotografie a ulozeni
function image_resize($pdo, $file_in, $new_width, $new_height)
{
    $info = getimagesize($file_in);
    $mime = $info['mime'];
    $tmp = imagecreatetruecolor($new_width, $new_height);
    switch ($mime) {
        case 'image/jpeg':
            $image_create_func = 'imagecreatefromjpeg';
            $image_save_func = 'imagejpeg';
            $new_image_ext = 'jpg';
            break;
        case 'image/png':
            $image_create_func = 'imagecreatefrompng';
            $image_save_func = 'imagepng';
            $new_image_ext = 'png';
            $background = imagecolorallocate($tmp , 0, 0, 0);
            imagecolortransparent($tmp, $background);
            imagealphablending($tmp, false);
            imagesavealpha($tmp, true);
            break;
        case 'image/gif':
            $image_create_func = 'imagecreatefromgif';
            $image_save_func = 'imagegif';
            $new_image_ext = 'gif';
            break;
        default:
            throw new Exception('Unknown image type.');
    }
    $img = $image_create_func($file_in);
    list($width, $height) = getimagesize($file_in);

    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    if (file_exists($file_in)): unlink($file_in); endif;
    if($image_save_func($tmp, "$file_in")):
        echo 'Obrázek '.$file_in.' vložen <br />';
    else:
        echo 'Obrázek '.$file_in.' nebyl vložen <br />';
    endif;
}

//funkce pro nahled fotogalerie s obrazky
function galerie_view ($pdo, $galerie_id)
{

    $sql = "SELECT * FROM galerie_photo WHERE galerie_id = :galerie_id ORDER BY poradi, id";
    $res = $pdo->prepare($sql);
    $res->execute(['galerie_id'=>$galerie_id]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        $id = $dev['id'];
        $nazev_cz = stripslashes($dev['nazev_cz']);
        $soubor = $dev['soubor'];
        $poradi = $dev['poradi'];
        $url =  (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/images/_galerie/".$galerie_id."-galerie";

        echo '<div class="smallsquare">

                <a href="'.$url.'/'.$soubor.'?image='.$poradi.'" data-toggle="lightbox" data-gallery="example-gallery" class="img-grid-c">
                    <img src="'.$url.'/small/'.$soubor.'?image='.$poradi.'" alt="'.$nazev_cz.'" class="img-grid-c">
                </a>
            </div>
            <div class="smallsquare_edit">
                <a href="#" title="Pořadí" class="d-none d-block btn btn-sm btn-secondary shadow-sm mt-1 mr-1 text-small"><small>'.$poradi.'</small></a>
                <a href="index.php?section=01&amp;page=03&amp;sec_page=05&amp;view='.$galerie_id.'&amp;edit='.$id.'&amp;show=2" title="Upravit" class="d-none d-block btn btn-sm btn-primary shadow-sm mt-1 mr-1">
                    <i class="fas fa-edit fa-sm text-white-50"></i> </a>
                <a href="index.php?section=01&amp;page=03&amp;sec_page=05&amp;view='.$galerie_id.'&amp;del='.$id.'" title="Smazat" class="d-none d-block btn btn-sm btn-danger shadow-sm mt-1 mr-1">
                    <i class="fas fa-trash fa-sm text-white-50"></i> </a>
            </div>';

	}
}

function galerie_photo_delete ($pdo, $photo, $galerie_id)
{
    $sql = "SELECT soubor FROM galerie_photo WHERE id = :photo";
    $res = $pdo->prepare($sql);
    $res->execute(['photo'=>$photo]);
    $dev = $res->fetch();
    $soubor = stripslashes($dev['soubor']);

    $delete_photo = unlink('../images/_galerie/'.$galerie_id.'-galerie/'.$soubor);
    $delete_photo_small = unlink('../images/_galerie/'.$galerie_id.'-galerie/small/'.$soubor);

    if ($delete_photo): echo '<span class="warning">Originál obrázku smazán</span><br />';endif;
    if ($delete_photo_small):	echo '<span class="warning">Thumbnail obrázku smazán</span><br />';endif;

    $sql1 = "delete FROM galerie_photo WHERE id = :photo";
    $res1 = $pdo->prepare($sql1);
    try {
        $res1->execute(['photo'=>$photo]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Fotografie byla smazána</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Fotgrafie nebyla smazána</span></a>';
        echo $error;
    }
}

//funkce pro upravu popisku u fotografie
function galerie_photo_edit ($pdo, $id, $nazev_cz, $nazev_en, $poradi)
{
    $qn_user = $_SESSION['qn_user'];
    $sql = "UPDATE galerie_photo SET nazev_cz = :nazev_cz, nazev_en = :nazev_en, poradi = :poradi, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['nazev_cz'=>$nazev_cz, 'nazev_en'=>$nazev_en, 'poradi'=>$poradi, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not updated: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Fotografie nebyla uložena</span></a>';
        echo $error;
    }

}

//funkce pro aktualizaci poradi aktualni galerie podle nazvu
function galerie_photo_poradi_update ($pdo, $galerie_id)
{
    $qn_user = $_SESSION['qn_user'];
	$i = 0;

	$sql = "SELECT id, soubor FROM galerie_photo WHERE galerie_id = :galerie_id ORDER BY soubor";
    $res = $pdo->prepare($sql);
    $res->execute(['galerie_id'=>$galerie_id]);
    $stmt = $res->fetchAll();

	foreach ($stmt as $dev)
    {
		$i++;
		$id = $dev['id'];
		$sql1 = "UPDATE galerie_photo SET poradi = :i, user_u = :qn_user_u WHERE id = :id";
        $res1 = $pdo->prepare($sql1);
        $res1->execute(['i'=>$i, 'qn_user_u'=>$qn_user, 'id'=>$id]);
	}
    echo '<div class="btn btn-success btn-icon-split w-25 text-left">
              <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Pořadí u galerie bylo aktualizováno</span></div>';
}

//funkce pro odstraneni duplicit v galerii
function galerie_photo_duplicity_delete ($pdo, $galerie_id)
{
    $sql = "DELETE gp1 FROM galerie_photo gp1, galerie_photo gp2 WHERE gp1.id > gp2.id AND gp1.soubor = gp2.soubor AND gp1.galerie_id = :galerie_id1 AND gp2.galerie_id = :galerie_id2";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['galerie_id1' => $galerie_id, 'galerie_id2' => $galerie_id]);
        echo '<div class="btn btn-success btn-icon-split w-25 text-left">
              <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Duplicity u galerie byly odstraněny</span></div>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Duplicity nebyly smazány</span></a>';
        echo $error;
    }
}

function galerie_typ_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM galerie_typ WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}
function galerie_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM galerie WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}
function galerie_photo_count ($pdo, $galerie_id)
{
    $sql = "SELECT count(*) FROM galerie_photo WHERE valid = 1 AND galerie_id = :galerie_id";
    $res = $pdo->prepare($sql);
    $res->execute(['galerie_id'=>$galerie_id]);
    return $res->fetchColumn();
}

function galerie_name ($pdo, $galerie_id)
{
    $sql = "SELECT nazev_cz FROM galerie WHERE valid = 1 AND id = :galerie_id";
    $res = $pdo->prepare($sql);
    $res->execute(['galerie_id'=>$galerie_id]);
    return $res->fetchColumn();
}

function galerie_temp_photo_delete ($pdo, $galerie_id)
{
    $sql = "DELETE FROM galerie_photo WHERE valid = 1 AND galerie_id = :galerie_id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['galerie_id'=>$galerie_id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">TEMP galerie byla smazána</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">TEMP galerie nebyla smazána</span></a>';
        echo $error;
    }
}