<?php
function news_item_vypis_home ($pdo, $lang)
{
    if ($lang == 'en'):
        $sql = 'SELECT n.id as id, n.url_en as url, n.datum as datum, n.nazev_en as nazev, n.perex_en as perex, nt.nazev_en as typ, nt.color as color, nt.page_en as page
                FROM news n LEFT OUTER JOIN news_typ nt on n.news_typ = nt.id 
                WHERE n.valid = 1 AND (n.visible = 1 OR n.visible = 3) ORDER BY n.datum DESC LIMIT 5';
    else:
        $sql = 'SELECT n.id as id, n.url_cz as url, n.datum as datum, n.nazev_cz as nazev, n.perex_cz as perex, nt.nazev_cz as typ, nt.color as color, nt.page_cz as page
                FROM news n LEFT OUTER JOIN news_typ nt on n.news_typ = nt.id 
                WHERE n.valid = 1 AND (n.visible = 1 OR n.visible = 2) ORDER BY n.datum DESC LIMIT 5';
    endif;
    $res = $pdo->prepare($sql);
    $res->execute();
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        echo '
        <a href="/'.$lang.'/index/news/'.$dev['page'].'/'.$dev['url'].'" class="col-lg-2 col-md-6 text-decoration-none bg-white border rounded text-dark m-1 p-0" title="'.$dev['nazev'].'">
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
    }
}

