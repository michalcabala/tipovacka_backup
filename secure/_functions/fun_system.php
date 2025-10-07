<?php
function menu_add ($pdo, $url_cz, $nazev_cz, $menu)
{
    $nazev_cz = addslashes($nazev_cz);
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO menu (url_cz, nazev_cz, menu, user_i, user_u) VALUES (:url_cz, :nazev_cz, :menu, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['url_cz'=>$url_cz, 'nazev_cz'=>$nazev_cz, 'menu'=>$menu, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch(PDOException $e) {
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Menu nebylo vloženo</span></a>';
        echo $error;
    }
}

function menu_edit ($pdo, $id, $url_cz, $nazev_cz, $menu, $valid)
{
    $nazev_cz = addslashes($nazev_cz);
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE menu SET url_cz =:url_cz, nazev_cz =:nazev_cz, menu = :menu, valid = :valid, user_u = :qn_user WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['url_cz'=>$url_cz, 'nazev_cz'=>$nazev_cz, 'menu'=>$menu, 'valid'=>$valid, 'qn_user'=>$qn_user, 'id'=>$id]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e) {
        $error = 'Data not edited: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Menu nebylo uloženo</span></a>';
        echo $error;
    }
}

function menu_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT * FROM menu WHERE valid = :valid ORDER BY menu LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        echo '<tr>
                <td>'.$dev['id'].'</td> 
                <td>'.$dev['menu'].'</td> 
                <td>'.$dev['url_cz'].'</td>
                <td>'.stripslashes($dev['nazev_cz']).'</td> 
                <td class="text-center">
                    <a class="btn btn-success btn-circle btn-sm" href="index.php?section=02&amp;page=02&amp;sec_page=03&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                        <i class="fas fa-edit"></i></a></td>
                <td class="text-center">
                    <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=02&amp;page=02&amp;sec_page=03&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                        <i class="fas fa-trash"></i></a></td>
              </tr>';
    }
}

function menu_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE menu SET valid = 0, user_u = :qn_user WHERE id = :id";
    $res = $pdo->prepare($sql);

    try {
        $res->execute(['qn_user'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Menu bylo smazáno</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not delete: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Menu nebylo smazáno</span></a>';
        echo $error;
    }
}

function menu_users_skup_vypis ($pdo, $skup_id, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = 'SELECT * FROM menu WHERE valid = :valid ORDER BY menu LIMIT :sqllimit';
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        $sql1 = "SELECT count(*) FROM menu_users_skup WHERE valid = 1 AND skup_id = :skup_id AND menu = :menu";
        $res1 = $pdo->prepare($sql1);
        $res1->execute(['skup_id'=>$skup_id, 'menu'=>$dev['menu']]);
        $dev1 = $res1->fetchColumn();

        if ($skup_id == 0):
            $pridat = $smazat = '';
        elseif ($dev1 == 0):
            $pridat = '<a class="btn btn-success btn-circle btn-sm" href="index.php?section=02&amp;page=02&amp;sec_page=04&amp;add='.$dev['menu'].'&amp;limit='.$limit.'&amp;skup_id='.$skup_id.'">
                        <i class="fas fa-edit"></i></a>';
            $smazat = '';
        else:
            $pridat = '';
            $smazat = '<a class="btn btn-danger btn-circle btn-sm" href="index.php?section=02&amp;page=02&amp;sec_page=04&amp;del='.$dev['menu'].'&amp;limit='.$limit.'&amp;skup_id='.$skup_id.'">
                        <i class="fas fa-trash"></i></a>';
        endif;

        echo '<tr>
                <td>'.$dev['id'].'</td> 
                <td>'.$dev['menu'].'</td> 
                <td>'.stripslashes($dev['nazev_cz']).'</td>
                <td>'.$dev['url_cz'].'</td> 
                <td class="text-center">'.$pridat.'</td>
                <td class="text-center">'.$smazat.'</td>
              </tr>';
    }
}

function menu_users_skup_delete ($pdo, $menu, $skup_id)
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE menu_users_skup SET valid = 0, user_u = :qn_user WHERE skup_id = :skup_id AND menu = :menu";
    $res = $pdo->prepare($sql);

    try {
        $res->execute(['qn_user'=>$qn_user, 'skup_id'=>$skup_id, 'menu'=>$menu]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Oprávnění bylo smazáno</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not delete: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Oprávnění nebylo smazáno</span></a>';
        echo $error;
    }
}

function menu_users_skup_add ($pdo, $menu, $skup_id)
{
    $sql0 = "SELECT count(*) FROM menu_users_skup WHERE valid = 1 AND menu = :menu AND skup_id = :skup_id";
    $res0 = $pdo->prepare($sql0);
    $res0->execute(['menu'=>$menu, 'skup_id'=>$skup_id]);
    $dev0 = $res0->fetchColumn();

    if ($dev0 > 0):
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Oprávnění nebylo vloženo</span></a>';
    else:
        $qn_user = $_SESSION["qn_user"];
        $sql = "INSERT INTO menu_users_skup (skup_id, menu, user_i, user_u) VALUES (:skup_id, :menu, :qn_user_i, :qn_user_u)";
        $res = $pdo->prepare($sql);
        try {
            $res->execute(['skup_id'=>$skup_id, 'menu'=>$menu, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
            echo '<a href="#" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Oprávnění bylo vloženo</span></a>';
        }
        catch (PDOException $e){
            $error = 'Data not insert: '. $e->getMessage();
            echo '<a href="#" class="btn btn-warning btn-icon-split">
                    <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Oprávnění nebylo vloženo</span></a>';
            echo $error;
        }
    endif;
}

function settings_add ($pdo, $typ, $name, $popis_cz, $hodnota, $hodnota_text)
{
    $popis_cz = addslashes($popis_cz);
    $name = addslashes($name);
    $hodnota_text = addslashes($hodnota_text);
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO settings (typ, name, popis_cz, hodnota, hodnota_text, user_i, user_u) 
        VALUES (:typ, :name, :popis_cz, :hodnota, :hodnota_text, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['typ'=>$typ, 'name'=>$name, 'popis_cz'=>$popis_cz, 'hodnota'=>$hodnota, 'hodnota_text'=>$hodnota_text, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not insert: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Hodnota nebyla vložena</span></a>';
		echo $error;
    }
}

function settings_edit ($pdo, $id, $typ, $name, $popis_cz, $hodnota, $hodnota_text, $valid)
{
    $popis_cz = addslashes($popis_cz);
    $name = addslashes($name);
    $hodnota_text = addslashes($hodnota_text);
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE settings SET typ = :typ, name = :name, popis_cz = :popis_cz, hodnota = :hodnota, hodnota_text = :hodnota_text, valid = :valid, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);

    try {
        $res->execute(['typ'=>$typ, 'name'=>$name, 'popis_cz'=>$popis_cz, 'hodnota'=>$hodnota, 'hodnota_text'=>$hodnota_text, 'valid'=>$valid, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not edited: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Hodnota nebyla uložena</span></a>';
        echo $error;
    }
}

function settings_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT id, typ, name, popis_cz, hodnota, hodnota_text FROM settings WHERE valid = :valid ORDER BY typ, name LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
	echo '<tr>
            <td>'.$dev['id'].'</td>
            <td>'.$dev['typ'].'</td>
		    <td>'.stripslashes($dev['name']).'</td>
    		<td>'.stripslashes($dev['popis_cz']).'</td>
        	<td>'.$dev['hodnota'].'</td>
        	<td>'.stripslashes($dev['hodnota_text']).'</td>
            <td class="text-center">
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=02&amp;page=02&amp;sec_page=02&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                    <i class="fas fa-edit"></i>
                </a>
           </td>
           <td class="text-center">
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=02&amp;page=02&amp;sec_page=02&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                    <i class="fas fa-trash"></i> 
                </a>
           </td>
         </tr>';
    }
}

function settings_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE settings SET valid = 0, user_u = :qn_user WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Systémová proměnná byla smazána</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Systémová proměnná nebyla smazána</span></a>';
        echo $error;
    }
}

function users_add ($pdo, $name, $login, $password, $popis_cz, $popis_en, $admin, $prava, $skup_id, $dealer_kod, $ridic_kod, $email)
{
    $name = addslashes($name);
    $login = addslashes($login);
    $pass_sha = sha1($password);
    $popis_cz = addslashes($popis_cz);
    $popis_en = addslashes($popis_en);
    $qn_user = $_SESSION["qn_user"];

    $sql = 'INSERT INTO users (name, login, password, popis_cz, popis_en, admin, prava, skup_id, dealer_kod, ridic_kod, email, user_i, user_u) 
        VALUES (:name, :login, :pass_sha, :popis_cz, :popis_en, :admin, :prava, :skup_id, :dealer_kod, :ridic_kod, :email, :qn_user_i, :qn_user_u)';
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['name'=>$name, 'login'=>$login, 'pass_sha'=>$pass_sha, 'popis_cz'=>$popis_cz, 'popis_en'=>$popis_en, 'admin'=>$admin, 'prava'=>$prava,
        'skup_id'=>$skup_id, 'dealer_kod'=>$dealer_kod, 'ridic_kod'=>$ridic_kod, 'email'=>$email, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Uživatel nebyl vložen</span></a>';
        echo $error;
    }
}

function users_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT * FROM users WHERE valid = :valid ORDER BY id LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        if ($dev['admin']==0): $admin = "NE"; else: $admin = "ANO"; endif;
        $skup_name = users_skup_name($pdo, $dev['skup_id']);
    	echo '
        <tr>
            <td>'.$dev['id'].'</td>
            <td>'.stripslashes($dev['name']).'</td>
            <td>'.stripslashes($dev['login']).'</td>
            <td>'.$dev['prava'].'</td>
            <td>'.$admin.'</td>
            <td>'.$skup_name.'</td>
            <td>'.$dev['dealer_kod'].'</td>
            <td>'.$dev['ridic_kod'].'</td>
            <td>'.$dev['email'].'</td>
            <td class="text-center">
                <a class="btn btn-success btn-circle btn-sm" href="index.php?section=02&amp;page=01&amp;sec_page=02&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                <i class="fas fa-edit"></i></a></td>
            <td class="text-center">
                <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=02&amp;page=01&amp;sec_page=02&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                <i class="fas fa-trash"></i></a></td>
	    </tr>';
    }
}

function users_edit ($pdo, $id, $name, $login, $password, $popis_cz, $popis_en, $admin, $prava, $skup_id, $dealer_kod, $ridic_kod, $email, $valid)
{
    $name = addslashes($name);
    $login = addslashes($login);
    $popis_cz = addslashes($popis_cz);
    $popis_en = addslashes($popis_en);
    $qn_user = $_SESSION["qn_user"];
    $pass_sha = sha1($password);

    if ($password <> ""):
        $sql = "UPDATE users SET name = :name, login = :login, password = :pass_sha, popis_cz = :popis_cz, popis_en = :popis_en, admin = :admin, prava = :prava, 
                 skup_id = :skup_id, dealer_kod = :dealer_kod, ridic_kod = :ridic_kod, email = :email, valid = :valid, user_u = :qn_user_u WHERE id = :id";
    else:
        $sql = "UPDATE users SET name = :name, login = :login, popis_cz = :popis_cz, popis_en = :popis_en, admin = :admin, prava = :prava, 
                 skup_id = :skup_id, dealer_kod = :dealer_kod, ridic_kod = :ridic_kod, email = :email, valid = :valid, user_u = :qn_user_u WHERE id = :id";
    endif;
    $res = $pdo->prepare($sql);
    try {
        if ($password <> ""):
            $res->execute(['name'=>$name, 'login'=>$login, 'pass_sha'=>$pass_sha, 'popis_cz'=>$popis_cz, 'popis_en'=>$popis_en, 'admin'=>$admin, 'prava'=>$prava,
                'skup_id'=>$skup_id, 'dealer_kod'=>$dealer_kod, 'ridic_kod'=>$ridic_kod, 'email'=>$email, 'valid'=>$valid, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        else:
            $res->execute(['name'=>$name, 'login'=>$login, 'popis_cz'=>$popis_cz, 'popis_en'=>$popis_en, 'admin'=>$admin, 'prava'=>$prava,
                'skup_id'=>$skup_id, 'dealer_kod'=>$dealer_kod, 'ridic_kod'=>$ridic_kod, 'email'=>$email, 'valid'=>$valid, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        endif;
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not edited: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Uživatel nebyl uložen</span></a>';
        echo $error;
    }
}

function users_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];
    $sql = "UPDATE users SET valid = 0, user_u = :qn_user_u WHERE id = :id";
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

function users_skup_add ($pdo, $nazev_cz, $poradi)
{
    $nazev_cz = addslashes($nazev_cz);
    $qn_user = $_SESSION["qn_user"];

    $sql = "INSERT INTO users_skup (poradi, nazev_cz, user_i, user_u) VALUES (:poradi, :nazev_cz, :qn_user_i, :qn_user_u)";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['poradi'=>$poradi, 'nazev_cz'=>$nazev_cz, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not inserted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Skupina uživatelů nebyla vložena</span></a>';
        echo $error;
    }
}

function users_skup_vypis ($pdo, $limit, $valid)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
    $sql = "SELECT id, nazev_cz, poradi FROM users_skup WHERE valid = :valid ORDER BY poradi LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid, 'sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        echo '
            <tr>
                <td>'.$dev["id"].'</td>
                <td>'.stripslashes($dev["nazev_cz"]).'</td>
                <td>'.$dev["poradi"].'</td>
                <td class="text-center">
                    <a class="btn btn-primary btn-circle btn-sm" href="index.php?section=02&amp;page=02&amp;sec_page=04&amp;skup_id='.$dev['id'].'">
                    <i class="fas fa-chevron-circle-right"></i></a></td>
                <td class="text-center">
                    <a class="btn btn-success btn-circle btn-sm" href="index.php?section=02&amp;page=01&amp;sec_page=03&amp;edit='.$dev['id'].'&amp;limit='.$limit.'&amp;show=2">
                    <i class="fas fa-edit"></i></a></td>
                <td class="text-center">
                    <a class="btn btn-danger btn-circle btn-sm" href="index.php?section=02&amp;page=01&amp;sec_page=03&amp;del='.$dev['id'].'&amp;limit='.$limit.'">
                    <i class="fas fa-trash"></i></a></td>
            </tr>';
    }
}

function users_skup_edit ($pdo, $id, $nazev_cz, $poradi, $valid)
{
    $nazev_cz = addslashes($nazev_cz);
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE users_skup SET poradi = :poradi, nazev_cz = :nazev_cz, valid = :valid, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['poradi'=>$poradi, 'nazev_cz'=>$nazev_cz, 'valid'=>$valid, 'qn_user_u'=>$qn_user, 'id'=>$id]);
        unset ($_POST['add']);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]1";
        echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
    }
    catch (PDOException $e){
        $error = 'Data not edited: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Skupina uživatelů nebyla uložena</span></a>';
        echo $error;
    }
}

function users_skup_delete ($pdo, $id)
{
    $qn_user = $_SESSION["qn_user"];

    $sql = "UPDATE users_skup SET valid = 0, user_u = :qn_user_u WHERE id = :id";
    $res = $pdo->prepare($sql);
    try {
        $res->execute(['qn_user_u'=>$qn_user, 'id'=>$id]);
        echo '<a href="#" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Skupina uživatelů byla smazána</span></a>';
    }
    catch (PDOException $e){
        $error = 'Data not deleted: '. $e->getMessage();
        echo '<a href="#" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Skupina uživatelů nebyla smazána</span></a>';
        echo $error;
    }
}

function users_skup_name ($pdo, $id)
{
    $sql = "SELECT nazev_cz FROM users_skup WHERE id = :id AND valid = 1";
    $res = $pdo->prepare($sql);
    $res->execute(['id'=>$id]);
    return $res->fetchColumn();
}

function users_skup_option_form ($pdo, $select)
{
    $sql = "SELECT * FROM users_skup WHERE valid = 1 ORDER BY poradi";
    $res = $pdo->prepare($sql);
    $res->execute();
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        $id = $dev['id'];
        $nazev_cz = stripslashes($dev['nazev_cz']);
        if($select == $id):
            echo '<option value='.$id.' selected="selected">'.$id.'&nbsp;-&nbsp;'.$nazev_cz.'</option>';
        else:
            echo '<option value='.$id.'>'.$id.'&nbsp;-&nbsp;'.$nazev_cz.'</option>';
        endif;
    }
}

//funkce pro vypis prihlaseni uzivatelu, vypise poslednich 300 prihlaseni
function users_log_vypis ($pdo, $limit)
{
    if ($limit == 0): $sqllimit = 999999; else: $sqllimit = $limit; endif;
	$sql = "SELECT id, login, ip, datum, web FROM users_log ORDER BY id DESC LIMIT :sqllimit";
    $res = $pdo->prepare($sql);
    $res->execute(['sqllimit'=>$sqllimit]);
    $stmt = $res->fetchAll();

	foreach ($stmt as $dev)
    {
		if ($dev['web']==0): $web = "Hlavní"; else: $web = "Administrace"; endif;

        $sql1 = 'SELECT us.nazev_cz FROM users_skup us, users u WHERE u.skup_id = us.id AND u.login = :login AND u.valid = 1 AND us.valid = 1 LIMIT 1 ';
        $res1 = $pdo->prepare($sql1);
        $res1->execute(['login'=>$dev['login']]);
        $skupina = $res1->fetchColumn();

		echo '<tr>
                <td>' . $dev['id'] . '</td>
                <td>' . $dev['login'] . '</td>
                <td>' . $skupina . '</td>
                <td>' . $dev['ip'] . '</td>
                <td>' . $dev['datum'] . '</td>
                <td>' . $web . '</td>
            </tr>';
	}
}

function settings_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM settings WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}
function menu_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM menu WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}
function menu_users_skup_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM menu_users_skup WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}
function users_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM users WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}
function users_skup_count ($pdo, $valid)
{
    $sql = "SELECT count(*) FROM users_skup WHERE valid = :valid";
    $res = $pdo->prepare($sql);
    $res->execute(['valid'=>$valid]);
    return $res->fetchColumn();
}
function users_log_count ($pdo)
{
    $sql = "SELECT count(*) FROM users_log";
    $res = $pdo->prepare($sql);
    $res->execute();
    return $res->fetchColumn();
}
