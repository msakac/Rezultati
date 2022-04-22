<!-- Rezlutati -->
<?php echo $this->render('Rezultati/rezultatiSidebar.php',NULL,get_defined_vars(),0); ?>
<script src="/files/js/Rezultati/rezultati.js"></script>
<section id="home">
    <div class="inner-width" style="max-width: 1000px">
        <div class="content">
            <div>
                <h3 style="font-size: xx-large; color:deepskyblue;"><?= ($sport) ?> -> <?= ($regija) ?>: <?= ($liga) ?></h3>
                <hr>
                <h4><?= ($datumVrijeme) ?></h4>
                <h4><?= ($mjesto) ?>: <?= ($stadion) ?></h4>
                <hr>
                <h3><?= ($domacin) ?> &emsp; <?= ($domacinPoeni) ?> - <?= ($gostPoeni) ?> &emsp; <?= ($gost) ?></h3>
                <hr>
            </div>
            <div style="width: 50%; float: left;padding:10px">
                <h4><?= ($domacin) ?></h4>
                <table class="content-table" style="width: 100%">
                    <thead>
                    <tr>
                        <th style="text-align: center">Ime</th>
                        <th style="text-align: center">Prezime</th>
                        <th style="text-align: center; ">Uloga</th>
                    </tr>
                    </thead>
                    <?php foreach (($domacinEkipa?:[]) as $id=>$igrac): ?>
                        <tr>
                                <?php foreach (($igrac?:[]) as $igracInfo): ?>
                                    <td><?= ($igracInfo) ?></td>
                                <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div style="width: 50%; float: left;padding:10px">
                <h4><?= ($gost) ?></h4>
                <table class="content-table" style="width: 100%">
                    <thead>
                    <tr>
                        <th style="text-align: center">Ime</th>
                        <th style="text-align: center">Prezime</th>
                        <th style="text-align: center; ">Uloga</th>
                    </tr>
                    </thead>
                    <?php foreach (($gostEkipa?:[]) as $id=>$igrac): ?>
                        <tr>
                            <?php foreach (($igrac?:[]) as $igracInfo): ?>
                                <td><?= ($igracInfo) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
</body>
</html>