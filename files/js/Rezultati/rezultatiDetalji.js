$(document).ready(function(){
    //dohvacanje ida i naziva lige i izrada href-a
    var liga = $("#linkLiga").data("value");
    const ligaArr = liga.split(",");
    var naziv = ligaArr[0].replace(/ /g,"_");
    var link = '/liga/'+naziv+'/'+Math.pow(ligaArr[1],4);
    $("#linkLiga").attr("href", link);

    var domacin = $("#linkDomacin").data("value");
    const domacinArr = domacin.split(",");
    var naziv = domacinArr[0].replace(/ /g,"_");
    var link = '/klub/'+naziv+'/'+Math.pow(domacinArr[1],4);
    $("#linkDomacin").attr("href", link);

    var gost = $("#linkGost").data("value");
    const gostArr = gost.split(",");
    var naziv = gostArr[0].replace(/ /g,"_");
    var link = '/klub/'+naziv+'/'+Math.pow(gostArr[1],4);
    $("#linkGost").attr("href", link);
});