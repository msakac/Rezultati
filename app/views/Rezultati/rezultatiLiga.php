<!-- Rezlutati -->
<include href="Rezultati/rezultatiSidebar.php"/>
<script src="/files/js/Rezultati/rezultatiDetalji.js"></script>
<section id="home">
    <div class="inner-width" style="max-width: 1100px;padding-bottom: 50px;">
        <div class="content">
            <div style="width: 50%; float: left;">
                <h3 style="font-size: x-large; text-align: left">{{@sport}} > {{@regija}}</h3>
                <hr>
            </div>
            <div style="width: 50%; float: left;">
                <h3 style="font-size: x-large; text-align: right">{{@kratica}}</h3>
                <hr>
            </div>
            <div>
                <h3 style="font-size: xx-large; color:deepskyblue">{{@title}}</h3>
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
                        <repeat group="{{@klubovi}}" key="{{@id}}" value="{{@klub}}">
                            <tr>
                                <repeat group="{{@klub}}" value="{{@klubInfo}}">
                                    <td>{{@klubInfo}}</td>
                                </repeat>
                            </tr>
                        </repeat>
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
                        <repeat group="{{@utakmice}}" key="{{@id}}" value="{{@utakmica}}">
                            <tr>
                                <repeat group="{{@utakmica}}" value="{{@utakmicaInfo}}">
                                    <td>{{@utakmicaInfo}}</td>
                                </repeat>
                            </tr>
                        </repeat>
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
                        <repeat group="{{@zavrseneUtakmice}}" key="{{@id}}" value="{{@utakmica}}">
                            <tr>
                                <repeat group="{{@utakmica}}" value="{{@utakmicaInfo}}">
                                    <td>{{@utakmicaInfo}}</td>
                                </repeat>
                            </tr>
                        </repeat>
                    </table>
                </div>
                <p style="padding-bottom: 50px; color:green"> .</p>
            </div>
        </div>
    </div>
</section>
</body>
</html>