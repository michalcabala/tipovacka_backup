<!-- Navbar  -->
<nav class="navbar navbar-expand-lg navbar-dark bg-light px-3 px-md-3 py-1 py-md-0" id="mainNav">
    <div class="container-xl">
        <a href="/<?php echo $lang;?>/index/home" class="navbar-brand p-0" title="<?php echo $sv[100];?>">
            <img src="/images/_design/logo-tipovacka-red-white.svg" class="d-inline m-1" alt="<?php echo $sv[100];?>">
            <h1 class="ms-2 d-none h1_logo"><?php echo $sv[100];?></h1>
            <small class="d-none">hcpcefans.cz</small>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="bi bi-menu-up">&nbsp;<small>menu</small></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0 pe-4">
                <a href="/<?php echo $lang;?>/index/home#page-top" class="nav-item nav-link text-nowrap <?php if($menu==200){echo 'active';} ?> fs-5" title="<?php echo $sv[200];?>"><?php echo $sv[200];?></a>
                <a href="/<?php echo $lang;?>/index/news" class="nav-item nav-link text-nowrap <?php if($menu==210){echo 'active';} ?> fs-5" title="<?php echo $sv[210];?>"><?php echo $sv[210];?></a>
                <a href="/<?php echo $lang;?>/index/tipovacky" class="nav-item nav-link text-nowrap <?php if($menu==220){echo 'active';} ?> fs-5" title="<?php echo $sv[220];?>"><?php echo $sv[220];?></a>
                <a href="/<?php echo $lang;?>/index/kontakt" class="nav-item nav-link text-nowrap <?php if($menu==240){echo 'active';} ?> fs-5" title="<?php echo $sv[240];?>"><?php echo $sv[240];?></a>
                <a href="/<?php echo $lang;?>/index?qusr_logout=1" class="nav-item nav-link text-nowrap fs-5" title="Odhlásit se">Odhlásit&nbsp;se</a>
            </div>
        </div>
    </div>
</nav>
<!-- End of Navbar  -->