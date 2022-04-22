<?php echo $this->render('Moderator/modSidebar.php',NULL,get_defined_vars(),0); ?>
<script src="/files/js/Moderator/igraci.js"></script>
<section id="home">
    <div class="inner-width" style="max-width: 1100px">
        <div class="content">
            <div id="poruka"></div>
            <div>
                <h3>Igraci</h3>
                <button type="button" name="dodaj" id="dodaj" class="btn btn-info">Dodaj</button>
            </div>
            <table id="tablica" class="content-table" style="width: 100%">
                <thead>
                <tr>
                    <th style="text-align: center">ID</th>
                    <th style="text-align: center">Ime</th>
                    <th style="text-align: center">Prezime</th>
                    <th style="text-align: center">Klub</th>
                    <th style="text-align: center">Pozicija</th>
                    <th style="text-align: center">Vrijednost (u mil. €)</th>
                    <th style="text-align: center">Dodaj/Obriši</th>
                </tr>
                </thead>
        </div>
</section>
</body>
</html>