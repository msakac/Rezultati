<!-- Rezlutati -->
<?php echo $this->render('Rezultati/rezultatiSidebar.php',NULL,get_defined_vars(),0); ?>
<script src="/files/js/Rezultati/rezultatiSidebar.js"></script>
<section id="home">
    <div class="inner-width">
        <div class="content">
            <div id="poruka"></div>
            <div>
                <h1 id="naslov"></h1>
            </div>
            <table id="tablica" class="content-table" style="width: 100%">
                <thead>
                <tr>
                    <th style="text-align: center">Datum i vrijeme</th>
                    <th style="text-align: center">Domaćin</th>
                    <th style="text-align: center">Poeni Domaćin</th>
                    <th style="text-align: center">Poeni Gost</th>
                    <th style="text-align: center">Gost</th>
                    <th style="text-align: center"></th>
                </tr>
                </thead>
        </div>
    </div>
</section>
</body>
</html>