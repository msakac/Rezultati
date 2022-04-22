$(document).ready(function() {
    dohvatiSlike('');

    function dohvatiSlike(klub) {
        var action = "fetch";
        $.ajax({
            url: "http://localhost/galerija/dohvati",
            method: "POST",
            data: {klub:klub},
            success: function (data) {
                $('#gallery').html(data);
            }
        })
    }

    $('#pretrazi').bind("change paste keyup", function() {
        $('#slike').empty();
        var trazi = $('#pretrazi').val();
        dohvatiSlike(trazi);
    });
});