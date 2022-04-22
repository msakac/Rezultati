<?php echo $this->render('Admin/adminSidebar.php',NULL,get_defined_vars(),0); ?>
<script src="/files/js/Admin/korisnici.js"></script>
<section id="home">
    <div class="inner-width" style="max-width: 1100px" >
        <div class="content">
            <div id="poruka"></div>
            <div>
                <h3>Korisnici</h3>
                <button type="button" name="dodaj" id="dodaj" class="btn btn-info">Dodaj</button>
            </div>
            <table id="tablica" class="content-table" style="width: 100%">
                <thead>
                <tr>
                    <th style="text-align: center">Korisničko ime</th>
                    <th style="text-align: center">E-mail</th>
                    <th style="text-align: center">Lozinka</th>
                    <th style="text-align: center">Ime</th>
                    <th style="text-align: center">Prezime</th>
                    <th style="text-align: center">Uloga</th>
                    <th style="text-align: center">Dodaj/Obriši</th>
                </tr>
                </thead>
    </div>
</section>
</body>
</html>