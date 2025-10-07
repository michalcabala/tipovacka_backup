<?php
include ROOT_DIR."/functions/fun_tipovacky.php"; include ROOT_DIR."/functions/fun_news.php";
$qusr_id = $_SESSION['qusr_id']; $tip_all = tipovacka_all($pdo, $tipid);
$getreg = $_GET['getreg'] ?? 0; $upl_id = $_GET['upl_id'] ?? 0; $usr_id = $_GET['usr_id'] ?? 0;
if ($getreg<>0): tipovacka_user_register($pdo, $tip_all['id'], $qusr_id); endif;

?>
<div class="container-xxl bg-dark epal-header mb-0">
    <div class="container text-center my-0 pt-5 mt-1 pb-1">
        <h1 class="display-6 text-light mb-0 animated slideInDown">Tipovačka: <?php echo $tip_all['nazev_cz'];?></h1>
    </div>
</div>

<section class="page-section" id="tipovackydet">
    <div class="container p-0">
        <div class="col-sm-12 bg-danger">
            <p class="text-light p-1"><a href="/<?php echo $lang;?>/index/kontakt" class="text-light">Uživatel: <?php echo $_SESSION['qusr_user'];?></a></p>
        </div>
        <?php if($tip_all['datum_od'] > date('Y-m-d', time())): ?>
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Tipovačka ještě nebyla spuštěna</h2>
                <h3 class="section-subheading text-muted">Tipovačka začne <?php echo format_date_www($tip_all['datum_od']);?>.</h3>
            </div>
        <?php elseif(tipovacka_user_logged($pdo, $tip_all['id'], $qusr_id)==0):?>
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Tipovačka byla spuštěna</h2>
                <h3 class="section-subheading text-muted">Níže nalezneš popis pravidel tipovačky, pokud se chceš zůčastnit, registruj se.</h3>
                <a href="?getreg=<?php echo $tip_all['id'];?>" class="btn btn-danger py-sm-3 px-sm-5 me-3 animated fadeInLeft" title="Registrovat do tipovačky">Registrovat se do tipovačky</a>
            </div>
            <div class="card-body p-2 fs-5 stattext">
                <?php if ($tip_all['news_id']>0): news_view_id($pdo, $lang, $tip_all['news_id']); endif;?>
            </div>
        <?php else: ?>
            <div class="text-center">
                <h2 class="section-heading text-uppercase fs-4">Tipujte zápasy a pořadí týmů</h2>
            </div>
            <div class="card text-center shadow mb-1 pb-2">
                <div class="card-header py-0 table-responsive py-2">
                    <ul class="nav nav-pills card-header-pills small d-flex flex-nowrap">
                        <?php if($tip_all['tip_zapasy']==1):?><li class="nav-item"><a class="btn btn-success m-1 shadow" href="/cz/index/tipovacky/<?php echo $tipid;?>/zapasy">Tipujte zápasy</a></li><?php endif;?>
                        <?php if($tip_all['tip_poradi']==1):?><li class="nav-item"><a class="btn btn-success m-1 shadow" href="/cz/index/tipovacky/<?php echo $tipid;?>/tabulka">Tipujte pořadí</a></li><?php endif;?>
                        <?php if($tip_all['tip_otazky']==1):?><li class="nav-item"><a class="btn btn-success m-1 shadow" href="/cz/index/tipovacky/<?php echo $tipid;?>/otazky">Tipujte otázky</a></li><?php endif;?>
                        <li class="nav-item"><a class="btn btn-danger m-1 shadow" href="/cz/index/tipovacky/<?php echo $tipid;?>/vysledky">Pořadí</a></li>
                        <?php if($tip_all['tip_zapasy']==1):?><li class="nav-item"><a class="btn btn-warning m-1 shadow" href="/cz/index/tipovacky/<?php echo $tipid;?>/zapasyupl">Uplynulé zápasy</a></li><?php endif;?>
                        <li class="nav-item"><a class="btn btn-dark m-1 shadow" href="/cz/index/tipovacky/<?php echo $tipid;?>/stats">Statistiky</a></li>
                        <li class="nav-item"><a class="btn btn-info m-1 shadow" href="/cz/index/tipovacky/<?php echo $tipid;?>/pravidla">Pravidla a info</a></li>
                    </ul>
                </div>
            <?php if ($thirdmenu == 2210 OR $thirdmenu == 0): ?>
                <div class="text-center mt-2">
                    <h3 class="section-subheading text-muted">Tipujte zápasy do konce tipu. Výsledky naleznete v menu Uplynulé zápasy.</h3>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 g-0">
                    <?php
                    if(isset($_POST['form_id'])): tipovacka_zapasy_form_insert ($pdo, $_POST['form_id'], $tip_all['id'], $_POST['zapas_id'], $qusr_id, $_POST['team1_goals'], $_POST['team2_goals']); endif;
                    tipovacka_zapasy_form_vypis ($pdo, $tip_all['id'], $qusr_id, $tip_all['tip_zapasy_remizy']);
                    ?>
                </div>
            <?php endif;?>
            <?php if ($thirdmenu == 2220):?>
                <div class="text-center mt-2">
                    <h3 class="section-subheading text-muted">Tipujte celkové pořadí tabulky do <?php echo format_date_www($tip_all['datum_do_poradi']);?>. Pro vymazání Vašeho pořadí vložte
                    nulu. Nelze mít dva týmy stejné umístění. Vyhodnoceno bude na konci tipovačky.</h3>
                </div>
                <?php
                if(isset($_POST['add']) AND $_POST['add']==1):
                    tipovacka_teams_form_insert ($pdo, $tip_all['id'], $qusr_id);
                endif;
                tipovacka_teams_user_insert ($pdo, $tip_all['id'], $qusr_id);
                tipovacka_teams_form_vypis ($pdo, $tip_all['id'], $qusr_id, $tip_all['datum_do_poradi']);
                ?>
            <?php endif;?>
            <?php if ($thirdmenu == 2250 AND $usr_id == 0): ?>
                <div class="text-center mt-2">
                    <h3 class="section-subheading text-muted">Celkové pořadí tipovačky. Body jsou přepočítávány po každém vyhodnocení zápasu, či otázky a po finálním vyhodnocení umístění týmu v tabulce</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table data-order='[]' class="table table-striped table-hover table-bordered" id="dataTable">
                            <thead class="table-dark">
                            <tr>
                                <!--<th class="text-center">Tipovačka</th>-->
                                <th class="text-center">Uživatel</th>
                                <?php if ($tip_all['tip_zapasy']==1): ?>
                                    <th class="text-center">Body zápasy</th>
                                    <th class="text-center">3B | 2B | 1B | 0B</th>
                                <?php endif;?>
                                <?php if ($tip_all['tip_otazky']==1): ?><th class="text-center">Body otázky</th><?php endif;?>
                                <?php if ($tip_all['tip_poradi']==1): ?><th class="text-center">Body pořadí</th><?php endif;?>
                                <th class="text-center">Body celkem</th>
                                <?php if ($tip_all['tip_poradi']==1): ?><th class="text-center">Tipy pořadí</th><?php endif;?>
                                <th class="text-center">Pořadí</th>
                            </tr>
                            </thead>
                            <tfoot class="table-light">
                            <tr>
                                <!--<th class="text-center">Tipovačka</th>-->
                                <th class="text-center">Uživatel</th>
                                <?php if ($tip_all['tip_zapasy']==1): ?>
                                    <th class="text-center">Body zápasy</th>
                                    <th class="text-center text-nowrap">3B | 2B | 1B | 0B</th>
                                <?php endif;?>
                                <?php if ($tip_all['tip_otazky']==1): ?><th class="text-center">Body otázky</th><?php endif;?>
                                <?php if ($tip_all['tip_poradi']==1): ?><th class="text-center">Body pořadí</th><?php endif;?>
                                <th class="text-center">Body celkem</th>
                                <?php if ($tip_all['tip_poradi']==1): ?><th class="text-center">Tipy pořadí</th><?php endif;?>
                                <th class="text-center">Pořadí</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php tipovacka_users_rel_vypis($pdo, $tip_all['id']);?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif;?>
            <?php if ($thirdmenu == 2250 AND $usr_id <> 0 ): ?>
                <div class="text-center mt-2">
                    <h3 class="section-subheading text-muted">Jak tipoval <?php echo $usr_id; ?> celkovou tabulku</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table data-order='[]' class="table table-striped table-hover table-bordered" id="dataTable">
                            <thead class="table-dark">
                            <tr>
                                <th class="text-center">Uživatel</th>
                                <th class="text-center">Tým</th>
                                <th class="text-center">Finálně</th>
                                <th class="text-center">Tip</th>
                                <th class="text-center">Body</th>
                            </tr>
                            </thead>
                            <tfoot class="table-light">
                            <tr>
                                <th class="text-center">Uživatel</th>
                                <th class="text-center">Tým</th>
                                <th class="text-center">Finálně</th>
                                <th class="text-center">Tip</th>
                                <th class="text-center">Body</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php tipovacka_poradi_user_vypis($pdo, $tip_all['id'], $usr_id);?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif;?>
            <?php if ($thirdmenu == 2260 AND $upl_id == 0 ): ?>
                <div class="text-center mt-2">
                    <h3 class="section-subheading text-muted">Uplynulé zápasy tipovačky s vyhodnocením a tipy ostatních</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table data-order='[]' class="table table-striped table-hover table-bordered" id="dataTable">
                            <thead class="table-dark">
                            <tr>
                                <th class="text-center">Datum</th>
                                <th class="text-center">Skupina</th>
                                <th class="text-center">Zápas</th>
                                <th class="text-center">Výsledek</th>
                                <th class="text-center">Tip</th>
                                <th class="text-center">Koef.</th>
                                <th class="text-center">Body</th>
                                <th class="text-center">Tipy</th>
                            </tr>
                            </thead>
                            <tfoot class="table-light">
                            <tr>
                                <th class="text-center">Datum</th>
                                <th class="text-center">Skupina</th>
                                <th class="text-center">Zápas</th>
                                <th class="text-center">Výsledek</th>
                                <th class="text-center">Tip</th>
                                <th class="text-center">Koef.</th>
                                <th class="text-center">Body</th>
                                <th class="text-center">Tipy</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php tipovacka_zapasy_uplynule_vypis($pdo, $tip_all['id'], $qusr_id);?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif;?>
            <?php if ($thirdmenu == 2260 AND $upl_id <> 0 ): ?>
                <div class="text-center mt-2">
                    <h3 class="section-subheading text-muted">Jak tipovali ostatní a statistiky.</h3>
                </div>
                <div class="card border-left-info shadow h-100 m-2 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                    <?php tipovacka_zapasy_uplynule_stat($pdo, $upl_id);?>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table data-order='[]' class="table table-striped table-hover table-bordered" id="dataTable">
                            <thead class="table-dark">
                            <tr>
                                <th>Datum</th>
                                <th>Skupina</th>
                                <th>Uživatel</th>
                                <th>Zápas</th>
                                <th>Výsledek</th>
                                <th>Tip</th>
                                <th>Body</th>
                            </tr>
                            </thead>
                            <tfoot class="table-light">
                            <tr>
                                <th>Datum</th>
                                <th>Skupina</th>
                                <th>Uživatel</th>
                                <th>Zápas</th>
                                <th>Výsledek</th>
                                <th>Tip</th>
                                <th>Body</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php tipovacka_zapasy_uplynule_user_vypis($pdo, $tip_all['id'], $qusr_id, $upl_id);?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif;?>
            <?php if ($thirdmenu == 2280): ?>
                <div class="text-center mt-2">
                    <h3 class="section-subheading text-muted">Připravujeme ... Celkové statistiky tipovačky.</h3>
                </div>
                <div class="card border-left-info shadow h-100 m-2 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">

                        </div>
                    </div>
                </div>
            <?php endif;?>
            <?php if ($thirdmenu == 2290): ?>
                <div class="card-body p-2 stattext">
                    <?php if ($tip_all['news_id']>0): news_view_id($pdo, $lang, $tip_all['news_id']); endif;?>
                </div>
            <?php endif;?>



        <?php endif;?>
    </div>
</section>