<!-- Rezlutati -->
<?php echo $this->render('Rezultati/rezultatiSidebar.php',NULL,get_defined_vars(),0); ?>
<script src="/files/js/Rezultati/rezultati.js"></script>
<section id="home">
    <div class="inner-width" style="max-width: 900px">
        <div class="content">
            <div id="poruka"></div>
            <div>
                <h1 id="naslov" style="font-size: xx-large">Svi rezultati</h1>
            </div>
            <table id="tablica" class="content-table" style="width: 100%">
                <thead>
                <tr>
                    <th style="text-align: center">Datum i vrijeme</th>
                    <th style="text-align: center">Status</th>
                    <!--<th style="text-align: center">Liga</th> -->
                    <th style="text-align: center">Domaćin</th>
                    <th style="text-align: center; ">Rezlutat</th>
                    <th style="text-align: center">Gost</th>
                    <th style="text-align: center">Detalji</th>
                </tr>
                </thead>
        </div>
    </div>
</section>
</body>
</html>