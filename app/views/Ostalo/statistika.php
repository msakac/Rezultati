<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="/files/js/Rezultati/statistika.js"></script><!-- Google chart library-->
<section id="home">
    <div class="inner-width">
        <div class="content">
            <div style="width: 50%; float: left; padding-right: 10px">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-9">
                                <h3 class="panel-title" style="text-align: left">Bodovi i utakmice</h3>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="liga">
                                    <option value="">Odaberi ligu</option>
                                    <repeat group="{{@lige}}" key="{{@id}}" value="{{@liga}}">
                                        <repeat group="{{@liga}}" value="{{@naziv}}">
                                            <option value="{{@id}}">{{@naziv}}</option>
                                        </repeat>
                                    </repeat>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="grafBodovi" style=" height: 450px; width: auto; margin: 0 auto;"></div>
                    </div>
                </div>
            </div>
                <div style="width: 50%; float: left; padding-left: 10px">
                <div class="panel panel-default">
                    <div class="panel-heading" style="height: 54.8px">
                        <div class="row">
                            <div class="col-md-9">
                                <h3 class="panel-title" style="text-align: left">Poeni</h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="grafPoeni" style="width: 800px; height: 450px; width: auto; margin: 0 auto"></div>
                    </div>
                </div>
                </div>
        </div>
    </div>
</section>
</body>
</html>