<!-- Rezlutati -->
<?php echo $this->render('Rezultati/rezultatiSidebar.php',NULL,get_defined_vars(),0); ?>
<script src="/files/js/Rezultati/rezultatiDetalji.js"></script>
<section id="home">
    <div class="inner-width" style="max-width: 1100px;padding-bottom: 50px;">
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
                <hr>
                <div style="width: 50%; float: left;">
                    <h4>Tablica</h4>
                    <table class="content-table" style="width: auto;">
                        <thead>
                        <tr>
                            <th style="text-align: center">Klub</th>
                            <th style="text-align: center">O</th>
                            <th style="text-align: center; ">P</th>
                            <th style="text-align: center; ">N</th>
                            <th style="text-align: center; ">I</th>
                            <th style="text-align: center; ">G</th>
                            <th style="text-align: center; ">Bodovi</th>
                        </tr>
                        </thead>
                        <?php foreach (($klubovi?:[]) as $id=>$klub): ?>
                            <tr>
                                <?php foreach (($klub?:[]) as $klubInfo): ?>
                                    <td><?= ($klubInfo) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <div style="width: 50%; float: left;">
                    <h4>Raspored utakmica</h4>
                    <table class="content-table" style="width: auto;">
                        <thead>
                        <tr>
                            <th style="text-align: center">Datum i vrijeme</th>
                            <th style="text-align: center">Status</th>
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
                    </table>
                    <br>
                    <h4>Završene utakmice</h4>
                    <table class="content-table" style="width: auto;">
                        <thead>
                        <tr>
                            <th style="text-align: center">Datum i vrijeme</th>
                            <th style="text-align: center">Status</th>
                            <th style="text-align: center; ">Domaćin</th>
                            <th style="text-align: center; ">Rezlutat</th>
                            <th style="text-align: center; ">Gost</th>
                        </tr>
                        </thead>
                        <?php foreach (($zavrseneUtakmice?:[]) as $id=>$utakmica): ?>
                            <tr>
                                <?php foreach (($utakmica?:[]) as $utakmicaInfo): ?>
                                    <td><?= ($utakmicaInfo) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <p style="padding-bottom: 50px; color:green"> .</p>
            </div>
        </div>
    </div>
</section>
</body>
</html>