$(document).ready(function(){
    var moderatori;
    var sportovi;
    dohvatiSportove();
    dohvatiModeratore();
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
                url:"http://localhost/admin/sportModerator",
                type:"POST"
            }
        });
    }

    function spremiModeratore(data) {
        moderatori = data;
        console.log(moderatori);
    }
    function dohvatiModeratore(){
        var uloge = null;
        var stupac = 'data1';
        $.ajax({
            url: 'http://localhost/admin/sportModerator/dohvatiModeratore',
            method: 'POST',
            data: {stupac:stupac},
            success: function(data) {
                spremiModeratore(data);
            }
        });
    }

    function spremiSportove(data) {
        sportovi = data;
        console.log(sportovi);
    }
    function dohvatiSportove(){
        var uloge = null;
        var stupac = 'data2';
        $.ajax({
            url: 'http://localhost/admin/sportModerator/dohvatiSportove',
            method: 'POST',
            data: {stupac:stupac},
            success: function(data) {
                spremiSportove(data);
            }
        });
    }

    $('#dodaj').click(function(){
        var html = '<tr>';
        html += moderatori;
        html += sportovi;
        html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs">Dodaj</button></td>';
        html += '</tr>';
        $('#tablica tbody').prepend(html);
    });

    $(document).on('click', '#insert', function(){
        var idKorisnik = $('#data1').val(); //ili $('#data2 option:selected').val();
        var idSport = $('#data2').val();
        if(idKorisnik != '' && idSport != '')
        {
            $.ajax({
                url:"http://localhost/admin/sportModerator/dodaj",
                method:"POST",
                data:{idKorisnik:idKorisnik, idSport:idSport},
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
                url:"http://localhost/admin/sportModerator/obrisi",
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
});