$(document).ready(function(){
    var kluboviDomacin;
    var kluboviGost;
    var stadioni;
    var lige;
    dohvatiLige();
    dohvatiStadione();
    dohvatiKluboveDomacin(0);
    dohvatiKluboveGost(0);
    dohvatiPodatke();
    function dohvatiPodatke()
    {
        var dataTable = $('#tablica').DataTable({
            "language": {
                "lengthMenu": "Prikazi _MENU_ rezlutata po stranici",
                "zeroRecords": "Nema podataka",
                "sInfo": "Prikazano _START_ - _END_ od _TOTAL_ podataka",
                "infoEmpty": "Nema podataka",
                "infoFiltered": "(filtrirano od _MAX_ podataka)",
                "search": "Trazi:",
                "paginate": {
                    "previous": "Prethodna",
                    "next": "Sljedeća"
                }
            },
            "serverSide" : true,
            "order" : [],
            "ajax" : {
                url:"http://localhost/moderator/utakmice",
                type:"POST"
            }
        });
    }

    function spremiLige(data) {
        lige = data;
        console.log(lige);
    }
    function dohvatiLige(){
        var stupac = 'data3';
        $.ajax({
            url: 'http://localhost/moderator/utakmice/dohvatiLige',
            method: 'POST',
            data: {stupac:stupac},
            success: function(data) {
                spremiLige(data);
            }
        });
    }

    function spremiKluboveDomacin(data) {
        kluboviDomacin = data;
        $('#data5').empty(); //ocistimo select
        $('#data5').append(kluboviDomacin);
        console.log(kluboviDomacin);
    }
    function dohvatiKluboveDomacin(liga){
        var stupac = 'data5';
        var liga = liga;
        $.ajax({
            url: 'http://localhost/moderator/utakmice/dohvatiKlubove',
            method: 'POST',
            data: {stupac:stupac,liga:liga},
            success: function(data) {
                spremiKluboveDomacin(data);
            }
        });
    }

    function spremiKluboveGost(data) {
        kluboviGost = data;
        $('#data8').empty(); //ocistimo select
        $('#data8').append(kluboviGost);
        console.log(kluboviGost);
    }
    function dohvatiKluboveGost(liga){
        var stupac = 'data8';
        var liga = liga;
        $.ajax({
            url: 'http://localhost/moderator/utakmice/dohvatiKlubove',
            method: 'POST',
            data: {stupac:stupac,liga:liga},
            success: function(data) {
                spremiKluboveGost(data);
            }
        });
    }

    function spremiStadione(data) {
        stadioni = data;
        console.log(stadioni);
    }
    function dohvatiStadione(){
        var stupac = 'data4';
        $.ajax({
            url: 'http://localhost/moderator/utakmice/dohvatiStadione',
            method: 'POST',
            data: {stupac:stupac},
            success: function(data) {
                spremiStadione(data);
            }
        });
    }

    $(document).on('change', '#data3',function(){ //kad odaberemo ligu dohvacamo za tu ligu klubove
        var liga = $('#data3').val();
        $('#data5').prop('disabled', false);
        $('#data8').prop('disabled', false);
        console.log(liga);
        dohvatiKluboveDomacin(liga);
        dohvatiKluboveGost(liga);
        //dodamo nove opcije za odabir
    });

    $('#dodaj').click(function(){
        var html = '<tr>';
        html += '<td contenteditable id="data1"></td>';
        html += '<td contenteditable id="data2"><input type="text" id="picker" size="9" style="color:black"></td>';
        html += lige;
        html += stadioni;
        html += '<td><select id ="data5" class="form-control selectpicker" style="width: 100px"  data-live-search="true"><option value="">Domacin</option></select></td>';
        html += '<td contenteditable id="data6"></td>';
        html += '<td contenteditable id="data7"></td>';
        html += '<td><select id ="data8" class="form-control selectpicker" style="width: 100px"  data-live-search="true"><option value="">Gost</option></select></td>';
        html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs">Dodaj</button></td>';
        html += '</tr>';
        $('#tablica tbody').prepend(html);
        $('#data5').prop('disabled', 'disabled');
        $('#data8').prop('disabled', 'disabled');
        $("#picker").datetimepicker({
           format: 'Y-m-d H:i:i',
            step:10,
        });
    });

    $(document).on('click', '#insert', function(){
        var datumVrijeme = $('#picker').val();
        var liga = $('#data3').val();
        var stadion = $('#data4').val();
        var domacin = $('#data5').val();
        var poeniD = $('#data6').text();
        var poeniG = $('#data7').text();
        var gost = $('#data8').val();
        if(datumVrijeme != '' && liga != '' && stadion != '' && domacin != '' && gost != '')
        {
            $.ajax({
                url:"http://localhost/moderator/utakmice/dodaj",
                method:"POST",
                data:{datumVrijeme:datumVrijeme,liga:liga,stadion:stadion,domacin:domacin,poeniD:poeniD,poeniG:poeniG,gost:gost},
                success:function(data)
                {
                    $('#poruka').html('<div class="alert alert-success">'+data+'</div>');
                    $('#tablica').DataTable().destroy();
                    dohvatiPodatke();
                }
            });
            setInterval(function(){
                $('#poruka').html('');
            }, 5000);
        }
        else
        {
            alert("Nisu uneseni svi podaci");
        }
    }); //dodavanje

    $(document).on('click', '.delete', function(){
        var id = $(this).attr("id");
        if(confirm("Jeste li sigurni da želite obrisati ovaj redak?"))
        {
            $.ajax({
                url:"http://localhost/moderator/utakmice/obrisi",
                method:"POST",
                data:{id:id},
                success:function(data){
                    $('#poruka').html('<div class="alert alert-success">'+data+'</div>');
                    $('#tablica').DataTable().destroy();
                    dohvatiPodatke();
                }
            });
            setInterval(function(){
                $('#poruka').html('');
            }, 5000);
        }
    }); //brisanje

    function azurirajPodatke(id, stupac, vrijednost)
    {
        $.ajax({
            url:"http://localhost/moderator/utakmice/azuriraj",
            method:"POST",
            data:{id:id, stupac:stupac, vrijednost:vrijednost},
            success:function(data)
            {
                $('#poruka').html('<div class="alert alert-success">'+data+'</div>');
                $('#tablica').DataTable().destroy();
                dohvatiPodatke();
            }
        });
        setInterval(function(){
            $('#poruka').html('');
        }, 5000);
    }

    $(document).on('blur', '.update', function(){
        var id = $(this).data("id");
        var stupac = $(this).data("column");
        var vrijednost = $(this).text();
        azurirajPodatke(id, stupac, vrijednost);
    });
});