$(document).ready(function(){
    var regije;
    var sportovi;
    dohvatiSportove();
    dohvatiRegije();
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
                url:"http://localhost/admin/sportLige",
                type:"POST"
            }
        });
    }

    function spremiRegije(data) {
        regije = data;
        console.log(regije);
    }
    function dohvatiRegije(){
        var stupac = 'data3';
        $.ajax({
            url: 'http://localhost/admin/sportLige/dohvatiRegije',
            method: 'POST',
            data: {stupac:stupac},
            success: function(data) {
                spremiRegije(data);
            }
        });
    }

    function spremiSportove(data) {
        sportovi = data;
        console.log(sportovi);
    }
    function dohvatiSportove(){
        var stupac = 'data2';
        $.ajax({
            url: 'http://localhost/admin/sportLige/dohvatiSportove',
            method: 'POST',
            data: {stupac:stupac},
            success: function(data) {
                spremiSportove(data);
            }
        });
    }

    $('#dodaj').click(function(){
        var html = '<tr>';
        html += '<td contenteditable id="data1"></td>';
        html += sportovi;
        html += regije;
        html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs">Dodaj</button></td>';
        html += '</tr>';
        $('#tablica tbody').prepend(html);
    });

    $(document).on('click', '#insert', function(){
        var naziv = $('#data1').text();
        var idSport = $('#data2').val();
        var idRegija = $('#data3').val();
        if(naziv != '' && idSport != '' && idRegija != '')
        {
            $.ajax({
                url:"http://localhost/admin/sportLige/dodaj",
                method:"POST",
                data:{naziv:naziv, idSport:idSport, idRegija:idRegija},
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
                    url:"http://localhost/admin/sportLige/obrisi",
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
                    url:"http://localhost/admin/sportLige/azuriraj",
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