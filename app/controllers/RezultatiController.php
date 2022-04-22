<?php
class RezultatiController extends Controller{
    public function beforeroute()
    {
    }

    function render(){
        $this->f3->set('title', 'Rezultati');
        $template = new Template();
        $this->DohvatiLigeISportove();
        $this->f3->set('stranica', 'Rezultati/rezultatiPocetna.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function detaljiRender(){
        $this->f3->set('title', 'Rezultati');
        $idUtakmice = pow($this->f3->get('PARAMS.utakmica'),1/4);
        $this->DohvatiLigeISportove();
        $this->DohvatiDetaljeUtakmice($idUtakmice);
        $this->DohvatiEkipuDomacina($idUtakmice);
        $this->DohvatiEkipuGosta($idUtakmice);
        $template = new Template();
        $this->f3->set('stranica', 'Rezultati/rezultatiDetalji.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function ligaRezultatiRender(){
        $liga = $this->f3->get("PARAMS.liga");
        $this->f3->set('title', $liga);
        $this->DohvatiLigeISportove();
        $this->DohvatiIdLige($liga);
        $template = new Template();
        $this->f3->set('stranica', 'Rezultati/rezlutatiPoLigama.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function DohvatiLigeISportove(){
        $rezlutatArr = array();

        $upitSport = "SELECT naziv FROM sport";
        $sportovi = $this->db->exec($upitSport); //dohvacam sve sportove

        foreach ($sportovi as $sport){ //iteriram se kroz sportove
            $upitSportLige = "SELECT sportliga.naziv FROM sportliga, sport 
            WHERE sportliga.idSport = sport.id AND sport.naziv = ?";
            $sportLige = $this->db->exec($upitSportLige, $sport['naziv']);//dohvacam sve lige za svaki sport
            foreach ($sportLige as $liga){ //iteriram se kroz lige
                $rezlutatArr[$sport['naziv']][] = $liga['naziv']; //dadajem ligu u niz z kljucem sport
            }
        }
        $this->f3->set('sportLige', $rezlutatArr);
    } //dohvaca sportove i lige koje su u pojedinom sportu za sidebar menu

    function dohvatiUtakmice(){
        if(($_POST['liga'] != '')){
            $this->dohvatiLigaUtakmice();
        }else{
            $this->dohvatiSveUtakmice();
        }
        //$this->dohvatiSveUtakmice();
    } //dohvaca utakmice ovisno o tome da li je pritisnuta liga ili samo rezlutati
    function dohvatiSveUtakmice(){

        $stupci = array('id', 'datumVrijeme', 'sportliga.naziv', 'stadion.naziv', 'domacin.naziv', 'gost.naziv');

        $upit = 'SELECT utakmica.id, utakmica.datumVrijeme, utakmica.idSportLiga, 
                             utakmica.idStadion, utakmica.idDomacin, utakmica.poeniDomacin, 
                             utakmica.idGost, utakmica.poeniGost, sportliga.naziv AS liga, 
                             stadion.naziv AS stadion, domacin.naziv AS domacin, gost.naziv AS gost 
                             FROM utakmica,sportliga, stadion, klub AS gost, klub AS domacin 
                             WHERE utakmica.idSportLiga = sportliga.id AND utakmica.idStadion = stadion.id 
                             AND utakmica.idDomacin = domacin.id AND utakmica.idGost = gost.id';

        if (isset($_POST["search"]["value"]))
        {
            $upit .= '
                AND(datumVrijeme LIKE "%' . $_POST["search"]["value"] . '%"
                OR sportliga.naziv LIKE "%' . $_POST["search"]["value"] . '%"
                OR stadion.naziv LIKE "%' . $_POST["search"]["value"] . '%"
                OR domacin.naziv LIKE "%' . $_POST["search"]["value"] . '%"
                OR gost.naziv LIKE "%' . $_POST["search"]["value"] . '%")';
        }
        if (isset($_POST["order"])) {
            $upit .= 'ORDER BY ' . $stupci[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $upit .= 'ORDER BY datumVrijeme DESC ';
        }
        $upit1 = '';

        if ($_POST["length"] != -1) {
            $upit1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $broj_redaka = $this->db->exec($upit);

        $rezlutat = $this->db->exec($upit . $upit1);
        $podaci = array();


        $datumVrijemeSad = date("Y-m-d H:i:s");
        foreach ($rezlutat as $red) {
            //Za ispis vremena
            $to_time =strtotime($red['datumVrijeme']);
            $from_time = strtotime($datumVrijemeSad);
            $minuta = round(($from_time - $to_time   ) / 60). " min";
            if(intval($minuta) < 0){
                $boja = 'white';
                $status = "-";
            }
            else if(intval($minuta) >= 0 && intval($minuta) <= 90 ){
                //ako je vrijeme sad vece od utakmica i vreme sad manje od utakmice +90min
                $boja = "lightgreen";
                $status = $minuta;
            }else{
                $boja = "salmon";
                $status = "Kraj";
            }

            //format ispisa datuma vremena
            $datetime = new DateTime($red["datumVrijeme"]);
            $datumVrijeme = $datetime->format('d.m.Y H:i');
            $rezlutat = $red["poeniDomacin"]." : ".$red["poeniGost"];

            ////
            $sub_array = array();
            $sub_array[] = '<div style="color: '.$boja.'" class="update" data-id="' . $red["id"] . '" data-column="datumVrijeme">' .$datumVrijeme  . '</div>';
            $sub_array[] = '<div style="color: '.$boja.'" class="update" data-id="' . $red["id"] . '" data-column="datumVrijeme">' .$status  . '</div>';
            $sub_array[] = '<div style="color: '.$boja.'" class="update" data-id="' . $red["id"] . '" data-column="idDomacin">' . $red["domacin"] . '</div>';
            $sub_array[] = '<div style="color: '.$boja.'" class="update" data-id="' . $red["id"] . '" data-column="poeniDomacin">' . $rezlutat . '</div>';
            $sub_array[] = '<div style="color: '.$boja.'" class="update" data-id="' . $red["id"] . '" data-column="idGost">' . $red["gost"] . '</div>';
            $sub_array[] = '<button type="button" name="detalji" class="btn btn-success btn-xs detalji" id="' . $red["id"] . '">Detalji</button>';
            $podaci[] = $sub_array;
        }

        $total = $this->db->exec($upit);

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);
    } //dohvaca sve utakmice
    function dohvatiLigaUtakmice(){
        $stupci = array('id', 'datumVrijeme', 'sportliga.naziv', 'stadion.naziv', 'domacin.naziv', 'gost.naziv');
    $upit = "SELECT utakmica.id, utakmica.datumVrijeme, utakmica.idSportLiga, 
                             utakmica.idStadion, utakmica.idDomacin, utakmica.poeniDomacin, 
                             utakmica.idGost, utakmica.poeniGost, sportliga.naziv AS liga, 
                             stadion.naziv AS stadion, domacin.naziv AS domacin, gost.naziv AS gost 
                             FROM utakmica,sportliga, stadion, klub AS gost, klub AS domacin 
                             WHERE utakmica.idSportLiga = sportliga.id AND utakmica.idStadion = stadion.id 
                             AND utakmica.idDomacin = domacin.id AND utakmica.idGost = gost.id AND sportliga.naziv = ?";
        if (isset($_POST["search"]["value"]))
        {
            $upit .= '
                AND(datumVrijeme LIKE "%' . $_POST["search"]["value"] . '%"
                OR sportliga.naziv LIKE "%' . $_POST["search"]["value"] . '%"
                OR stadion.naziv LIKE "%' . $_POST["search"]["value"] . '%"
                OR domacin.naziv LIKE "%' . $_POST["search"]["value"] . '%"
                OR gost.naziv LIKE "%' . $_POST["search"]["value"] . '%")';
        }
        if (isset($_POST["order"])) {
            $upit .= 'ORDER BY ' . $stupci[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $upit .= 'ORDER BY datumVrijeme DESC ';
        }
        $upit1 = '';

        if ($_POST["length"] != -1) {
            $upit1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $broj_redaka = $this->db->exec($upit, array($_POST["liga"]));

        $rezlutat = $this->db->exec($upit . $upit1, array($_POST["liga"]));
        $podaci = array();


        $datumVrijemeSad = date("Y-m-d H:i:s");
        foreach ($rezlutat as $red) {
            //Za ispis vremena
            $to_time =strtotime($red['datumVrijeme']);
            $from_time = strtotime($datumVrijemeSad);
            $minuta = round(($from_time - $to_time   ) / 60). " min";
            if(intval($minuta) < 0){
                $boja = 'white';
                $status = "-";
            }
            else if(intval($minuta) >= 0 && intval($minuta) <= 90 ){
                //ako je vrijeme sad vece od utakmica i vreme sad manje od utakmice +90min
                $boja = "lightgreen";
                $status = $minuta;
            }else{
                $boja = "salmon";
                $status = "Kraj";
            }

            //format ispisa datuma vremena
            $datetime = new DateTime($red["datumVrijeme"]);
            $datumVrijeme = $datetime->format('d.m.Y H:i');
            $rezlutat = $red["poeniDomacin"]." : ".$red["poeniGost"];

            ////
            $sub_array = array();
            $sub_array[] = '<div style="color: '.$boja.'" class="update" data-id="' . $red["id"] . '" data-column="datumVrijeme">' .$datumVrijeme  . '</div>';
            $sub_array[] = '<div style="color: '.$boja.'" class="update" data-id="' . $red["id"] . '" data-column="datumVrijeme">' .$status  . '</div>';
            $sub_array[] = '<div style="color: '.$boja.'" class="update" data-id="' . $red["id"] . '" data-column="idDomacin">' . $red["domacin"] . '</div>';
            $sub_array[] = '<div style="color: '.$boja.'" class="update" data-id="' . $red["id"] . '" data-column="poeniDomacin">' . $rezlutat . '</div>';
            $sub_array[] = '<div style="color: '.$boja.'" class="update" data-id="' . $red["id"] . '" data-column="idGost">' . $red["gost"] . '</div>';
            $sub_array[] = '<button type="button" name="detalji" class="btn btn-success btn-xs detalji" id="' . $red["id"] . '">Detalji</button>';
            $podaci[] = $sub_array;
        }

        $total = $this->db->exec($upit,array($_POST["liga"]));

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);


    } //dohvaca utakmice koje su u nekoj ligi
    function DohvatiDetaljeUtakmice($id){
        $upit = "SELECT utakmica.id,sport.naziv AS sport, regija.naziv AS regija, sportliga.naziv AS liga, sportliga.id as ligaId, 
                utakmica.datumVrijeme, stadion.naziv AS stadion, stadion.mjesto AS mjesto, utakmica.idDomacin, 
                utakmica.idGost, utakmica.poeniDomacin, utakmica.poeniGost, domacin.naziv as domacin, gost.naziv AS gost, domacin.id as domacinId, gost.id AS gostId
                FROM sport, sportliga, stadion, regija, klub AS gost, klub AS domacin, utakmica WHERE  utakmica.id = ? 
                AND utakmica.idSportLiga = sportliga.id AND utakmica.idStadion = stadion.id AND utakmica.idDomacin = domacin.id 
                AND utakmica.idGost = gost.id AND sport.id = sportliga.idSport  AND sportliga.idRegija = regija.id";
        $rezlutat = $this->db->exec($upit, array($id));
        foreach ($rezlutat as $red){

            $datetime = new DateTime($red["datumVrijeme"]);
            $datumVrijeme = $datetime->format('d.m.Y H:i');
            $this->f3->set('datumVrijeme', $datumVrijeme);
            $ligaNazivId = $red["liga"] . "," . $red["ligaId"];
            $domacinNazivId = $red["domacin"] . "," . $red["domacinId"];
            $gostNazivId = $red["gost"] . "," . $red["gostId"];
            $this->f3->set('ligaNazivId', $ligaNazivId);
            $this->f3->set('domacinNazivId', $domacinNazivId);
            $this->f3->set('gostNazivId', $gostNazivId);

            $this->f3->set('sport', $red['sport']);
            $this->f3->set('regija', $red['regija']);
            $this->f3->set('liga', $red['liga']);
            $this->f3->set('stadion', $red['stadion']);
            $this->f3->set('mjesto', $red['mjesto']);
            $this->f3->set('gost', $red['gost']);
            $this->f3->set('domacin', $red['domacin']);
            $this->f3->set('domacinPoeni', $red['poeniDomacin']);
            $this->f3->set('gostPoeni', $red['poeniGost']);
        }
    } //dohvaca sve detalje za neku utakmicu
    function DohvatiEkipuDomacina($id){
        $domacinEkipaArr = array();
        $upitDomacin = "SELECT igrac.Ime, igrac.id, igrac.Prezime, pozicija.Naziv 
                        FROM igrac, pozicija, utakmica, klub 
                        WHERE utakmica.id = ? AND utakmica.idDomacin = klub.id 
                        AND klub.id = igrac.idKlub AND igrac.idPozicija = pozicija.id";
        $rezlutat = $this->db->exec($upitDomacin, array($id));

        foreach ($rezlutat as $red){
            $domacinEkipaArr[$red["id"]][] = $red["Ime"];
            $domacinEkipaArr[$red["id"]][] = $red["Prezime"];
            $domacinEkipaArr[$red["id"]][] = $red["Naziv"];
        }
        $this->f3->set('domacinEkipa', $domacinEkipaArr);
    } //dohvaca momcad domacina
    function DohvatiEkipuGosta($id){
        $gostEkipaArr = array();
        $upitGost = "SELECT igrac.Ime, igrac.id, igrac.Prezime, pozicija.Naziv FROM igrac, pozicija, utakmica, klub 
                        WHERE utakmica.id = ? AND utakmica.idGost = klub.id 
                        AND klub.id = igrac.idKlub AND igrac.idPozicija = pozicija.id";
        $rezlutat = $this->db->exec($upitGost, array($id));

        foreach ($rezlutat as $red){
            $gostEkipaArr[$red["id"]][] = $red["Ime"];
            $gostEkipaArr[$red["id"]][] = $red["Prezime"];
            $gostEkipaArr[$red["id"]][] = $red["Naziv"];
        }
        $this->f3->set('gostEkipa', $gostEkipaArr);
    } //dohvaca momcad gosta
    function DohvatiIdLige($nazivLige){
        $upit = "SELECT * FROM sportliga WHERE naziv = ?";
        $rezlutat = $this->db->exec($upit, array($nazivLige));
        foreach ($rezlutat as $red) {
            $ligaNazivId = $red["naziv"] . "," . $red["id"];
            $this->f3->set('ligaNazivId', $ligaNazivId);
        }
    }
}