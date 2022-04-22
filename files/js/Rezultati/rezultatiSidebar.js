$(document).ready(function(){
    $('#prikazi a').click(function(){ //kad pritisnemo sub anchor na sidebaru, brisemo tablicu i zovemo funkciju za prikaz utakmica
        var liga = this.text;
        console.log(liga);
        if(liga != '')
        {
            window.location.href = 'http://localhost/rezultati/'+liga;
        }
    });
});