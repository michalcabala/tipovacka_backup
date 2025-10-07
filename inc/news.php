<?php
include ROOT_DIR."/functions/fun_news.php";

$limit = $_SESSION['records-limit'] ?? 8;
    $pages = (isset($_GET['pages']) && is_numeric($_GET['pages']) ) ? $_GET['pages'] : 1;
    $paginationStart = ($pages - 1) * $limit;
    if ($category == ''): $news_typ = 0; else: $news_typ = news_typ_id($pdo, $category); endif;
    $allRecords = news_count($pdo, $lang, $news_typ);
    $totalPages = ceil($allRecords / $limit);
    $paginationCount = 5;
    $prev = $pages - 1; $next = $pages + 1;


?>
<div class="container-xxl bg-dark epal-header mb-0">
    <div class="container text-center my-0 pt-5 mt-1 pb-1">
        <h1 class="display-6 text-light mb-2 animated slideInDown"><?php echo $sv[2001];?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a href="/<?php echo $lang;?>/index/news" class="<?php if($secmenu==210){echo "text-light";} else{echo "text-warning";}?> text-decoration-none fw-medium" title="<?php echo $sv[219];?>"><?php echo $sv[219];?></a></li>
                <li class="breadcrumb-item"><a href="/<?php echo $lang;?>/index/news/obecne" class="<?php if($secmenu==211){echo "text-light";} else{echo "text-warning";}?> text-decoration-none fw-medium" title="<?php echo $sv[211];?>"><?php echo $sv[211];?></a></li>
                <li class="breadcrumb-item"><a href="/<?php echo $lang;?>/index/news/tipovacky" class="<?php if($secmenu==212){echo "text-light";} else{echo "text-warning";}?> text-decoration-none fw-medium" title="<?php echo $sv[212];?>"><?php echo $sv[212];?></a></li>
            </ol>
        </nav>
        <?php if($text <> ''): echo '<h2 class="display-6 text-light mb-0 animated slideInDown">'.news_view_name($pdo, $lang, $text).'</h2>'; endif;?>
        <?php if($text <> ''): echo '<h6 class="text-light mb-0 animated slideInDown">'.news_view_datum($pdo, $lang, $text).'</h6>'; endif;?>
    </div>
</div>

<?php if ($text == ''): ?>
<div class="container-xxl py-2">
    <div class="container">
        <div class="text-center">
            <h5 class="section-title ff-secondary text-center text-dark fw-normal"><?php if($secmenu == 210): echo $sv[2011]; else: echo $sv[$secmenu]; endif; ?></h5>
        </div>
        <div class="row blog-item-hover">
            <?php news_item_vypis_all ($pdo, $lang, $news_typ, $paginationStart, $limit);?>
        </div>
        <!-- Pagination -->
        <nav aria-label="Page navigation example" class="table-responsive mt-5">
            <ul class="pagination justify-content-center">
                <?php if($pages>$paginationCount): ?>
                    <li class="page-item"><a class="page-link" href="?pages=1" title="<?php echo $sv[2901];?>"><?php echo $sv[2901];?></a></li>
                <?php endif;?>
                    <li class="page-item <?php if($pages <= 1){ echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($pages <= 1){ echo '#'; } else { echo "?pages=" . $prev; } ?>" title="<?php echo $sv[2902];?>"><?php echo $sv[2902];?></a>
                    </li>
                <?php for($i = max(1, $pages - $paginationCount); $i <= min($pages + $paginationCount, $totalPages); $i++): ?>
                    <li class="page-item <?php if($pages == $i) {echo 'active'; } ?>">
                        <a class="page-link" href="?pages=<?= $i; ?>" title="<?= $i; ?>"> <?= $i; ?> </a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php if($pages >= $totalPages) { echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($pages >= $totalPages){ echo '#'; } else {echo "?pages=". $next; } ?>" title="<?php echo $sv[2903];?>"><?php echo $sv[2903];?></a>
                </li>
                <?php if($pages<($totalPages-$paginationCount)): ?>
                    <li class="page-item"><a class="page-link" href="?pages=<?php echo $totalPages;?>" title="<?php echo $sv[2904];?>"><?php echo $sv[2904];?></a></li>
                <?php endif;?>
            </ul>
        </nav>
    </div>
</div>
<?php else: ?>
<div class="container-xxl py-3">
    <div class="container p-0">
        <div class="card-body p-5 fs-5 stattext">
            <?php news_view($pdo, $lang, $text);?>
        </div>
    </div>

</div>
<?php endif;?>
