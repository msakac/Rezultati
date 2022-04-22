<!-- Rezlutati -->
<section id="home">
    <div class="inner-width" style="max-width: 1000px">
        <div class="content">
            <div style="width: 50%; float: left;">
                <h3 style="font-size: x-large; text-align: left"><?= ($sport) ?> > <?= ($regija) ?></h3>
                <hr>
            </div>
            <div style="width: 50%; float: left;">
                <h3 style="font-size: x-large; text-align: right"><?= ($kratica) ?></h3>
                <hr>
            </div>
            <div>
                <h3 style="font-size: xx-large; color:deepskyblue"><?= ($title) ?></h3>
                <h4><?= ($mjesto) ?>: <?= ($stadion) ?></h4>
                <hr>
        </div>
            <div style="width: 50%; float: left;padding:10px">
                <table class="content-table" style="width: 100%">
                    <thead>
                    <tr>
                        <th style="text-align: center">Ime</th>
                        <th style="text-align: center">Prezime</th>
                        <th style="text-align: center; ">Uloga</th>
                    </tr>
                    </thead>
                    <?php foreach (($ekipa?:[]) as $id=>$igrac): ?>
                        <tr>
                            <?php foreach (($igrac?:[]) as $igracInfo): ?>
                                <td><?= ($igracInfo) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div style="width: 50%; float: left;padding:10px">
                <table class="content-table" style="width: auto;">
                    <thead>
                    <tr>
                        <th style="text-align: center">Datum i vrijeme</th>
                        <th style="text-align: center; ">Domaćin</th>
                        <th style="text-align: center; ">Rezlutat</th>
                        <th style="text-align: center; ">Gost</th>
                    </tr>
                    </thead>
                    <?php foreach (($utakmice?:[]) as $id=>$utakmica): ?>
                        <tr>
                            <?php foreach (($utakmica?:[]) as $utakmicaInfo): ?>
                                <td><?= ($utakmicaInfo) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
            </div>
    </div>
</section>
</body>
</html>