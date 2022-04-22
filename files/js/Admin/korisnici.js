$(document).ready(function(){
    var uloge;
    dohvatiPodatke();
    dohvatiUloge();
    
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
                url:"http://localhost/admin/korisnici",
                type:"POST"
            }
        });
    }

    function spremiUloge(data) {
        uloge = data;
        console.log(uloge);
    }
    function dohvatiUloge(){
        var uloge = null;
        var stupac = 'data6';
        $.ajax({
            url: 'http://localhost/admin/korisnici/dohvatiUloge',
            method: 'POST',
            data: {stupac:stupac},
            success: function(data) {
                spremiUloge(data);
            }
        });
    }


    $('#dodaj').click(function(){//on click dodaj dodajemo novi red v tablicu
        var html = '<tr>';
        html += '<td contenteditable id="data1"></td>';
        html += '<td contenteditable id="data2"></td>';
        html += '<td contenteditable id="data3"></td>';
        html += '<td contenteditable id="data4"></td>';
        html += '<td contenteditable id="data5"></td>';
        //html += '<td contenteditable id="data6"></td>';
        html += uloge;
        html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs">Dodaj</button></td>';
        html += '</tr>';
        $('#tablica tbody').prepend(html);
    });


    $(document).on('click', '#insert', function(){
        var korisnickoIme = $('#data1').text();
        var email = $('#data2').text();
        var lozinka = $('#data3').text();
        var ime = $('#data4').text();
        var prezime = $('#data5').text();
        var uloga = $('#data6').val(); //ili $('#data6 option:selected').val();
        if(ime != '' && prezime != '' && korisnickoIme != '' && email != '' && lozinka != '' && uloga != '')
        {
            $.ajax({
                url:"http://localhost/admin/korisnici/dodaj",
                method:"POST",
                data:{korisnickoIme:korisnickoIme, email:email, lozinka:lozinka, ime:ime, prezime:prezime, uloga:uloga},
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
                url:"http://localhost/admin/korisnici/obrisi",
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
            url:"http://localhost/admin/korisnici/azuriraj",
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