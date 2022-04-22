$(document).ready(function(){
    var klubovi;
    var pozicije;
    dohvatiPozicije();
    dohvatiKlubove();
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
                url:"http://localhost/moderator/igraci",
                type:"POST"
            }
        });
    }


    function spremiKlubove(data) {
        klubovi = data;
        console.log(klubovi);
    }
    function dohvatiKlubove(){
        var stupac = 'data4';
        $.ajax({
            url: 'http://localhost/moderator/igraci/dohvatiKlubove',
            method: 'POST',
            data: {stupac:stupac},
            success: function(data) {
                spremiKlubove(data);
            }
        });
    }

    function spremiPozicije(data) {
        pozicije = data;
        console.log(pozicije);
    }
    function dohvatiPozicije(){
        var stupac = 'data5';
        $.ajax({
            url: 'http://localhost/moderator/igraci/dohvatiPozicije',
            method: 'POST',
            data: {stupac:stupac},
            success: function(data) {
                spremiPozicije(data);
            }
        });
    }

    $('#dodaj').click(function(){
        var html = '<tr>';
        html += '<td contenteditable id="data1"></td>';
        html += '<td contenteditable id="data2"></td>';
        html += '<td contenteditable id="data3"></td>';
        html += klubovi;
        html += pozicije;
        html += '<td contenteditable id="data6"></td>';
        html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs">Dodaj</button></td>';
        html += '</tr>';
        $('#tablica tbody').prepend(html);
    });

    $(document).on('click', '#insert', function(){
        var ime = $('#data2').text();
        var prezime = $('#data3').text();
        var klub = $('#data4').val();
        var pozicija = $('#data5').val();
        var vrijednost = $('#data6').text();
        if(ime != '' && prezime != '' && klub != '' && pozicija != '' && vrijednost != '')
        {
            $.ajax({
                url:"http://localhost/moderator/igraci/dodaj",
                method:"POST",
                data:{ime:ime,prezime:prezime,klub:klub,pozicija:pozicija,vrijednost:vrijednost},
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
                url:"http://localhost/moderator/igraci/obrisi",
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
            url:"http://localhost/moderator/igraci/azuriraj",
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