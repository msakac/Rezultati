$(document).ready(function(){
    var sportovi;
    var regije;
    var stadioni;
    dohvatiSportove();
    dohvatiRegije();
    dohvatiStadione();
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
                url:"http://localhost/moderator/klubovi",
                type:"POST"
            }
        });
    }

    function spremiSportove(data) {
        sportovi = data;
        console.log(sportovi);
    }
    function dohvatiSportove(){
        var stupac = 'data5';
        $.ajax({
            url: 'http://localhost/moderator/klubovi/dohvatiSportove',
            method: 'POST',
            data: {stupac:stupac},
            success: function(data) {
                spremiSportove(data);
            }
        });
    }

    function spremiStadione(data) {
        stadioni = data;
        console.log(stadioni);
    }
    function dohvatiStadione(){
        var stupac = 'data3';
        $.ajax({
            url: 'http://localhost/moderator/klubovi/dohvatiStadione',
            method: 'POST',
            data: {stupac:stupac},
            success: function(data) {
                spremiStadione(data);
            }
        });
    }

    function spremiRegije(data) {
        regije = data;
        console.log(regije);
    }
    function dohvatiRegije(){
        var stupac = 'data4';
        $.ajax({
            url: 'http://localhost/moderator/klubovi/dohvatiRegije',
            method: 'POST',
            data: {stupac:stupac},
            success: function(data) {
                spremiRegije(data);
            }
        });
    }

    $('#dodaj').click(function(){
        var html = '<tr>';
        html += '<td contenteditable id="data1"></td>';
        html += '<td contenteditable id="data2"></td>';
        html += stadioni;
        html += regije;
        html += sportovi;
        html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs">Dodaj</button></td>';
        html += '</tr>';
        $('#tablica tbody').prepend(html);
    });

    $(document).on('click', '#insert', function(){
        var id = $('#data1').text();
        var naziv = $('#data2').text();
        var stadion = $('#data3').val();
        var regije = $('#data4').val();
        var sport = $('#data5').val();
        if(id != '' && naziv != '' && regije!='' && sport!='')
        {
            $.ajax({
                url:"http://localhost/moderator/klubovi/dodaj",
                method:"POST",
                data:{id:id, naziv:naziv,stadion:stadion,regije:regije,sport:sport},
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
                url:"http://localhost/moderator/klubovi/obrisi",
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
            url:"http://localhost/moderator/klubovi/azuriraj",
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