$(document).ready(function() {
    dohvatiSveUtakmice(''); //kao parametar šaljemo '' i onda ucitava sve utakmice
    function dohvatiSveUtakmice(liga) {
        //$('.content-table').hide();
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
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "http://localhost/rezultati",
                type: "POST",
                data:{liga:liga},
            }
        });
    }
    $(document).on('click', '.detalji', function(){
        var id = $(this).attr("id");
        var idKript = Math.pow(id,4)
        window.location.href = 'http://localhost/rezultati/detalji/'+idKript;
    }); //preusmjeravanje
   /*$('#prikazi a').click(function(){ //kad pritisnemo sub anchor na sidebaru, brisemo tablicu i zovemo funkciju za prikaz utakmica
       var liga = this.text;
       $('#naslov').html(liga);
       if(liga != '')
       {
           $('#tablica').DataTable().destroy();
           dohvatiSveUtakmice(liga);
       }
    });*/
    /*$(document).on('click', '.detalji', function(){
        var id = $(this).attr("id");
        var idKript = Math.pow(id,4)
        window.location.href = 'http://localhost/rezultati/detalji/'+idKript;
    }); //preusmjeravanje*/
});