$(document).ready(function(){
    var klubovi;
    var lige;
    dohvatiLige();
    dohvatiPodatke();
    dohvatiKlubove(0);
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
                url:"http://localhost/moderator/klublige",
                type:"POST"
            }
        });
    }

    function spremiLige(data) {
        lige = data;
        console.log(lige);
    }
    function dohvatiLige(){
        var stupac = 'data1';
        $.ajax({
            url: 'http://localhost/moderator/klublige/dohvatiLige',
            method: 'POST',
            data: {stupac:stupac},
            success: function(data) {
                spremiLige(data);
            }
        });
    }

    function spremiKlubove(data) {
        klubovi = data;
        $('#data2').empty(); //ocistimo select
        $('#data2').append(klubovi);
        console.log(klubovi);
    }
    function dohvatiKlubove(liga){
        var stupac = 'data2';
        var liga = liga;
        $.ajax({
            url: 'http://localhost/moderator/klublige/dohvatiKlubove',
            method: 'POST',
            data: {stupac:stupac,liga:liga},
            success: function(data) {
                spremiKlubove(data);
            }
        });
    }

    $('#dodaj').click(function(){
        var html = '<tr>';
        html += lige;
        html += '<td><select id ="data2" class="form-control selectpicker" data-live-search="true"><option value="">Odaberi klub</option></select></td>'
        html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs">Dodaj</button></td>';
        html += '</tr>';
        $('#tablica tbody').prepend(html);
        $('#data2').prop('disabled', 'disabled'); //disabled odabir klubova sve dok ne odaberemo ligu
    });

    $(document).on('change', '#data1',function(){ //kad odaberemo ligu dohvacamo za tu ligu klubove
        var liga = $('#data1').val();
        $('#data2').prop('disabled', false);
        console.log(liga);
        dohvatiKlubove(liga);
         //dodamo nove opcije za odabir
    });

    $(document).on('click', '#insert', function(){
        var liga = $('#data1').val();
        var klub = $('#data2').val();
        if(klub != '' && liga != '')
        {
            $.ajax({
                url:"http://localhost/moderator/klublige/dodaj",
                method:"POST",
                data:{ liga:liga,klub:klub},
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
                url:"http://localhost/moderator/klublige/obrisi",
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
            url:"http://localhost/moderator/klublige/azuriraj",
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