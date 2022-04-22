<!-- Rezlutati -->
<include href="Rezultati/rezultatiSidebar.php"/>
<script src="/files/js/Rezultati/rezultatiDetalji.js"></script>
<section id="home">
    <div class="inner-width" style="max-width: 1000px">
        <div class="content">
            <div>
                <a style="color:deepskyblue;" id="linkLiga" href="" data-value = "{{@ligaNazivId}}"><h3 style="font-size: xx-large;">{{@sport}} -> {{@regija}}: {{@liga}}</h3></a>
                <hr>
                <h4>{{@datumVrijeme}}</h4>
                <h4>{{@mjesto}}: {{@stadion}}</h4>
                <hr>
                <h3>{{@domacin}} &emsp; {{@domacinPoeni}} - {{@gostPoeni}} &emsp; {{@gost}}</h3>
                <hr>
            </div>
            <div style="width: 50%; float: left;padding:10px">
                <a style="color:deepskyblue;" id="linkDomacin" href="" data-value = "{{@domacinNazivId}}"><h4>{{@domacin}}</h4></a>
                <table class="content-table" style="width: 100%">
                    <thead>
                    <tr>
                        <th style="text-align: center">Ime</th>
                        <th style="text-align: center">Prezime</th>
                        <th style="text-align: center; ">Uloga</th>
                    </tr>
                    </thead>
                    <repeat group="{{@domacinEkipa}}" key="{{@id}}" value="{{@igrac}}">
                        <tr>
                                <repeat group="{{@igrac}}" value="{{@igracInfo}}">
                                    <td>{{@igracInfo}}</td>
                                </repeat>
                        </tr>
                    </repeat>
                </table>
            </div>
            <div style="width: 50%; float: left;padding:10px">
                <a style="color:deepskyblue;" id="linkGost" href="" data-value = "{{@gostNazivId}}"><h4>{{@gost}}</h4></a>
                <table class="content-table" style="width: 100%">
                    <thead>
                    <tr>
                        <th style="text-align: center">Ime</th>
                        <th style="text-align: center">Prezime</th>
                        <th style="text-align: center; ">Uloga</th>
                    </tr>
                    </thead>
                    <repeat group="{{@gostEkipa}}" key="{{@id}}" value="{{@igrac}}">
                        <tr>
                            <repeat group="{{@igrac}}" value="{{@igracInfo}}">
                                <td>{{@igracInfo}}</td>
                            </repeat>
                        </tr>
                    </repeat>
            </div>
        </div>
    </div>
</section>
</body>
</html>