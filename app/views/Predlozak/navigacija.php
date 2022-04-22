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
            <check if="{{@SESSION.uloga >= 1}}"> <!-- Provjera je li uloga >=1 odnosno je li korisnik prijavljen -->
                <false>
                        <a href="/prijava"><i class="fa fa-fw fa-sign-in"></i>Prijava</a><!-- Ako je prijavljen ne prikazujem prijavu-->
                </false>
                <true>
                    <a href="/galerija"><i class="fa fa-fw fa-image"></i>Galerija</a>
                    <a href="/statistika"><i class="fa fa-fw fa-chart-bar"></i>Statistika</a>
                    <check if="{{@SESSION.uloga >= 2 }}"> <!-- Provjera je li uloga >=2 odnosno je li moderator il admin -->
                        <true>
                            <a href="/moderator"><i class="fa fa-fw fa-cog"></i>ModPanel</a>
                        </true>
                    </check>
                    <check if="{{@SESSION.uloga >= 3}}"><!-- Provjera je li uloga >=3 odnosno jeli admin -->
                        <true>
                            <a href="/admin"><i class="fa fa-fw fa-cogs"></i>AdminPanel</a>
                        </true>
                    </check>
                    <a href="/profil"><i class="fa fa-fw fa-user-circle"></i>{{@SESSION.korisnik}}</a>
                    <a href="/odjava"><i class="fa fa-fw fa-sign-out"></i>Odjava</a>
                </true>
            </check>
        </div>
    </div>
</nav>
