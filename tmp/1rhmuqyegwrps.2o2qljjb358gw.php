<?php echo $this->render('Moderator/modSidebar.php',NULL,get_defined_vars(),0); ?>
<script src="/files/js/Moderator/utakmice.js"></script>
<section id="home">
    <div class="inner-width" style="max-width: 1200px">
        <div class="content">
            <div id="poruka"></div>
            <div>
                <h3>Utakmice</h3>
                <button type="button" name="dodaj" id="dodaj" class="btn btn-info">Dodaj</button>
            </div>
            <table id="tablica" class="content-table" style="min-width: 100%">
                <thead>
                <tr>
                    <th style="text-align: center">ID</th>
                    <th style="text-align: center">Datum i vrijeme</th>
                    <th style="text-align: center">Liga</th>
                    <th style="text-align: center">Stadion</th>
                    <th style="text-align: center">Domaćin</th>
                    <th style="text-align: center">PD</th>
                    <th style="text-align: center">PG</th>
                    <th style="text-align: center">Gost</th>
                    <th style="text-align: center">D/O</th>
                </tr>
                </thead>
        </div>
</section>
</body>
</html>