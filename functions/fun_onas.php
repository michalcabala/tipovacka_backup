<?php
function blog_item_vypis_home ($pdo, $lang)
{
    if ($lang == 'en'):
        $sql = 'SELECT b.id, b.url_en as url, b.datum, b.nazev_en as nazev, b.perex_en as perex, bk.nazev_en as kategorie, bk.color, bk.page_en as page
                    FROM blog b LEFT OUTER JOIN blog_kat bk on b.blog_kat = bk.id 
                    WHERE b.valid = 1 AND b.fav = 1 AND (b.visible = 1 OR b.visible = 3) ORDER BY b.datum DESC';
    else:
        $sql = 'SELECT b.id, b.url_cz as url, b.datum, b.nazev_cz as nazev, b.perex_cz as perex, bk.nazev_cz as kategorie, bk.color, bk.page_cz as page
                    FROM blog b LEFT OUTER JOIN blog_kat bk on b.blog_kat = bk.id 
                    WHERE b.valid = 1 AND b.fav = 1 AND (b.visible = 1 OR b.visible = 2) ORDER BY b.datum DESC';
    endif;
    $res = $pdo->prepare($sql);
    $res->execute();
    $stmt = $res->fetchAll();

    foreach ($stmt as $dev)
    {
        echo '
        <a href="/'.$lang.'/index/blog/'.$dev['page'].'/'.$dev['url'].'" class="text-decoration-none text-dark" title="'.$dev['nazev'].'">
                <div class="testimonial-item bg-white border rounded p-4">
                    
                    <p>'.$dev['perex'].'</p>
                    <div class="d-flex align-items-center">
                        <div class="ps-3">
                            <h5 class="mb-1">'.$dev['nazev'].'</h5>
                            <small>'.format_date_www($dev['datum']).'</small>
                        </div>
                    </div>
                </div>
            </a>';
    }
}

