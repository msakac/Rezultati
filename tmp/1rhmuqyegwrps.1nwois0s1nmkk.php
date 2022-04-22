<?php echo $this->render('Moderator/modSidebar.php',NULL,get_defined_vars(),0); ?>
<script src="/files/js/Moderator/klublige.js"></script>
<section id="home">
    <div class="inner-width" style="max-width: 900px">
        <div class="content">
            <div id="poruka"></div>
            <div>
                <h3>Klub - lige</h3>
                <button type="button" name="dodaj" id="dodaj" class="btn btn-info">Dodaj</button>
            </div>
            <table id="tablica" class="content-table" style="width: 100%">
                <thead>
                <tr>
                    <th style="text-align: center">Liga</th>
                    <th style="text-align: center">Klub</th>
                    <th style="text-align: center">Dodaj/Obriši</th>
                </tr>
                </thead>
        </div>
</section>
</body>
</html>