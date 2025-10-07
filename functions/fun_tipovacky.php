<?php
function tipovacky_vypis_all ($pdo, $lang, $typ): void
{
    $dnes = date('Y-m-d', time());
    if ($typ == 1):
        $sql = 'SELECT * FROM zdef_tipovacka WHERE valid = 1 AND aktivni = 1 AND datum_od > :dnes ORDER BY datum_od, id';
        $res = $pdo->prepare($sql);
        $res->execute(['dnes'=>$dnes]);
        $heading = '<div class="text-center"><h5 class="section-title ff-secondary text-center text-dark fw-normal">Nadcházející tipovačky</h5></div>';
    elseif ($typ == 2):
        $sql = 'SELECT * FROM zdef_tipovacka WHERE valid = 1 AND aktivni = 1 AND datum_od <= :dnes1 AND datum_do >= :dnes2 ORDER BY datum_od, id';
        $res = $pdo->prepare($sql);
        $res->execute(['dnes1'=>$dnes, 'dnes2'=>$dnes]);
        $heading = '<div class="text-center"><h5 class="section-title ff-secondary text-center text-dark fw-normal">Probíhající tipovačky</h5></div>';
    else:
        $sql = 'SELECT * FROM zdef_tipovacka WHERE valid = 1 AND aktivni = 1 AND datum_do < :dnes ORDER BY datum_od, id';
        $res = $pdo->prepare($sql);
        $res->execute(['dnes'=>$dnes]);
        $heading = '<div class="text-center"><h5 class="section-title ff-secondary text-center text-dark fw-normal">Uplynulé tipovačky</h5></div>';
    endif;
    $rows = $res->rowcount();
    $stmt = $res->fetchAll();

    if ($rows <> 0):
        echo $heading;
        echo '<div class="row text-center m-2 g-3" id="po">';
        foreach ($stmt as $dev)
            {
                $unique = $dev['url'];
                $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/cz/index/tipovacky/$unique";
            echo '
                <div class="col-lg-3 col-md-6 col-sm-12 wow fadeIn" data-wow-delay="0.1s">
                    <div class="tipovacky-item shadow" id="headingOne">
                        <a href="'.$url.'" class="tipovacky-link text-dark" title="'.$dev['nazev_cz'].'">
                            <div class="tipovacky-hover">
                                <div class="tipovacky-hover-content">
                                    <i class="bi bi-plus-square-fill"  style="font-size: 3rem;"></i>
                                </div>
                            </div>
                            <img src="/files/images/tipovacka/'.$dev['image'].'" class="img-fluid" alt="'.$dev['nazev_cz'].'">
                            <div class="tipovacky-caption">
                                <div class="tipovacky-caption-heading">'.$dev['nazev_cz'].'</div>
                                <div class="tipovacky-caption-subheading">'.$dev['popis_cz'].'</div>
                                <div class="tipovacky-caption-subheading">Tipujte od: '.format_date_www($dev['datum_od']).' - '.format_date_www($dev['datum_do']).'</div>
                            </div>
                        </a>
                    </div>
                </div>
            ';}
        echo '</div>';
    endif;
}

function tipovacka_all ($pdo, $tipid) :array
{
    $sql = 'SELECT * FROM zdef_tipovacka WHERE url = :tipid AND valid = 1';
    $res = $pdo->prepare($sql);
    $res->execute(['tipid'=>$tipid]);
    return $res->fetch();
}

function tipovacka_id ($pdo, $tipid) :string
{
    $sql = 'SELECT id FROM zdef_tipovacka WHERE url = :tipid AND valid = 1';
    $res = $pdo->prepare($sql);
    $res->execute(['tipid'=>$tipid]);
    $dev = $res->fetch();
    return $dev['id'];
}

function tipovacka_user_logged ($pdo, $tipovacka_id, $user_id)
{
    $sql = 'SELECT count(*) FROM zdef_tipovacka_users_rel WHERE tipovacka_id = :tipovacka_id AND user_id = :user_id AND registered = 1 AND valid = 1';
    $res = $pdo->prepare($sql);
    $res->execute(['tipovacka_id'=>$tipovacka_id, 'user_id'=>$user_id]);
    return $res->fetchColumn();
}

function tipovacka_user_register ($pdo, $tipovacka_id, $user_id): void
{ //ulozit usera do tabulky rel pokud neexistuje, vygenerovat tabulku poradi, pokud neexistuje
    $qn_user = $_SESSION['qusr_user'];
    $sql1 = 'SELECT count(*) FROM zdef_tipovacka_users_rel WHERE tipovacka_id = :tipovacka_id AND user_id = :user_id AND valid = 1';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['tipovacka_id'=>$tipovacka_id, 'user_id'=>$user_id]);
    $dev1 = $res1->fetchColumn();

    if ($dev1 == 0):
        try {
        $sql2 = 'INSERT INTO zdef_tipovacka_users_rel (tipovacka_id, user_id, user_i, user_u ) VALUES (:tipovacka_id, :user_id, :qn_user_i, :qn_user_u)';
        $res2 = $pdo->prepare($sql2);
        $res2->execute(['tipovacka_id'=>$tipovacka_id, 'user_id'=>$user_id, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
            $url_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
            $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$url_parts[0]";
            echo "<script type='text/javascript'>document.location.href='$url';</script>";
            echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
        }
        catch (PDOException $e){
            $error = 'Data not inserted: '. $e->getMessage();
            echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Nebyl jsi registrován, kontaktuj nás.</span></a>';
            echo $error;
        }
    else:
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Nebyl jsi registrován, kontaktuj nás.</span></a>';
    endif;
}

function tipovacka_teams_user_insert ($pdo, $tipovacka_id, $user_id)
{
    $qn_user = $_SESSION['qusr_user'];
    $sql1 = 'SELECT sum(id) FROM zdef_tipovacka_teams WHERE valid = 1 AND tipovacka_id = :tipovacka_id';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['tipovacka_id'=>$tipovacka_id]);
    $dev1 = $res1->fetchColumn() ?? 0;

    $sql2 = 'SELECT sum(team_id) FROM zdef_tipovacka_tips_poradi WHERE valid = 1 AND tipovacka_id = :tipovacka_id AND user_id = :user_id';
    $res2 = $pdo->prepare($sql2);
    $res2->execute(['tipovacka_id'=>$tipovacka_id, 'user_id'=>$user_id]);
    $dev2 = $res2->fetchColumn() ?? 0;

    if ($dev1 <> $dev2):
        $sql3 = 'SELECT * FROM zdef_tipovacka_teams WHERE valid = 1 AND tipovacka_id = :tipovacka_id';
        $res3 = $pdo->prepare($sql3);
        $res3->execute(['tipovacka_id'=>$tipovacka_id]);
        $stmt = $res3->fetchAll();
        foreach ($stmt as $dev3)
        {
            $sql4 = 'SELECT count(*) FROM zdef_tipovacka_tips_poradi WHERE tipovacka_id = :tipovacka_id AND user_id = :user_id AND team_id = :team_id AND valid = 1';
            $res4 = $pdo->prepare($sql4);
            $res4->execute(['tipovacka_id'=>$tipovacka_id, 'user_id'=>$user_id, 'team_id'=>$dev3['id']]);
            $dev4 = $res4->fetchColumn() ?? 0;

            if ($dev4 == 0):
                $sql5 = 'INSERT INTO zdef_tipovacka_tips_poradi (tipovacka_id, user_id, team_id, user_i, user_u) VALUES (:tipovacka_id, :user_id, :team_id, :qn_user_i, :qn_user_u)';
                $res5 = $pdo->prepare($sql5);
                $res5->execute(['tipovacka_id'=>$tipovacka_id, 'user_id'=>$user_id, 'team_id'=>$dev3['id'], 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
            endif;

        }
    endif;
}

function tipovacka_teams_form_vypis ($pdo, $tipovacka_id, $user_id, $datum_do_poradi): void
{
    $sql1 = 'SELECT ztt.id as id, ztt.nazev_cz as nazev_cz, ztt.image as image, ztt.poradi_final as poradi_final                
            FROM zdef_tipovacka_teams ztt 
            LEFT JOIN zdef_tipovacka_tips_poradi zttp on ztt.id = zttp.team_id
            WHERE zttp.user_id = :user_id AND ztt.tipovacka_id = :tipovacka_id AND ztt.valid = 1 ORDER BY ztt.poradi_final, zttp.poradi, ztt.poradi, ztt.nazev_cz';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['tipovacka_id'=>$tipovacka_id, 'user_id'=>$user_id]);
    $stmt = $res1->fetchAll();
    $dnes = date('Y-m-d', time());

    echo '<form method="post" class="form-inline justify-content-center formPoradi">';
    foreach ($stmt as $dev1) //poradi trefa 10b, o jednu pozici 5b, o dvě pozice 3b
    {
        $sql2 = 'SELECT id, poradi, body FROM zdef_tipovacka_tips_poradi WHERE valid = 1 AND tipovacka_id = :tipovacka_id AND team_id = :team_id AND user_id = :user_id';
        $res2 = $pdo->prepare($sql2);
        $res2->execute(['tipovacka_id'=>$tipovacka_id, 'team_id'=>$dev1['id'], 'user_id'=>$user_id]);
        $dev2 = $res2->fetch();
        $rows2 = $res2->rowcount();
        if ($rows2==0): $dev2['id'] = 0; $dev2['poradi'] = 0; $dev2['body'] = 0; endif;
        if ($dev1['poradi_final']==0): $poradi_final = ''; else: $poradi_final = $dev1['poradi_final'].' / '.$dev2['body'].'b'; endif;
        if (isset($_POST['add'])): $dev2['poradi']=$_POST['poradi'][$dev2['id']]; endif;

        if ($dnes<=$datum_do_poradi):
            echo '
                <div class="row bg-light mx-3 mt-2 rounded-3">
                    <div class="col-lg"><img src="/files/images/teams/small/'.$dev1['image'].'" alt="'.$dev1['nazev_cz'].'" /> </div>
                    <div class="col-lg-4 d-flex align-items-center"><span class="fs-4 fw-bolder">'.$dev1['nazev_cz'].'</span></div>
                    <div class="col-lg-2">
                        <input type="text" name="poradi_final" id="poradi_final" disabled class="form-control" placeholder="finálně" value="'.$poradi_final.'" />
                    </div>
                    <div class="col-lg-2">
                        <input type="number" onClick="this.select();" name="poradi['.$dev2['id'].']" id="poradi" class="form-control" min="0" max="99" placeholder="umístění" value="'.$dev2['poradi'].'" />
                        <input type="hidden" name="tip['.$dev2['id'].']" value="'.$dev2['id'].'" />
                    </div>
                    <div class="col-lg-2">
                        <input type="submit" id="cmd" value="uložit" class="" />
                    </div>
                </div>
                ';
        else:
            echo '
                    <div class="row bg-light mx-3 mt-2 rounded-3">
                        <div class="col-lg"><img src="/files/images/teams/small/'.$dev1['image'].'" alt="'.$dev1['nazev_cz'].'" /> </div>
                        <div class="col-lg-4 d-flex align-items-center"><span class="fs-4 fw-bolder">'.$dev1['nazev_cz'].'</span></div>
                        <div class="col-lg-2">
                            <input type="text" name="poradi_final" id="poradi_final" disabled class="form-control" placeholder="finálně" value="'.$poradi_final.'" /></div>
                        <div class="col-lg-2">
                            <input type="text" name="poradi" id="poradi" class="form-control" disabled placeholder="umístění" value="'.$dev2['poradi'].'" /></div>
                            <input type="hidden" name="form_id" value="'.$dev2['id'].'" />
                            <input type="hidden" name="team_id" value="'.$dev1['id'].'" />
                        <div class="col-lg-2">
                            <input type="submit" value="již nelze" class="" disabled /></div>
                    </div>
                ';
        endif;
    }
    echo '<input type="hidden" name="add" value="1" />
            </form>';
}

function tipovacka_zapasy_form_vypis ($pdo, $tipovacka_id, $user_id, $remizy): void
{
    date_default_timezone_set('Europe/Prague');
    $ted = date('Y-m-d H:i:s', time());
    $sql1 = 'SELECT ztz.id as id, ztz.skupina as skupina, ztz.team1_id as team1_id, ztz.team2_id as team2_id, ztz.datetime as datetime, ztz.datetime_end as datetime_end,
                ztt1.nazev_cz as team1, ztt2.nazev_cz as team2, ztt1.image as image1, ztt2.image as image2
            FROM zdef_tipovacka_zapasy ztz 
            LEFT JOIN zdef_tipovacka_teams ztt1 on ztz.team1_id = ztt1.id  
            LEFT JOIN zdef_tipovacka_teams ztt2 on ztz.team2_id = ztt2.id
            WHERE ztz.tipovacka_id = :tipovacka_id AND ztz.valid = 1 AND ztz.datetime >= :ted ORDER BY ztz.datetime, ztz.poradi';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['tipovacka_id'=>$tipovacka_id, 'ted'=>$ted]);
    $stmt = $res1->fetchAll();

    foreach ($stmt as $dev1)
    {
        $sql2 = 'SELECT id, team1_goals, team2_goals FROM zdef_tipovacka_tips_zapasy WHERE valid = 1 AND zapas_id = :zapas_id AND user_id = :user_id';
        $res2 = $pdo->prepare($sql2);
        $res2->execute(['zapas_id'=>$dev1['id'], 'user_id'=>$user_id]);
        $dev2 = $res2->fetch();
        $rows2 = $res2->rowcount();
        if ($rows2==0): $color = 'primary'; $dev2['id'] = 0; $dev2['team1_goals'] = ''; $dev2['team2_goals'] = ''; else: $color = 'danger';  endif;
        if ($dev2['team1_goals']==$dev2['team2_goals'] AND $remizy==0 AND $rows2<>0):
            $remizy = '<span class="text-danger">remíza bude chyba</span>';
        else:
            $remizy = '';
        endif;

        if ($ted<=$dev1['datetime_end']):
            echo '
                <div class="col tip_zapas" id="zapas'.$dev1['id'].'">
                    <div class="card text-bg-light my-3 mx-1">
                        <div class="card-header bg-'.$color.' text-light fw-bolder">'.$dev1['skupina'].' '.format_datetimemin_www($dev1['datetime']).'</div>
                        <div class="card-body">
                            <span class="card-title p-0">'.$dev1['team1'].'</span>
                            <div class="row align-items-center">
                                <div class="col p-0"><img src="/files/images/teams/small/'.$dev1['image1'].'" alt="'.$dev1['team1'].'"></div>
                                <div class="col"><span class="d-block fs-4">&nbsp:&nbsp</span></div>
                                <div class="col p-0"><img src="/files/images/teams/small/'.$dev1['image2'].'" alt="'.$dev1['team2'].'"></div>
                            </div>
                            <span class="card-title">'.$dev1['team2'].'</span>
                        </div>
                        <div class="card-footer text-center">
                            <div>konec tipu: '.format_datetimemin_www($dev1['datetime_end']).' '.$remizy.'</div>
                            <form method="post" class="form-inline">
                                <input type="number" class="form-control" onClick="this.select();" name="team1_goals" id="team1_goals" min="0" max="99" value="'.$dev2['team1_goals'].'" placeholder="Dom." /> :
                                <input type="number" class="form-control" onClick="this.select();" name="team2_goals" id="team2_goals" min="0" max="99" value="'.$dev2['team2_goals'].'" placeholder="Hos." />
                                <input type="hidden" name="form_id" value="'.$dev2['id'].'" />
                                <input type="hidden" name="zapas_id" value="'.$dev1['id'].'" />
                                <input type="submit" class="btn btn-primary" value="Uložit" />
                            </form>
                        </div>
                    </div>
                </div>';
        else:
            echo '<div class="col tip_zapas" id="zapas'.$dev1['id'].'">
                    <div class="card text-bg-light my-3 mx-1">
                        <div class="card-header bg-'.$color.' text-light fw-bolder">'.$dev1['skupina'].' '.format_datetimemin_www($dev1['datetime']).'</div>
                        <div class="card-body">
                            <span class="card-title p-0">'.$dev1['team1'].'</span>
                            <div class="row align-items-center">
                                <div class="col p-0"><img src="/files/images/teams/small/'.$dev1['image1'].'" alt="'.$dev1['team1'].'"></div>
                                <div class="col"><span class="d-block fs-4">&nbsp:&nbsp</span></div>
                                <div class="col p-0"><img src="/files/images/teams/small/'.$dev1['image2'].'" alt="'.$dev1['team2'].'"></div>
                            </div>
                            <span class="card-title">'.$dev1['team2'].'</span>
                        </div>
                        <div class="card-footer text-center">
                            <div>konec tipu: '.format_datetimemin_www($dev1['datetime_end']).' '.$remizy.'</div>
                            <form method="post" class="form-inline">
                                <input type="number" class="form-control" onClick="this.select();" name="team1_goals" id="team1_goals" min="0" max="99" disabled value="'.$dev2['team1_goals'].'" placeholder="Dom." /> :
                                <input type="number" class="form-control" onClick="this.select();" name="team2_goals" id="team2_goals" min="0" max="99" disabled value="'.$dev2['team2_goals'].'" placeholder="Hos." />
                                <input type="hidden" name="form_id" value="'.$dev2['id'].'" />
                                <input type="hidden" name="zapas_id" value="'.$dev1['id'].'" />
                                <input type="submit" class="btn btn-primary" value="Nelze" disabled />
                            </form>
                        </div>
                    </div>
                </div>';
        endif;
    }
}

function tipovacka_teams_form_insert ($pdo, $tipovacka_id, $user_id): void
{
    $qn_user = $_SESSION['qusr_user'];
    $sql1 = 'SELECT * FROM zdef_tipovacka_tips_poradi WHERE tipovacka_id = :tipovacka_id AND user_id = :user_id AND valid = 1';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['tipovacka_id'=>$tipovacka_id, 'user_id'=>$user_id]);

    $unique = 0;
    $dups = array();
    foreach(array_count_values($_POST['poradi']) as $val => $c)
        if($c > 1) $dups[] = $val;
    foreach ($dups as $value){
        $to = $value;
        if($to <> 0): $unique = 1; endif;}

    if ($unique == 0):
        $stmt = $res1->fetchAll();
        foreach ($stmt as $dev1) {
            $sql2 = 'UPDATE zdef_tipovacka_tips_poradi SET poradi = :poradi, user_u = :qn_user_u WHERE id = :id';
            $res2 = $pdo->prepare($sql2);
            $res2->execute(['poradi' => $_POST['poradi'][$dev1['id']], 'id' => $_POST['tip'][$dev1['id']], 'qn_user_u' => $qn_user]);
        }
        $url_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$url_parts[0]";
        echo "<script type='text/javascript'>document.location.href='$url';</script>";
    else:
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Některé zapsané pořadí je duplicitní.</span></a>';
    endif;
}

function tipovacka_zapasy_form_insert ($pdo, $id, $tipovacka_id, $zapas_id, $user_id, $team1_goals, $team2_goals): void
{
    $qn_user = $_SESSION['qusr_user'];
    date_default_timezone_set('Europe/Prague');
    $ted = date('Y-m-d H:m:i', time());
    $sql1 = 'SELECT datetime_end FROM zdef_tipovacka_zapasy WHERE id = :zapas_id AND valid = 1';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['zapas_id'=>$zapas_id]);
    $dev1 = $res1->fetch();

    if ($team1_goals > $team2_goals): $tip = 1; elseif ($team1_goals == $team2_goals): $tip = 0; else: $tip = 2; endif;

    if ($id == 0):
        $sql3 = 'SELECT id FROM zdef_tipovacka_tips_zapasy WHERE valid = 1 AND tipovacka_id = :tipovacka_id AND zapas_id = :zapas_id AND user_id = :user_id';
        $res3 = $pdo->prepare($sql3);
        $res3->execute(['tipovacka_id'=>$tipovacka_id, 'zapas_id'=>$zapas_id, 'user_id'=>$user_id]);
        $dev3 = $res3->fetch();
        $id = $dev3['id'] ?? 0;
    endif;

    if ($ted<=$dev1['datetime_end']):
        try {
            if($id==0):
                $sql2 = 'INSERT INTO zdef_tipovacka_tips_zapasy (tipovacka_id, user_id, zapas_id, team1_goals, team2_goals, tip, user_i, user_u) 
                            VALUES (:tipovacka_id, :user_id, :zapas_id, :team1_goals, :team2_goals, :tip, :qn_user_i, :qn_user_u)';
                $res2 = $pdo->prepare($sql2);
                $res2->execute(['tipovacka_id'=>$tipovacka_id, 'user_id'=>$user_id, 'zapas_id'=>$zapas_id, 'team1_goals'=>$team1_goals, 'team2_goals'=>$team2_goals, 'tip'=>$tip, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
            else:
                $sql2 = 'UPDATE zdef_tipovacka_tips_zapasy SET team1_goals = :team1_goals, team2_goals = :team2_goals, tip = :tip, user_u = :qn_user_u WHERE id = :id';
                $res2 = $pdo->prepare($sql2);
                $res2->execute(['team1_goals'=>$team1_goals, 'team2_goals'=>$team2_goals, 'tip'=>$tip, 'qn_user_u'=>$qn_user, 'id'=>$id]);
            endif;
            $url_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
            $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$url_parts[0]#zapas$zapas_id";
            echo "<script type='text/javascript'>document.location.href='$url';</script>";
            //echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
        }
        catch (PDOException $e){
            $error = 'Data not inserted: '. $e->getMessage();
            echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Tip nebyl uložen</span></a>';
            echo $error;}
    else:
        echo '<a href="#" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Již skončila možnost tipování.</span></a>';
    endif;
}

function tipovacka_users_rel_vypis ($pdo, $tipovacka_id): void
{
    //select, ktery zjisti uzivatele a body v ramci tipovacky
    $sql1 = "SELECT ztur.body_zapasy as body_zapasy, ztur.body_poradi as body_poradi, ztur.body_otazky, ztur.body_celkem as body_celkem, ztu.login as login, ztu.id as user_id,
            zt.nazev_cz as tipovacka, zt.tip_zapasy as tip_zapasy, zt.tip_poradi as tip_poradi, zt.tip_otazky as tip_otazky, zt.datum_do_poradi as datum_do_poradi
            FROM zdef_tipovacka_users_rel ztur 
                INNER JOIN zdef_tipovacka_users ztu on ztur.user_id = ztu.id 
                INNER JOIN zdef_tipovacka zt on ztur.tipovacka_id = zt.id
            WHERE ztur.valid = 1 AND ztur.tipovacka_id = :tipovacka_id
            ORDER BY ztur.body_celkem DESC, ztur.body_zapasy DESC, ztur.body_otazky DESC, ztur.body_poradi DESC, ztu.login";
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['tipovacka_id'=>$tipovacka_id]);
    $poradi = 1;

    date_default_timezone_set('Europe/Prague');
    $ted = date('Y-m-d', time());

    $stmt1 = $res1->fetchAll();
    foreach ($stmt1 as $dev1) {
        if ($dev1['datum_do_poradi'] < $ted):
            $tipy = '<a class="btn btn-sm btn-primary shadow" href="?usr_id=' . $dev1['user_id'] . '">Tipy</a>';
        else:
            $tipy = 'až po ' . format_date_www($dev1['datum_do_poradi']);
        endif;
        //select zjisteni koeficientů zapasů (je to kvůli zjisteni poctu spravných tipů, ale problem je s koeficientem)
        $sql2 = 'SELECT DISTINCT koeficient FROM zdef_tipovacka_tips_zapasy WHERE valid = 1';
        $res2 = $pdo->prepare($sql2);
        $res2->execute();
        $stmt2 = $res2->fetchAll();
        $tip3 = $tip2 = $tip1 = 0;
        foreach ($stmt2 as $dev2) {
            //select zjisteni poctu presných tipů
            $body3 = 3 * $dev2['koeficient'];
            $sql3 = 'SELECT COUNT(*) FROM zdef_tipovacka_tips_zapasy WHERE valid = 1 AND user_id = :user_id AND tipovacka_id = :tipovacka_id AND koeficient = :koeficient AND body = :body';
            $res3 = $pdo->prepare($sql3);
            $res3->execute(['user_id' => $dev1['user_id'], 'tipovacka_id' => $tipovacka_id, 'koeficient' => $dev2['koeficient'], 'body' => $body3]);
            $dev3 = $res3->fetchColumn() ?? 0;
            $tip3 = $tip3 + $dev3;
            //select zjisteni poctu tipů rozdilů skore
            $body2 = 2 * $dev2['koeficient'];
            $sql4 = 'SELECT COUNT(*) FROM zdef_tipovacka_tips_zapasy WHERE valid = 1 AND user_id = :user_id AND tipovacka_id = :tipovacka_id AND koeficient = :koeficient AND body = :body';
            $res4 = $pdo->prepare($sql4);
            $res4->execute(['user_id' => $dev1['user_id'], 'tipovacka_id' => $tipovacka_id, 'koeficient' => $dev2['koeficient'], 'body' => $body2]);
            $dev4 = $res4->fetchColumn() ?? 0;
            $tip2 = $tip2 + $dev4;
            //select zjisteni poctu spravných tipů vítěze
            $body1 = 1 * $dev2['koeficient'];
            $sql5 = 'SELECT COUNT(*) FROM zdef_tipovacka_tips_zapasy WHERE valid = 1 AND user_id = :user_id AND tipovacka_id = :tipovacka_id AND koeficient = :koeficient AND body = :body';
            $res5 = $pdo->prepare($sql5);
            $res5->execute(['user_id' => $dev1['user_id'], 'tipovacka_id' => $tipovacka_id, 'koeficient' => $dev2['koeficient'], 'body' => $body1]);
            $dev5 = $res5->fetchColumn() ?? 0;
            $tip1 = $tip1 + $dev5;
        }
        //select zjisteni chybnych tipů
        $sql6 = 'SELECT COUNT(*) FROM zdef_tipovacka_tips_zapasy zttz
                    INNER JOIN zdef_tipovacka_zapasy ztz on zttz.zapas_id = ztz.id 
                    WHERE zttz.valid = 1 AND zttz.user_id = :user_id AND zttz.tipovacka_id = :tipovacka_id AND zttz.body = 0 AND ztz.tip<>99';
        $res6 = $pdo->prepare($sql6);
        $res6->execute(['user_id' => $dev1['user_id'], 'tipovacka_id' => $tipovacka_id]);
        $dev6 = $res6->fetchColumn() ?? 0;
        $tip0 = $dev6;

        echo '
        <tr class="align-middle">
            <!--<td>' . $dev1["tipovacka"] . '</td>-->
            <td>' . $dev1["login"] . '</td>';
        if ($dev1['tip_zapasy'] == 1): echo '
            <td>' . $dev1["body_zapasy"] . '</td>
            <td class="text-nowrap" ><span class="badge text-bg-success">' . $tip3 . '</span> | <span class="badge text-bg-primary">' . $tip2 . '</span> | <span class="badge text-bg-warning">' . $tip1 . '</span> | <span class="badge text-bg-danger">' . $tip0 . '</span></td>';
        endif;
        if ($dev1['tip_otazky'] == 1): echo '<td>' . $dev1["body_otazky"] . '</td>'; endif;
        if ($dev1['tip_poradi'] == 1): echo '<td>' . $dev1["body_poradi"] . '</td>'; endif;
        echo '<td>' . $dev1["body_celkem"] . '</td>';
        if ($dev1['tip_poradi'] == 1): echo '<td>' . $tipy . '</td>'; endif;
        echo '<td>' . $poradi . '</td>
        </tr>';
        $poradi = $poradi + 1;
    }

}

function tipovacka_zapasy_uplynule_vypis ($pdo, $tipovacka_id, $user_id): void
{
    date_default_timezone_set('Europe/Prague');
    $ted = date('Y-m-d H:m:i', time());
    $sql1 = 'SELECT ztz.id as id, ztz.skupina as skupina, ztz.team1_id as team1_id, ztz.team2_id as team2_id, ztz.datetime as datetime, ztz.datetime_end as datetime_end, 
                ztz.team1_goals as team1_goals, ztz.team2_goals as team2_goals, ztz.koeficient as koeficient,
                ztt1.nazev_cz as team1, ztt2.nazev_cz as team2, ztt1.image as image1, ztt2.image as image2
            FROM zdef_tipovacka_zapasy ztz 
            LEFT JOIN zdef_tipovacka_teams ztt1 on ztz.team1_id = ztt1.id  
            LEFT JOIN zdef_tipovacka_teams ztt2 on ztz.team2_id = ztt2.id
            WHERE ztz.tipovacka_id = :tipovacka_id AND ztz.valid = 1 AND ztz.datetime <= :ted ORDER BY ztz.datetime DESC, ztz.poradi DESC';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['tipovacka_id'=>$tipovacka_id, 'ted'=>$ted]);
    $stmt = $res1->fetchAll();

    foreach ($stmt as $dev1) //poradi trefa 10b, o jednu pozici 5b, o dvě pozice 3b
    {
        $sql2 = 'SELECT * FROM zdef_tipovacka_tips_zapasy WHERE valid = 1 AND zapas_id = :zapas_id AND user_id = :user_id';
        $res2 = $pdo->prepare($sql2);
        $res2->execute(['zapas_id'=>$dev1['id'], 'user_id'=>$user_id]);
        $dev2 = $res2->fetch();
        $rows2 = $res2->rowcount();
        $zapas = $dev1['team1'].' : '.$dev1['team2'];
        $vysledek = $dev1['team1_goals'].' : '.$dev1['team2_goals'];
        if ($rows2==0):
            $tip = ' : ';
            $body = 0;
        else:
            $tip = $dev2['team1_goals'].' : '.$dev2['team2_goals'];
            $body = $dev2['body'];
        endif;

        echo '
        <tr class="align-middle">
            <td>'.format_datetimemin_www($dev1["datetime"]).'</td>
            <td>'.$dev1['skupina'].'</td>
            <td class="text-nowrap">'.$zapas.'</td>
            <td>'.$vysledek.'</td>
            <td>'.$tip.'</td>
            <td>'.$dev1['koeficient'].'</td>
            <td>'.$body.'</td>
            <td><a class="btn btn-primary shadow" href="?upl_id='.$dev1['id'].'">Tipy</a></td>
        </tr>';

    }
}

function tipovacka_zapasy_uplynule_user_vypis ($pdo, $tipovacka_id, $user_id, $upl_id): void
{
    $sql1 = 'SELECT zttz.id as id, zttz.user_id as user_id, zttz.zapas_id as zapas_id, zttz.team1_goals as tip_team1_goals, zttz.team2_goals as tip_team2_goals, zttz.body as body, zttz.tip as tip,
                ztz.skupina as skupina, ztz.team1_goals as team1_goals, ztz.team2_goals as team2_goals, ztz.datetime as datetime, ztz.koeficient as koeficient, 
                ztt1.nazev_cz as team1, ztt2.nazev_cz as team2, ztu.login as login
                FROM zdef_tipovacka_tips_zapasy zttz
                INNER JOIN zdef_tipovacka_zapasy ztz on zttz.zapas_id = ztz.id
                LEFT JOIN zdef_tipovacka_teams ztt1 on ztz.team1_id = ztt1.id  
                LEFT JOIN zdef_tipovacka_teams ztt2 on ztz.team2_id = ztt2.id
                LEFT JOIN zdef_tipovacka_users ztu on zttz.user_id = ztu.id
             WHERE zttz.valid = 1 AND zttz.zapas_id = :upl_id ORDER BY zttz.body DESC, ztu.login';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['upl_id'=>$upl_id]);
    $stmt = $res1->fetchAll();

    foreach ($stmt as $dev1) //p
    {

        $zapas = $dev1['team1'].' : '.$dev1['team2'];
        $vysledek = $dev1['team1_goals'].' : '.$dev1['team2_goals'];
        $tip = $dev1['tip_team1_goals'].' : '.$dev1['tip_team2_goals'];

        echo '
        <tr class="align-middle">
            <td>'.format_datetimemin_www($dev1["datetime"]).'</td>
            <td>'.$dev1['skupina'].'</td>
            <td>'.$dev1['login'].'</td>
            <td>'.$zapas.'</td>
            <td>'.$vysledek.'</td>
            <td>'.$tip.'</td>
            <td>'.$dev1['body'].'</td>
        </tr>';

    }
}

function tipovacka_zapasy_uplynule_stat ($pdo, $upl_id): void
{
    $sql1 = 'SELECT koeficient FROM zdef_tipovacka_zapasy WHERE valid = 1 AND id = :zapas_id';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['zapas_id'=>$upl_id]);
    $dev1 = $res1->fetch();

    $sql2 = 'SELECT count(*) FROM zdef_tipovacka_tips_zapasy WHERE valid = 1 AND zapas_id = :zapas_id';
    $res2 = $pdo->prepare($sql2);
    $res2->execute(['zapas_id'=>$upl_id]);
    $dev2 = $res2->fetchColumn() ?? 0;

    $tip3 = $dev1['koeficient']*3;
    $tip2 = $dev1['koeficient']*2;
    $tip1 = $dev1['koeficient']*1;

    $sql3 = 'SELECT count(*) FROM zdef_tipovacka_tips_zapasy WHERE valid = 1 AND zapas_id = :zapas_id AND body = :tip3';
    $res3 = $pdo->prepare($sql3);
    $res3->execute(['zapas_id'=>$upl_id, 'tip3'=>$tip3]);
    $dev3 = $res3->fetchColumn() ?? 0;

    $sql4 = 'SELECT count(*) FROM zdef_tipovacka_tips_zapasy WHERE valid = 1 AND zapas_id = :zapas_id AND body = :tip2';
    $res4 = $pdo->prepare($sql4);
    $res4->execute(['zapas_id'=>$upl_id, 'tip2'=>$tip2]);
    $dev4 = $res4->fetchColumn() ?? 0;

    $sql5 = 'SELECT count(*) FROM zdef_tipovacka_tips_zapasy WHERE valid = 1 AND zapas_id = :zapas_id AND body = :tip1';
    $res5 = $pdo->prepare($sql5);
    $res5->execute(['zapas_id'=>$upl_id, 'tip1'=>$tip1]);
    $dev5 = $res5->fetchColumn() ?? 0;

    $sql6 = 'SELECT count(*) FROM zdef_tipovacka_tips_zapasy WHERE valid = 1 AND zapas_id = :zapas_id AND body = 0';
    $res6 = $pdo->prepare($sql6);
    $res6->execute(['zapas_id'=>$upl_id]);
    $dev6 = $res6->fetchColumn() ?? 0;

    $tip3_proc = round($dev3/($dev2/100));
    $tip2_proc = round($dev4/($dev2/100));
    $tip1_proc = round($dev5/($dev2/100));
    $tip0_proc = round($dev6/($dev2/100));

echo '
      <div class="col-lg-2">
        <div class="text-dark fw-bolder mb-1">Počet tipů</div>
        <div class="row no-gutters align-items-center">
            <div class="col-auto">
                <div class="h5 mb-0 text-gray-800">'.$dev2.'</div>
            </div>
            <div class="col">
                <div class="progress progress-sm me-2">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
      </div>
      <div class="col-lg-2">
        <div class="text-dark fw-bolder mb-1">Přesné tipy</div>
        <div class="row no-gutters align-items-center">
            <div class="col-auto">
                <div class="h5 mb-0 text-gray-800">'.$tip3_proc.'%</div>
            </div>
            <div class="col">
                <div class="progress progress-sm me-2">
                    <div class="progress-bar bg-success" role="progressbar" style="width: '.$tip3_proc.'%" aria-valuenow="'.$tip3_proc.'" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
      </div>
      <div class="col-lg-2">
        <div class="text-dark fw-bolder mb-1">Rozdílné skóre</div>
        <div class="row no-gutters align-items-center">
            <div class="col-auto">
                <div class="h5 mb-0 text-gray-800">'.$tip2_proc.'%</div>
            </div>
            <div class="col">
                <div class="progress progress-sm me-2">
                    <div class="progress-bar bg-success" role="progressbar" style="width: '.$tip2_proc.'%" aria-valuenow="'.$tip2_proc.'" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
      </div>
      <div class="col-lg-2">
        <div class="text-dark fw-bolder mb-1">Trefený vítěz</div>
        <div class="row no-gutters align-items-center">
            <div class="col-auto">
                <div class="h5 mb-0 text-gray-800">'.$tip1_proc.'%</div>
            </div>
            <div class="col">
                <div class="progress progress-sm me-2">
                    <div class="progress-bar bg-success" role="progressbar" style="width: '.$tip1_proc.'%" aria-valuenow="'.$tip1_proc.'" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
      </div>
      <div class="col-lg-2">
        <div class="text-dark fw-bolder mb-1">Chyb</div>
        <div class="row no-gutters align-items-center">
            <div class="col-auto">
                <div class="h5 mb-0 text-gray-800">'.$tip0_proc.'%</div>
            </div>
            <div class="col">
                <div class="progress progress-sm me-2">
                    <div class="progress-bar bg-success" role="progressbar" style="width: '.$tip0_proc.'%" aria-valuenow="'.$tip0_proc.'" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
      </div>

';
}

function tipovacka_poradi_user_vypis ($pdo, $tipovacka_id, $user_id): void
{

    $sql1 = 'SELECT zttp.poradi as tip, zttp.body as body, ztt.nazev_cz as nazev, ztt.poradi_final as poradi_final, ztu.login as login
                FROM zdef_tipovacka_tips_poradi zttp
                LEFT JOIN zdef_tipovacka_users ztu on zttp.user_id = ztu.id    
                LEFT JOIN zdef_tipovacka_teams ztt on zttp.team_id = ztt.id  
             WHERE zttp.valid = 1 AND zttp.user_id = :user_id AND zttp.tipovacka_id = :tipovacka_id ORDER BY zttp.poradi, ztt.poradi';
    $res1 = $pdo->prepare($sql1);
    $res1->execute(['user_id'=>$user_id, 'tipovacka_id'=>$tipovacka_id]);
    $stmt = $res1->fetchAll();

    foreach ($stmt as $dev1) //p
    {

        echo '
        <tr class="align-middle">
            <td>'.$dev1['login'].'</td>
            <td>'.$dev1['nazev'].'</td>
            <td>'.$dev1['poradi_final'].'</td>
            <td>'.$dev1['tip'].'</td>
            <td>'.$dev1['body'].'</td>
        </tr>';

    }
}