<?php echo $this->render('Admin/adminSidebar.php',NULL,get_defined_vars(),0); ?>
<script src="/files/js/Admin/dnevnikRada.js"></script>
<section id="home">
    <div class="inner-width" style="max-width: 1100px" >
        <div class="content">
            <div id="poruka"></div>
            <div>
                <h3>Dnevnik rada</h3>
            </div>
            <table id="tablica" class="content-table" style="width: 100%">
                <thead>
                <tr>
                    <th style="text-align: center">Radnja</th>
                    <th style="text-align: center">Datum i vrijeme</th>
                    <th style="text-align: center">Tip rada</th>
                    <th style="text-align: center">Korisnik</th>
                    <th style="text-align: center">ID korisnik</th>
                </tr>
                </thead>
        </div>
</section>
</body>
</html>