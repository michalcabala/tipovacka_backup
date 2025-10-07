<?php
function news_item_vypis_all ($pdo, $lang, $news_typ, $paginationStart, $limit)
{
    if ($news_typ == 0):
        if ($lang == 'en'):
            $sql = 'SELECT n.id as id, n.url_en as url, n.datum as datum, n.nazev_en as nazev, n.perex_en as perex, nt.nazev_en as typ, nt.color as color, nt.page_en as page
                    FROM news n LEFT OUTER JOIN news_typ nt on n.news_typ = nt.id 
                    WHERE n.valid = 1 AND (n.visible = 1 OR n.visible = 3) ORDER BY n.datum DESC LIMIT :paginationStart, :limit';
        else:
            $sql = 'SELECT n.id as id, n.url_cz as url, n.datum as datum, n.nazev_cz as nazev, n.perex_cz as perex, nt.nazev_cz as typ, nt.color as color, nt.page_cz as page
                    FROM news n LEFT OUTER JOIN news_typ nt on n.news_typ = nt.id 
                    WHERE n.valid = 1 AND (n.visible = 1 OR n.visible = 2) ORDER BY n.datum DESC LIMIT :paginationStart, :limit';
        endif;
        $res = $pdo->prepare($sql);
        $res->execute(['paginationStart'=>$paginationStart, 'limit'=>$limit]);
    else:
        if ($lang == 'en'):
            $sql = 'SELECT n.id as id, n.url_en as url, n.datum as datum, n.nazev_en as nazev, n.perex_en as perex, nt.nazev_en as typ, nt.color as color, nt.page_en as page
                    FROM news n LEFT OUTER JOIN news_typ nt on n.news_typ = nt.id 
                    WHERE n.valid = 1 AND (n.visible = 1 OR n.visible = 3) AND news_typ = :news_typ ORDER BY n.datum DESC LIMIT :paginationStart, :limit';
        else:
            $sql = 'SELECT n.id as id, n.url_cz as url, n.datum as datum, n.nazev_cz as nazev, n.perex_cz as perex, nt.nazev_cz as typ, nt.color as color, nt.page_cz as page
                    FROM news n LEFT OUTER JOIN news_typ nt on n.news_typ = nt.id 
                    WHERE n.valid = 1 AND (n.visible = 1 OR n.visible = 2) AND news_typ = :news_typ ORDER BY n.datum DESC LIMIT :paginationStart, :limit';
        endif;
        $res = $pdo->prepare($sql);
        $res->execute(['paginationStart'=>$paginationStart, 'limit'=>$limit, 'news_typ'=>$news_typ]);
    endif;
    $stmt = $res->fetchAll();

    $number = 0;

    foreach ($stmt as $dev)
    {
        echo '
        <a href="/'.$lang.'/index/news/'.$dev['page'].'/'.$dev['url'].'" class="col-lg-3 col-md-6 text-decoration-none bg-white border rounded text-dark m-2 p-0" title="'.$dev['nazev'].'">
                <div class="news-item p-2 pt-2 mb-2">
                    <i class="bi bi-newspaper pb-2 text-'.$dev['color'].' mb-1">&nbsp;<span class="fs-6">'.$dev['typ'].'</span></i>
                    <p>'.$dev['perex'].'</p>
                    <div class="d-flex align-items-center">
                        <div class="">
                            <h5 class="mb-1">'.$dev['nazev'].'</h5>
                            <small class="text-'.$dev['color'].'">'.format_date_www($dev['datum']).'</small>
                        </div>
                    </div>
                </div>
            </a>';
        $number = $number+1;
    }
    if ($number == 0):
        echo '<div class="container p-0"><div class="card-body p-2 fs-5 stattext text-center"><p>'.sv($pdo, $lang, 2021).'</p></div></div>';
    endif;
}

function news_view ($pdo, $lang, $news)
{
    if ($lang == 'cz'):
        $sql = "SELECT * FROM news WHERE url_cz = :url_cz AND valid = 1";
        $res = $pdo->prepare($sql);
        $res->execute(['url_cz' => $news]);
        $dev = $res->fetch();
        echo stripslashes($dev['text_cz']);
    else:
        $sql = "SELECT * FROM news WHERE url_en = :url_en AND valid = 1";
        $res = $pdo->prepare($sql);
        $res->execute(['url_en' => $news]);
        $dev = $res->fetch();
        echo stripslashes($dev['text_en']);
    endif;

    if ($dev['galerie_id']<>0):
        echo '<div class="container p-0 news_view">';
        galerie_view($pdo, $lang, $dev['galerie_id']);
        echo '</div>';
    endif;
}

function news_view_id ($pdo, $lang, $news)
{
    if ($lang == 'cz'):
        $sql = "SELECT * FROM news WHERE id = :id AND valid = 1";
        $res = $pdo->prepare($sql);
        $res->execute(['id' => $news]);
        $dev = $res->fetch();
        echo stripslashes($dev['text_cz']);
    else:
        $sql = "SELECT * FROM news WHERE url_en = :url_en AND valid = 1";
        $res = $pdo->prepare($sql);
        $res->execute(['id' => $news]);
        $dev = $res->fetch();
        echo stripslashes($dev['text_en']);
    endif;

    if ($dev['galerie_id']<>0):
        echo '<div class="container p-0">';
        galerie_view($pdo, $lang, $dev['galerie_id']);
        echo '</div>';
    endif;

}

function news_view_name ($pdo, $lang, $news)
{
    if ($lang == 'cz'):
        $sql = "SELECT nazev_cz FROM news WHERE url_cz = :url_cz AND valid = 1";
        $res = $pdo->prepare($sql);
        $res->execute(['url_cz' => $news]);
        $dev = $res->fetch();
        return $dev['nazev_cz'];
    else:
        $sql = "SELECT nazev_en FROM news WHERE url_en = :url_en AND valid = 1";
        $res = $pdo->prepare($sql);
        $res->execute(['url_en' => $news]);
        $dev = $res->fetch();
        return $dev['nazev_en'];
    endif;
}

function news_view_datum ($pdo, $lang, $news)
{
    if ($lang == 'cz'):
        $sql = "SELECT datum FROM news WHERE url_cz = :url_cz AND valid = 1";
        $res = $pdo->prepare($sql);
        $res->execute(['url_cz' => $news]);
        $dev = $res->fetch();
        return format_date_www($dev['datum']);
    else:
        $sql = "SELECT datum FROM news WHERE url_en = :url_en AND valid = 1";
        $res = $pdo->prepare($sql);
        $res->execute(['url_en' => $news]);
        $dev = $res->fetch();
        return format_date_www($dev['datum']);
    endif;
}

function news_typ_id ($pdo, $category)
{
    $sql = "SELECT id FROM news_typ WHERE page_cz = :cat_cz OR page_en = :cat_en";
    $res = $pdo->prepare($sql);
    $res->execute(['cat_cz' => $category, 'cat_en' => $category]);
    $dev = $res->fetch();
    return $dev['id'];
}

function news_count ($pdo, $lang, $news_typ)
{
    if ($news_typ == 0):
        if ($lang == 'en'):
            $sql = "SELECT count(*) FROM news WHERE valid = 1 AND (visible = 1 OR visible = 3)";
        else:
            $sql = "SELECT count(*) FROM news WHERE valid = 1 AND (visible = 1 OR visible = 2)";
        endif;
        $res = $pdo->prepare($sql);
        $res->execute();
    else:
        if ($lang == 'en'):
            $sql = "SELECT count(*) FROM news WHERE valid = 1 AND (visible = 1 OR visible = 3) AND news_typ = :news_typ ";
        else:
            $sql = "SELECT count(*) FROM news WHERE valid = 1 AND (visible = 1 OR visible = 2) AND news_typ = :news_typ ";
        endif;
        $res = $pdo->prepare($sql);
        $res->execute(['news_typ'=>$news_typ]);
    endif;
    return $res->fetchColumn();
}