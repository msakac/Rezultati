$(document).ready(function() {
    var liga = $("#naslov").text();
    dohvatiSveUtakmice(liga); //kao parametar šaljemo '' i onda ucitava sve utakmice
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
                data: {liga: liga},
            }
        });
    }
    $(document).on('click', '.detalji', function(){
        var id = $(this).attr("id");
        var idKript = Math.pow(id,4)
        window.location.href = 'http://localhost/rezultati/detalji/'+idKript;
    });
    var liga = $("#linkLiga").data("id");
    const ligaArr = liga.split(",");
    var naziv = ligaArr[0].replace(/ /g,"_");
    var link = '/liga/'+naziv+'/'+Math.pow(ligaArr[1],4);
    $("#linkLiga").attr("href", link);
});