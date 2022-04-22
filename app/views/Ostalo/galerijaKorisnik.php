<script src="/files/js/Galerija/galerijaKorisnik.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<section id="home">
    <div class="inner-width" style="max-width: 1000px">
        <div class="content">
            <h3>Moja galerija</h3>
            <hr>
            <button type="button" name="add" id="add" class="btn btn-success">Dodaj sliku</button>
            <div id="poruka" style="padding-top: 10px; padding-bottom: 10px"></div>
            <table id="tablicaSlika" class="content-table" style="width: 100%">
        </div>
    </div>
</section>
</body>
<div id="imageModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 style="color:black; text-align: left">Predaj sliku</h4>
            </div>
            <div class="modal-body">
                <form id="image_form" method="post" enctype="multipart/form-data">
                    <div class="col-md-3" style="margin-top: 15px">
                        <select class="form-control" id="klub" style="width: auto;">
                            <option value="">Odaberi klub</option>
                            <repeat group="{{@klubovi}}" key="{{@id}}" value="{{@klub}}">
                                <repeat group="{{@klub}}" value="{{@naziv}}">
                                    <option value="{{@id}}">{{@naziv}}</option>
                                </repeat>
                            </repeat>
                        </select>
                    </div>
                    <p><label>Odaberi sliku</label>
                        <input type="file" name="slika" id="slika" style="color: black; padding-left: 150px"/></p><br />
                    <input type="hidden" name="image_id" id="image_id" />
                    <input type="submit" name="insert" id="insert" value="Predaj" class="btn btn-info" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
            </div>
        </div>
    </div>
</div>
</html>
