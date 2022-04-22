google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback();

function dohvatiPodatkeZaLigu(liga, naziv) {
    var naslov = naziv;
    $.ajax({
        url:"http://localhost/statistika",
        method:"POST",
        data:{liga:liga},
        dataType:"JSON",
        success:function(data)
        {
            prikaziGrafBodova(data, naslov);
            prikaziGrafPoena(data, naslov);
        }
    });
}
function prikaziGrafBodova(podaci, nazivLige) {
    var naslov = nazivLige+ ' bodovno stanje i broj utakmica';
    var jsonData = podaci;
    var podaciGraf = new google.visualization.DataTable();
    podaciGraf.addColumn('string', 'Klub');
    podaciGraf.addColumn('number', 'Bodovi');
    podaciGraf.addColumn('number', 'Mečevi');
    $.each(jsonData, function(i, jsonData){
        var klub = jsonData.klub;
        var bodovi = jsonData.bodovi;
        var poeniPrimljeni = parseInt(jsonData.brojUtakmica);
        podaciGraf.addRows([[klub,bodovi, poeniPrimljeni]]);
    });
    var options = {
        colors: ['blue','purple'],
        title:naslov,
        titleTextStyle: {color: 'blue'},
        hAxis: {
            title: "Klubovi"
        },
        vAxis: {
            title: 'Bodovi',

        }
    };
    var chart = new google.visualization.ColumnChart(document.getElementById('grafBodovi'));
    chart.draw(podaciGraf, options);
}
function prikaziGrafPoena(podaci, nazivLige) {
    var naslov = nazivLige+ ' postignuti i primljeni poeni po klubu';
    var jsonData = podaci;
    var podaciGraf = new google.visualization.DataTable();
    podaciGraf.addColumn('string', 'Klub');
    podaciGraf.addColumn('number', 'Pogoci');
    podaciGraf.addColumn('number', 'Primljeni');
    $.each(jsonData, function(i, jsonData){
        var klub = jsonData.klub;
        var poeniOsvojeni = jsonData.poeniOsvojeni;
        var poeniPrimljeni = jsonData.poeniPrimljeni;
        podaciGraf.addRows([[klub, poeniOsvojeni,poeniPrimljeni]]);
    });
    var options = {
        colors: ['green','red'],
        title:naslov,
        titleTextStyle: {color: 'red'},
        hAxis: {
            title: "Klubovi",
        },
        vAxis: {
            title: 'Poeni',
            is3D:true
        }
    };
    var chart = new google.visualization.ColumnChart(document.getElementById('grafPoeni'));
    chart.draw(podaciGraf, options);
}

$(document).ready(function(){
    $('#liga').change(function(){
        var liga = $(this).val();
        var ligaNaziv = $('#liga option:selected').text();
        console.log(liga);
        if(liga != '')
        {
            dohvatiPodatkeZaLigu(liga, ligaNaziv);
        }
    });

});
