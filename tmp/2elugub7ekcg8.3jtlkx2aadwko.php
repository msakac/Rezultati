<!-- Glavna navigacija za neprijavljenog korisnika -->
<body id="boja">
<nav class="navbar">
    <div class="inner-width">
        <a class="logo" id="logoPocetna" href="/rezultati"></a>
        <button class="menu-toggler">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="navbar-menu">
            <a href="/"><i class="fa fa-fw fa-hand-paper"></i>Početna</a>
            <a href="/rezultati"><i class="fa fa-fw fa-futbol"></i>Rezultati</a>
            <?php if ($SESSION['uloga'] >= 1): ?> <!-- Provjera je li uloga >=1 odnosno je li korisnik prijavljen -->
                
                    <a href="/galerija"><i class="fa fa-fw fa-image"></i>Galerija</a>
                    <a href="/statistika"><i class="fa fa-fw fa-chart-bar"></i>Statistika</a>
                    <?php if ($SESSION['uloga'] >= 2): ?> <!-- Provjera je li uloga >=2 odnosno je li moderator il admin -->
                        
                            <a href="/moderator"><i class="fa fa-fw fa-cog"></i>ModPanel</a>
                        
                    <?php endif; ?>
                    <?php if ($SESSION['uloga'] >= 3): ?><!-- Provjera je li uloga >=3 odnosno jeli admin -->
                        
                            <a href="/admin"><i class="fa fa-fw fa-cogs"></i>AdminPanel</a>
                        
                    <?php endif; ?>
                    <a href="/profil"><i class="fa fa-fw fa-user-circle"></i><?= ($SESSION['korisnik']) ?></a>
                    <a href="/odjava"><i class="fa fa-fw fa-sign-out"></i>Odjava</a>
                
                <?php else: ?>
                        <a href="/prijava"><i class="fa fa-fw fa-sign-in"></i>Prijava</a><!-- Ako je prijavljen ne prikazujem prijavu-->
                
            <?php endif; ?>
        </div>
    </div>
</nav>
