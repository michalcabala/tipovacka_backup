<?php include ROOT_DIR."/functions/fun_tipovacky.php"; ?>
<?php if ($menu <> 200):?>
<div class="container-xxl bg-dark epal-header mb-0">
    <div class="container text-center my-0 pt-5 mt-1 pb-1">
        <h1 class="display-6 text-light mb-0 animated slideInDown"><?php echo $sv[3001];?></h1>
    </div>
</div>
<?php endif;?>

<section class="page-section" id="tipovacky">
    <div class="container p-0">
        <?php if ($menu <> 200):?>
        <div class="col-sm-12 bg-danger">
            <p class="text-light p-1"><a href="/<?php echo $lang;?>/index/kontakt" class="text-light">Uživatel: <?php echo $_SESSION['qusr_user'];?></a></p>
        </div>
        <?php endif;
        tipovacky_vypis_all ($pdo, $lang, 2);
        tipovacky_vypis_all ($pdo, $lang, 1);
        if ($menu <> 200): tipovacky_vypis_all ($pdo, $lang, 3);endif;
        ?>
    </div>
</section>