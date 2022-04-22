$(document).ready(function(){
    dohvatiSlike();
    function dohvatiSlike(){
        var action = "fetch";
        $.ajax({
            url:"http://localhost/galerija/korisnik/dohvati",
            method:"POST",
            success:function(data)
            {
                $('#tablicaSlika').html(data);
            }
        })
    }
    $('#add').click(function(){
        $('#imageModal').modal('show');
        $('#image_form')[0].reset();
    });

    $('#image_form').submit(function(event){
        event.preventDefault();
        var image_name = $('#slika').val();

        var klub = $('#klub').val();
        console.log(klub);
        if(image_name == '' || klub == '')
        {
            alert("Odaberite sliku i klub");
            return false;
        }
        else
        {
            var extension = $('#slika').val().split('.').pop().toLowerCase();
            if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
            {
                alert("Format nije podržan");
                $('#slika').val('');
                return false;
            }
            else
            {
                var formData = new FormData(this);
                formData.append('klub',klub);
                $.ajax({
                    url:"http://localhost/galerija/korisnik/predaj",
                    method:"POST",
                    data:formData,
                    contentType:false,
                    processData:false,
                    success:function(data)
                    {
                        $('#poruka').html('<div class="alert alert-success">'+data+'</div>');
                        dohvatiSlike();
                        $('#image_form')[0].reset();
                        $('#imageModal').modal('hide');
                    }
                });
                setInterval(function(){
                    $('#poruka').html('');
                }, 5000);
            }
        }
    });

    $(document).on('click', '.delete', function(){
        var id = $(this).attr("id");
        var action = "delete";
        if(confirm("Jeste li sigurni da želite izbrisati sliku?"))
        {
            $.ajax({
                url:"http://localhost/galerija/korisnik/obrisi",
                method:"POST",
                data:{id:id},
                success:function(data)
                {
                    $('#poruka').html('<div class="alert alert-success">'+data+'</div>');
                    dohvatiSlike();
                }
            });
            setInterval(function(){
                $('#poruka').html('');
            }, 5000);
        }
        else
        {
            return false;
        }
    });
});