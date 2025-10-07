<?php include ROOT_DIR."/functions/fun_home.php"; ?>
<div class="container-xxl bg-dark epal-header mb-0">
    <div class="container text-center my-0 pt-5 mt-1 pb-1">
        <h1 class="display-6 text-light mb-0 animated slideInDown"><?php echo $sv[2000]; ?></h1>
    </div>
</div>


<section class="page-section" id="home">
    <div class="container p-0">
        <div class="col-sm-12 bg-danger">
            <p class="text-light p-1"><a href="/<?php echo $lang;?>/index/kontakt" class="text-light">Uživatel: <?php echo $_SESSION['qusr_user'];?></a></p>
        </div>
        <?php  include ROOT_DIR.'/inc/tipovacky.php'; ?>
        <div class="text-center">
            <h5 class="section-title ff-secondary text-center text-dark fw-normal"><?php echo $sv[2012]; ?></h5>
        </div>
        <div class="row blog-item-hover">
            <?php
            news_item_vypis_home ($pdo, $lang);?>
        </div>
    </div>
</section>


<?php

include ROOT_DIR.'/inc/kontakt.php'; ?>