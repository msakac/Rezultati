<script src="/files/js/sidebar.js"></script>
<script src="/files/js/Rezultati/rezultatiSidebar.js"></script>
<div class="menu-btn">
    <i class="fas fa-bars"></i>
</div>
<div class="side-bar">
    <div class="close-btn">
        <i class="fas fa-times" style="padding: 30px 0"></i>
    </div>
    <div class="menu">
        <?php foreach (($sportLige?:[]) as $sport=>$liga): ?>
            <div class="item">
                <a class="sub-btn"><i class="fas fa-futbol" id = "<?= ($sport) ?>"></i><?= ($sport) ?><i class="fas fa-angle-right dropdown"></i></a>
                <div class="sub-menu" id="prikazi">
                    <?php foreach (($liga?:[]) as $ligaIspis): ?>
                        <a class="sub-item"><?= ($ligaIspis) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
       <!-- <div class="item">
            <a class="sub-btn"><i class="fas fa-table"></i>Tables<i class="fas fa-angle-right dropdown"></i></a>
            <div class="sub-menu">
                <a href="#" class="sub-item">Sub Item 01</a>
            </div>
        </div>-->
    </div>
</div>