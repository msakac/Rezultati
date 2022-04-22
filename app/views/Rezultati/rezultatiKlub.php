<!-- Rezlutati -->
<section id="home">
    <div class="inner-width" style="max-width: 1000px">
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
                <h4>{{@mjesto}}: {{@stadion}}</h4>
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
                    <repeat group="{{@ekipa}}" key="{{@id}}" value="{{@igrac}}">
                        <tr>
                            <repeat group="{{@igrac}}" value="{{@igracInfo}}">
                                <td>{{@igracInfo}}</td>
                            </repeat>
                        </tr>
                    </repeat>
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
                    <repeat group="{{@utakmice}}" key="{{@id}}" value="{{@utakmica}}">
                        <tr>
                            <repeat group="{{@utakmica}}" value="{{@utakmicaInfo}}">
                                <td>{{@utakmicaInfo}}</td>
                            </repeat>
                        </tr>
                    </repeat>
            </div>
    </div>
</section>
</body>
</html>