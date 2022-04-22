<?php
class ModController extends Controller{
    function beforeroute()
    {
        if ($this->f3->get('SESSION.uloga') < 2) { //ako nije moderator il admin
            $this->f3->reroute('/');
        }
        }
    function render()
    {
        $this->f3->set('title', 'Moderator');
        $template = new Template();
        $this->f3->set('stranica', 'Moderator/pocetna.php');
        echo $template->render('Predlozak/predlozak.php');
    }

    //CRUD Pozicija
    function pozicijeRender()
    {
        $this->f3->set('title', 'Moderator');
        $template = new Template();
        $this->f3->set('stranica', 'Moderator/pozicije.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function dohvatiPozicije()
    {
        $stupci = array('id', 'Naziv');

        $upit = "SELECT * FROM pozicija ";
        if (isset($_POST["search"]["value"]))
        {
            $upit .= '
                WHERE id LIKE "%' . $_POST["search"]["value"] . '%" 
                OR Naziv LIKE "%' . $_POST["search"]["value"] . '%" 
                ';
        }
        if (isset($_POST["order"])) {
            $upit .= 'ORDER BY ' . $stupci[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $upit .= 'ORDER BY id DESC ';
        }
        $upit1 = '';

        if ($_POST["length"] != -1) {
            $upit1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $broj_redaka = $this->db->exec($upit);

        $rezlutat = $this->db->exec($upit.$upit1);
        $podaci = array();

        foreach ($rezlutat as $red) {
            $sub_array = array();
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="id">' . $red["id"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="Naziv">' . $red["Naziv"] . '</div>';
            $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $red["id"] . '">Obriši</button>';
            $podaci[] = $sub_array;
        }

        $total = $this->db->exec("SELECT * FROM pozicija ");

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);
    }
    function dodajPoziciju(){
        $upit = 'INSERT INTO pozicija(Naziv) VALUES(?)';
        $dodaj = $this->db->exec($upit,array($_POST["naziv"]));
        if($dodaj){
            $this->dnevnikRada($upit);
            echo 'Nova pozicija kreirana';
        }
    }
    function obrisiPoziciju(){
        $upit ='DELETE FROM pozicija WHERE id = ?';
        $obrisi = $this->db->exec($upit, array($_POST["id"]));
        if($obrisi)
        {
            $this->dnevnikRada($upit);
            echo 'Pozicija izbrisana';
        }
    }
    function azurirajPoziciju(){
        $upit="UPDATE pozicija SET " . $_POST["stupac"] . "='" . $_POST["vrijednost"] . "' WHERE id = '" . $_POST["id"] . "'";
        $azuriraj = $this->db->exec($upit);
        if($azuriraj){
            $this->dnevnikRada($upit);
            echo 'Pozicija azurirana';
        }

    }

    //CRUD Stadion
    function stadioniRender()
    {
        $this->f3->set('title', 'Moderator');
        $template = new Template();
        $this->f3->set('stranica', 'Moderator/stadioni.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function dohvatiStadione()
    {
        $stupci = array('id', 'naziv', 'mjesto');

        $upit = "SELECT * FROM stadion ";

        if (isset($_POST["search"]["value"]))
        {
            $upit .= '
                WHERE id LIKE "%' . $_POST["search"]["value"] . '%" 
                OR naziv LIKE "%' . $_POST["search"]["value"] . '%" 
                OR mjesto LIKE "%' . $_POST["search"]["value"] . '%"
                ';
        }
        if (isset($_POST["order"])) {
            $upit .= 'ORDER BY ' . $stupci[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $upit .= 'ORDER BY id DESC ';
        }
        $upit1 = '';

        if ($_POST["length"] != -1) {
            $upit1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $broj_redaka = $this->db->exec($upit);

        $rezlutat = $this->db->exec($upit.$upit1);
        $podaci = array();

        foreach ($rezlutat as $red) {
            $sub_array = array();
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="id">' . $red["id"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="naziv">' . $red["naziv"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="mjesto">' . $red["mjesto"] . '</div>';
            $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $red["id"] . '">Obriši</button>';
            $podaci[] = $sub_array;
        }

        $total = $this->db->exec("SELECT * FROM stadion ");

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);
    }
    function dodajStadion(){
        $upit = 'INSERT INTO stadion(naziv,mjesto) VALUES(?,?)';
        $dodaj = $this->db->exec($upit,array($this->f3->get('POST.naziv'),$_POST["mjesto"]));
        if($dodaj){
            $this->dnevnikRada($upit);
            echo 'Novi stadion kreiran';
        }
    }
    function obrisiStadion(){
        $upit ='DELETE FROM stadion WHERE id = ?';
        $obrisi = $this->db->exec($upit, array($_POST["id"]));
        if($obrisi)
        {
            $this->dnevnikRada($upit);
            echo 'Stadion izbrisan';
        }
    }
    function azurirajStadion(){
        $upit="UPDATE stadion SET " . $_POST["stupac"] . "='" . $_POST["vrijednost"] . "' WHERE id = '" . $_POST["id"] . "'";
        $azuriraj = $this->db->exec($upit);
        if($azuriraj){
            $this->dnevnikRada($upit);
            echo 'Stadion ažuriran';
        }

    }

    //CRUD Klub, dohvacanje sportova, stadiona i regija za select
    function kluboviRender()
    {
        $this->f3->set('title', 'Moderator');
        $template = new Template();
        $this->f3->set('stranica', 'Moderator/klubovi.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function kluboviDohvatiSportove(){
        $rezlutat = '';
        if(($this->f3->get('SESSION.uloga')) < 3){
            $upit = "select sport.id, sport.naziv from sport, sportmoderator 
                    where sportmoderator.korisnik_id = ? and sportmoderator.sport_id = sport.id";
            $rezlutat = $this->db->exec($upit, array($this->f3->get('SESSION.id')));
        }else{
            $upit = "select sport.id, sport.naziv from sport";
            $rezlutat = $this->db->exec($upit);
        }

        $sportovi = '';
        $sportovi .= '<td><select id ="'.$_POST["stupac"].'" class="form-control selectpicker" style="width: 100px" data-live-search="true"><option value="">Sport</option>';
        foreach ($rezlutat as $red){
            $red["sportIspis"] =$red["naziv"]." (".$red["id"].")";
            $sportovi .='<option value="'.$red["id"].'">'.$red["sportIspis"].'</option>';
        }
        $sportovi .='</select></td>';
        echo $sportovi;
    }
    function kluboviDohvatiStadione(){
        $upit = "SELECT * FROM stadion";
        $rezlutat = $this->db->exec($upit);
        $stadioni = '';
        $stadioni .= '<td><select id ="'.$_POST["stupac"].'" class="form-control selectpicker" style="width: 100px" data-live-search="true"><option value="">Stadion</option>';
        foreach ($rezlutat as $red){
            $red["stadionIspis"] =$red["naziv"]." (".$red["id"].")";
            $stadioni .='<option value="'.$red["id"].'">'.$red["stadionIspis"].'</option>';
        }
        $stadioni .='</select></td>';
        echo $stadioni;
    }
    function kluboviDohvatiRegije(){
        $upit = "SELECT * FROM regija";
        $rezlutat = $this->db->exec($upit);
        $regije = '';
        $regije .= '<td><select id ="'.$_POST["stupac"].'" class="form-control selectpicker" style="width: 100px" data-live-search="true"><option value="">Regija</option>';
        foreach ($rezlutat as $red){
            $red["regijaIspis"] =$red["naziv"]." (".$red["id"].")";
            $regije .='<option value="'.$red["id"].'">'.$red["regijaIspis"].'</option>';
        }
        $regije .='</select></td>';
        echo $regije;
    }
    function dohvatiKlubove()
    {
        //provjera da li je korisnik moderator i dohvaca samo klubove koji on moderira
        if($this->f3->get('SESSION.uloga') < 3){
            $upitProvjera = 'SELECT klub.id as id, klub.naziv as klub, klub.sport_id as idSport, 
                                klub.regija_id as idRegija, klub.idStadion as idStadion, 
                                stadion.naziv as stadion, regija.naziv as regija, sport.naziv as sport,
                                sportmoderator.sport_id, sportmoderator.korisnik_id 
                                FROM klub,regija,stadion,sport, sportmoderator 
                                WHERE klub.sport_id = sport.id AND klub.regija_id=regija.id AND klub.idStadion = stadion.id 
                                AND sportmoderator.sport_id = klub.sport_id AND sportmoderator.korisnik_id = '.$this->f3->get('SESSION.id');
        }else{
            $upitProvjera = 'SELECT klub.id as id, klub.naziv as klub, klub.sport_id as idSport, klub.regija_id as idRegija, 
                 klub.idStadion as idStadion, stadion.naziv as stadion, regija.naziv as regija, sport.naziv as sport 
                 FROM klub,regija,stadion,sport WHERE klub.sport_id = sport.id AND klub.regija_id=regija.id AND klub.idStadion = stadion.id';
        }

        $stupci = array('klub.id', 'klub.naziv', 'stadion.naziv', 'regija.naziv', 'sport.naziv');

        $upit = $upitProvjera;

        if (isset($_POST["search"]["value"]))
        {
            $upit .= '
                AND(klub.id LIKE "%' . $_POST["search"]["value"] . '%"
                OR klub.naziv LIKE "%' . $_POST["search"]["value"] . '%" 
                OR stadion.naziv LIKE "%' . $_POST["search"]["value"] . '%"
                OR regija.naziv LIKE "%' . $_POST["search"]["value"] . '%"
                OR sport.naziv LIKE "%' . $_POST["search"]["value"] . '%")';
        }
        if (isset($_POST["order"])) {
            $upit .= 'ORDER BY ' . $stupci[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $upit .= 'ORDER BY klub.id DESC ';
        }
        $upit1 = '';

        if ($_POST["length"] != -1) {
            $upit1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $broj_redaka = $this->db->exec($upit);

        $rezlutat = $this->db->exec($upit.$upit1);
        $podaci = array();

        foreach ($rezlutat as $red) {
            $red["stadionIspis"] =$red["stadion"]." (".$red["idStadion"].")";
            $red["regijaIspis"] =$red["regija"]."(".$red["idRegija"].")";
            $red["sportIspis"] =$red["sport"]."(".$red["idSport"].")";
            $sub_array = array();
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="id">' . $red["id"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="naziv">' . $red["klub"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="idStadion">' . $red["stadionIspis"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="regija_id">' . $red["regijaIspis"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="sport_id">' . $red["sportIspis"] . '</div>';
            $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $red["id"] . '">Obriši</button>';
            $podaci[] = $sub_array;
        }

        $total = $this->db->exec($upitProvjera);

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);
    }
    function dodajKlub(){
        if($this->f3->get('SESSION.uloga') < 3){
            $upit = 'SELECT * FROM sportmoderator WHERE sport_id = ? AND korisnik_id = ?';
            $rezlutat = $this->db->exec($upit, array($_POST["sport"], $this->f3->get('SESSION.id')));
            if(count($rezlutat)<1){
                echo 'Niste moderator sporta '.$_POST["sport"].' !';
                exit();
            }
        }
        $upit = 'INSERT INTO klub(id,naziv,idStadion,regija_id,sport_id) VALUES(?,?,?,?,?)';
        $dodaj = $this->db->exec($upit,array($_POST["id"],$_POST["naziv"],$_POST["stadion"],$_POST["regije"],$_POST["sport"]));
        if($dodaj){
            $this->dnevnikRada($upit);
            echo 'Novi klub kreiran';
        }
    }
    function obrisiKlub(){
        $upit ='DELETE FROM klub WHERE id = ?';
        $obrisi = $this->db->exec($upit, array($_POST["id"]));
        if($obrisi)
        {
            $this->dnevnikRada($upit);
            echo 'Klub izbrisan';
        }
    }
    function azurirajKlub(){
        $upit = "UPDATE klub SET " . $_POST["stupac"] . "='" . $_POST["vrijednost"] . "' WHERE id = '" . $_POST["id"] . "'";
        $azuriraj = $this->db->exec($upit);
        if ($azuriraj) {
            $this->dnevnikRada($upit);
            echo 'Klub ažuriran';
        }
    }

    //CRUD Klub lige
    function klubligeRender()
    {
        $this->f3->set('title', 'Moderator');
        $template = new Template();
        $this->f3->set('stranica', 'Moderator/klublige.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function klubligeDohvatiKlubove()
    {
        $upitDohvatiSport = "SELECT sportliga.idSport, sportliga.idRegija FROM sportliga WHERE sportliga.id = ?";
        $rezlutatDohvatiSport = $this->db->exec($upitDohvatiSport, array($_POST["liga"]));


        $sportid ='';
        $regijaid ='';
        foreach($rezlutatDohvatiSport as $red){
            $sportid = $red["idSport"];
            $regijaid =$red["idRegija"];
        }

        $upit = "SELECT klub.naziv, klub.id FROM klub WHERE klub.sport_id = ? AND klub.regija_id = ?";
        //$upit = "SELECT klub.naziv, klub.id FROM klub WHERE klub.sport_id = ?";
        $rezlutat = $this->db->exec($upit, array($sportid, $regijaid));
        //$rezlutat = $this->db->exec($upit, array($sportid));
        $klubovi = '';
        //$klubovi .= '<td><select id ="' . $_POST["stupac"] . '" class="form-control selectpicker" data-live-search="true"><option value="">Odaberi klub</option>';
        foreach ($rezlutat as $red) {
            $red["kluboviIspis"] = $red["naziv"] . " (" . $red["id"] . ")";
            $klubovi .= '<option value="' . $red["id"] . '">' . $red["kluboviIspis"] . '</option>';
        }
        //$klubovi .= '</select></td>';
        echo $klubovi;
    } //dohvacam klubove koji su u istom sportu i regiji kak i liga
    //da bi mogel dodavati europske klubove v npr CL moram napraviti da jen klub more biti u vise regija tj jos jenu tablicu
    function klubligeDohvatiLige(){
        $rezlutat = '';
        if(($this->f3->get('SESSION.uloga')) < 3){
            $upit = "select sportliga.naziv, sportliga.id from sportliga, sportmoderator 
                    where sportliga.idSport = sportmoderator.sport_id and sportmoderator.korisnik_id = ?";
            $rezlutat = $this->db->exec($upit, array($this->f3->get('SESSION.id')));
        }else{
            $upit = "select sportliga.id, sportliga.naziv from sportliga";
            $rezlutat = $this->db->exec($upit);
        }

        $lige = '';
        $lige .= '<td><select id ="'.$_POST["stupac"].'" class="form-control selectpicker" data-live-search="true"><option value="">Odaberi ligu</option>';
        foreach ($rezlutat as $red){
            $red["ligeIspis"] =$red["naziv"]." (".$red["id"].")";
            $lige .='<option value="'.$red["id"].'">'.$red["ligeIspis"].'</option>';
        }
        $lige .='</select></td>';
        echo $lige;
    }
    function dohvatiKlublige()
    {
        //provjera da li je korisnik moderator i dohvaca samo klubove koji on moderira
        if($this->f3->get('SESSION.uloga') < 3){
            $upitProvjera = 'SELECT klublige.idLiga, klublige.idKlub, sportliga.naziv as liga, klub.naziv as klub,
                             sportmoderator.sport_id, sportmoderator.korisnik_id FROM klublige, sportliga, klub, sportmoderator 
                             WHERE klublige.idLiga = sportliga.id AND klublige.idKlub = klub.id AND sportmoderator.sport_id = klub.sport_id
                             AND sportmoderator.korisnik_id =  '.$this->f3->get('SESSION.id');
        }else{
            $upitProvjera = 'SELECT klublige.idLiga, klublige.idKlub, sportliga.naziv as liga, klub.naziv as klub 
                             FROM klublige, sportliga, klub WHERE klublige.idLiga = sportliga.id AND klublige.idKlub = klub.id';
        }
        $stupci = array('sportliga.naziv', 'klub.naziv');

        $upit = $upitProvjera;

        if (isset($_POST["search"]["value"]))
        {
            $upit .= '
                AND(sportliga.naziv LIKE "%' . $_POST["search"]["value"] . '%"
                OR klub.naziv LIKE "%' . $_POST["search"]["value"] . '%")';
        }
        if (isset($_POST["order"])) {
            $upit .= 'ORDER BY ' . $stupci[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $upit .= 'ORDER BY sportliga.naziv DESC ';
        }
        $upit1 = '';

        if ($_POST["length"] != -1) {
            $upit1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $broj_redaka = $this->db->exec($upit);

        $rezlutat = $this->db->exec($upit.$upit1);
        $podaci = array();

        foreach ($rezlutat as $red) {
            $red["id"] = $red["idLiga"].",".$red["idKlub"];
            $red["ligaIspis"] =$red["liga"]." (".$red["idLiga"].")";
            $red["klubIspis"] =$red["klub"]."(".$red["idKlub"].")";
            $sub_array = array();
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="idLiga">' . $red["ligaIspis"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="idKlub">' . $red["klubIspis"] . '</div>';
            $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $red["id"] . '">Obriši</button>';
            $podaci[] = $sub_array;
        }

        $total = $this->db->exec($upitProvjera);

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);
    }
    function dodajKlublige(){
        /*if($this->f3->get('SESSION.uloga') < 3){
            $upit = 'SELECT sportmoderator.sport_id, sportmoderator.korisnik_id, sportliga.idSport, sportliga.id, klublige.idLiga
                        FROM sportmoderator, sportliga, klublige
                            WHERE klublige.idLiga = sportliga.id AND sportliga.idSport = sportmoderator.sport_id AND korisnik_id = 47 ';
            $upit2 = 'SELECT sportmoderator.sport'
            $rezlutat = $this->db->exec($upit, array($_POST["sport"], $this->f3->get('SESSION.id')));
            if(count($rezlutat)<1){
                echo 'Niste moderator sporta '.$_POST["sport"].' !';
                exit();
            }
        }*/
        $upit = 'INSERT INTO klublige(idLiga, idKlub) VALUES(?,?)';
        $dodaj = $this->db->exec($upit,array($_POST["liga"],$_POST["klub"]));
        if($dodaj){
            $this->dnevnikRada($upit);
            echo 'Klub dodan u ligu';
        }
    }
    function obrisiKlublige(){
        $separator = ",";
        $podaci = explode($separator,$_POST["id"]);
        $upit ='DELETE FROM klublige WHERE idLiga = ? AND idKlub = ?';
        $obrisi = $this->db->exec($upit, array($podaci[0], $podaci[1]));
        if($obrisi)
        {
            $this->dnevnikRada($upit);
            echo 'Klub izbrisan iz lige';
        }
    }
    function azurirajKlublige(){
        $separator = ",";
        $podaci = explode($separator,$_POST["id"]);
        $upit="UPDATE klublige SET " . $_POST["stupac"] . "='" . $_POST["vrijednost"] . "' WHERE idLiga = '" . $podaci[0] . "' AND idKlub = '".$podaci[1]."'";
        $azuriraj = $this->db->exec($upit);
        if($azuriraj){
            $this->dnevnikRada($upit);
            echo 'Ažurirana liga kluba';
        }

    }

    //CRUD Igrac, dohvaćanje klubova i pozicija za select
    function igraciRender()
    {
        $this->f3->set('title', 'Moderator');
        $template = new Template();
        $this->f3->set('stranica', 'Moderator/igraci.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function igraciDohvatiKlubove(){
        $rezlutat = '';
        if(($this->f3->get('SESSION.uloga')) < 3){
            $upit = "select klub.naziv, klub.id from klub, sportmoderator 
                    where klub.sport_id = sportmoderator.sport_id and sportmoderator.korisnik_id = ?";
            $rezlutat = $this->db->exec($upit, array($this->f3->get('SESSION.id')));
        }else{
            $upit = "select klub.id, klub.naziv from klub";
            $rezlutat = $this->db->exec($upit);
        }

        $klubovi = '';
        $klubovi .= '<td><select id ="'.$_POST["stupac"].'" class="form-control selectpicker" style="width: 100px" data-live-search="true"><option value="">Klub</option>';
        foreach ($rezlutat as $red){
            $red["klubIspis"] =$red["naziv"]." (".$red["id"].")";
            $klubovi .='<option value="'.$red["id"].'">'.$red["klubIspis"].'</option>';
        }
        $klubovi .='</select></td>';
        echo $klubovi;
    }
    function igraciDohvatiPozicije(){
        $upit = "SELECT * FROM pozicija";
        $rezlutat = $this->db->exec($upit);
        $pozicije = '';
        $pozicije .= '<td><select id ="'.$_POST["stupac"].'" class="form-control selectpicker" style="width: 100px" data-live-search="true"><option value="">Pozicija</option>';
        foreach ($rezlutat as $red){
            $red["pozicijeIspis"] =$red["Naziv"]." (".$red["id"].")";
            $pozicije .='<option value="'.$red["id"].'">'.$red["pozicijeIspis"].'</option>';
        }
        $pozicije .='</select></td>';
        echo $pozicije;
    }
    function dohvatiIgrace()
    {
        $stupci = array('igrac.id','igrac.ime', 'igrac.prezime', 'klub.naziv', 'pozicija.Naziv', 'igrac.vrijednost');

        $upit = "SELECT igrac.id, igrac.ime, igrac.prezime, igrac.idKlub, igrac.idPozicija, igrac.vrijednost, klub.naziv as klub,
                 pozicija.Naziv as pozicija FROM igrac,klub,pozicija WHERE igrac.idKlub = klub.id AND igrac.idPozicija = pozicija.id";

        if (isset($_POST["search"]["value"]))
        {
            $upit .= '
                AND(igrac.ime LIKE "%' . $_POST["search"]["value"] . '%"
                OR igrac.prezime LIKE "%' . $_POST["search"]["value"] . '%"
                OR klub.naziv LIKE "%' . $_POST["search"]["value"] . '%"
                OR pozicija.Naziv LIKE "%' . $_POST["search"]["value"] . '%"
                OR igrac.vrijednost LIKE "%' . $_POST["search"]["value"] . '%")';
        }
        if (isset($_POST["order"])) {
            $upit .= 'ORDER BY ' . $stupci[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $upit .= 'ORDER BY igrac.id DESC ';
        }
        $upit1 = '';

        if ($_POST["length"] != -1) {
            $upit1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $broj_redaka = $this->db->exec($upit);

        $rezlutat = $this->db->exec($upit.$upit1);
        $podaci = array();

        foreach ($rezlutat as $red) {
            $red["pozicijaIspis"] =$red["pozicija"]." (".$red["idPozicija"].")";
            $red["klubIspis"] =$red["klub"]."(".$red["idKlub"].")";
            $sub_array = array();
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="id">' . $red["id"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="ime">' . $red["ime"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="prezime">' . $red["prezime"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="idKlub">' . $red["klubIspis"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="idPozicija">' . $red["pozicijaIspis"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="vrijednost">' . $red["vrijednost"] . '</div>';
            $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $red["id"] . '">Obriši</button>';
            $podaci[] = $sub_array;
        }

        $total = $this->db->exec("SELECT igrac.id, igrac.ime, igrac.prezime, igrac.idKlub, igrac.idPozicija, igrac.vrijednost, klub.naziv as klub,
                 pozicija.Naziv as pozicija FROM igrac,klub,pozicija WHERE igrac.idKlub = klub.id AND igrac.idPozicija = pozicija.id");

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);
    }
    function dodajIgraca(){
        $upit = 'INSERT INTO igrac(Ime, Prezime, idKlub, idPozicija, vrijednost) VALUES(?,?,?,?,?)';
        $dodaj = $this->db->exec($upit,array($_POST["ime"],$_POST["prezime"],$_POST["klub"],$_POST["pozicija"],$_POST["vrijednost"]));
        if($dodaj){
            $this->dnevnikRada($upit);
            echo 'Novi igrač kreiran';
        }
    }
    function obrisiIgraca(){
        $upit ='DELETE FROM igrac WHERE id = ?';
        $obrisi = $this->db->exec($upit, array($_POST["id"]));
        if($obrisi)
        {
            $this->dnevnikRada($upit);
            echo 'Igrac izbrisan';
        }
    }
    function azurirajIgraca(){
        $upit="UPDATE igrac SET " . $_POST["stupac"] . "='" . $_POST["vrijednost"] . "' WHERE id = '" . $_POST["id"] . "'";
        $azuriraj = $this->db->exec($upit);
        if($azuriraj){
            $this->dnevnikRada($upit);
            echo 'Igrač ažuriran';
        }

    }

    //CRUD Utakmica
    function utakmiceRender()
    {
        $this->f3->set('title', 'Moderator');
        $template = new Template();
        $this->f3->set('stranica', 'Moderator/utakmice.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function utakmiceDohvatiKlubove()
    {
        $upit = "SELECT klub.naziv, klub.id FROM klub, klublige WHERE klublige.idKlub = klub.id and klublige.idLiga = ?";

        $rezlutat = $this->db->exec($upit, array($_POST["liga"]));
        $klubovi = '';
        foreach ($rezlutat as $red) {
            $red["kluboviIspis"] = $red["naziv"] . " (" . $red["id"] . ")";
            $klubovi .= '<option value="' . $red["id"] . '">' . $red["kluboviIspis"] . '</option>';
        }
        echo $klubovi;
    } //dohvacam klubove koji su u odabranoj ligi
    function utakmiceDohvatiLige(){
        $rezlutat = '';
        if(($this->f3->get('SESSION.uloga')) < 3){
            $upit = "select sportliga.naziv, sportliga.id from sportliga, sportmoderator 
                    where sportliga.idSport = sportmoderator.sport_id and sportmoderator.korisnik_id = ?";
            $rezlutat = $this->db->exec($upit, array($this->f3->get('SESSION.id')));
        }else{
            $upit = "select sportliga.id, sportliga.naziv from sportliga";
            $rezlutat = $this->db->exec($upit);
        }

        $lige = '';
        $lige .= '<td><select id ="'.$_POST["stupac"].'" class="form-control selectpicker" style="width: 70px" data-live-search="true"><option value="">Liga</option>';
        foreach ($rezlutat as $red){
            $red["ligeIspis"] =$red["naziv"]." (".$red["id"].")";
            $lige .='<option value="'.$red["id"].'">'.$red["ligeIspis"].'</option>';
        }
        $lige .='</select></td>';
        echo $lige;
    }
    function utakmiceDohvatiStadione(){
        $upit = "SELECT * FROM stadion";
        $rezlutat = $this->db->exec($upit);
        $stadioni = '';
        $stadioni .= '<td><select id ="'.$_POST["stupac"].'" class="form-control selectpicker" style="width: 100px"  data-live-search="true"><option value="">Stadion</option>';
        foreach ($rezlutat as $red){
            $red["stadionIspis"] =$red["naziv"]." (".$red["id"].")";
            $stadioni .='<option value="'.$red["id"].'">'.$red["stadionIspis"].'</option>';
        }
        $stadioni .='</select></td>';
        echo $stadioni;
    }
    function dohvatiUtakmice(){
        if($this->f3->get('SESSION.uloga') < 3){
            $upitProvjera = 'SELECT utakmica.id, utakmica.datumVrijeme, utakmica.idSportLiga, 
                             utakmica.idStadion, utakmica.idDomacin, utakmica.poeniDomacin, 
                             utakmica.idGost, utakmica.poeniGost, sportliga.naziv AS liga, 
                             stadion.naziv AS stadion, domacin.naziv AS domacin, gost.naziv AS gost, sportmoderator.sport_id, sportmoderator.korisnik_id 
                             FROM utakmica,sportliga, sportmoderator, stadion, klub AS gost, klub AS domacin 
                             WHERE utakmica.idSportLiga = sportliga.id AND utakmica.idStadion = stadion.id 
                             AND utakmica.idDomacin = domacin.id AND utakmica.idGost = gost.id AND sportmoderator.sport_id = sportliga.idSport
                             AND sportmoderator.korisnik_id =  '.$this->f3->get('SESSION.id');
        }else{
            $upitProvjera = 'SELECT utakmica.id, utakmica.datumVrijeme, utakmica.idSportLiga, 
                             utakmica.idStadion, utakmica.idDomacin, utakmica.poeniDomacin, 
                             utakmica.idGost, utakmica.poeniGost, sportliga.naziv AS liga, 
                             stadion.naziv AS stadion, domacin.naziv AS domacin, gost.naziv AS gost 
                             FROM utakmica,sportliga, stadion, klub AS gost, klub AS domacin 
                             WHERE utakmica.idSportLiga = sportliga.id AND utakmica.idStadion = stadion.id 
                             AND utakmica.idDomacin = domacin.id AND utakmica.idGost = gost.id';
        }
            $stupci = array('id', 'datumVrijeme', 'sportliga.naziv', 'stadion.naziv', 'domacin.naziv', 'gost.naziv');

            $upit = $upitProvjera;

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

            foreach ($rezlutat as $red) {
                $datetime = new DateTime($red["datumVrijeme"]);
                $datumVrijeme = $datetime->format('d.m.Y H:i');

                $datumVrijemeSad = date("Y-m-d H:i:s");
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

                $red["domaciIspis"] = $red["domacin"] . " (" . $red["idDomacin"] . ")";
                $red["gostIspis"] = $red["gost"] . " (" . $red["idGost"] . ")";
                $red["ligaIspis"] = $red["liga"] . " (" . $red["idSportLiga"] . ")";
                $red["stadioIspis"] = $red["stadion"] . "(" . $red["idStadion"] . ")";
                $sub_array = array();
                $sub_array[] = '<div style="color: '.$boja.'"  contenteditable class="update" data-id="' . $red["id"] . '" data-column="id">' . $red["id"] . '</div>';
                $sub_array[] = '<div style="color: '.$boja.'"  contenteditable class="update" data-id="' . $red["id"] . '" data-column="datumVrijeme">' . $datumVrijeme . '</div>';
                $sub_array[] = '<div style="color: '.$boja.'"  contenteditable class="update" data-id="' . $red["id"] . '" data-column="idSportLiga">' . $red["ligaIspis"] . '</div>';
                $sub_array[] = '<div style="color: '.$boja.'"  contenteditable class="update" data-id="' . $red["id"] . '" data-column="idStadion">' . $red["stadioIspis"] . '</div>';
                $sub_array[] = '<div style="color: '.$boja.'"  contenteditable class="update" data-id="' . $red["id"] . '" data-column="idDomacin">' . $red["domaciIspis"] . '</div>';
                $sub_array[] = '<div style="color: '.$boja.'"  contenteditable class="update" data-id="' . $red["id"] . '" data-column="poeniDomacin">' . $red["poeniDomacin"] . '</div>';
                $sub_array[] = '<div style="color: '.$boja.'"  contenteditable class="update" data-id="' . $red["id"] . '" data-column="poeniGost">' . $red["poeniGost"] . '</div>';
                $sub_array[] = '<div style="color: '.$boja.'"  contenteditable class="update" data-id="' . $red["id"] . '" data-column="idGost">' . $red["gostIspis"] . '</div>';
                $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $red["id"] . '">Obriši</button>';
                $podaci[] = $sub_array;
            }

            $total = $this->db->exec($upitProvjera);

            $izlaz = array(
                "draw" => intval($_POST["draw"]),
                "recordsTotal" => count($total),
                "recordsFiltered" => count($broj_redaka),
                "data" => $podaci
            );
            echo json_encode($izlaz);
    }
    function dodajUtakmicu(){
        if($_POST["domacin"] == $_POST["gost"]){
            echo 'Domacin i gost ne mogu biti isti!';
        }else{
            if($_POST["poeniD"] != "" && $_POST["poeniG"] != ""){
                $upit = 'INSERT INTO utakmica(datumVrijeme, idSportLiga, idStadion, idDomacin, poeniDomacin, idGost, poeniGost) VALUES(?,?,?,?,?,?,?)';
                $dodaj = $this->db->exec($upit,array($_POST["datumVrijeme"],$_POST["liga"],$_POST["stadion"],
                    $_POST["domacin"],$_POST["poeniD"],$_POST["gost"],$_POST["poeniG"]));
            }else{
                $upit = 'INSERT INTO utakmica(datumVrijeme, idSportLiga, idStadion, idDomacin, idGost) VALUES(?,?,?,?,?)';
                $dodaj = $this->db->exec($upit,array($_POST["datumVrijeme"],$_POST["liga"],$_POST["stadion"],
                    $_POST["domacin"],$_POST["gost"]));
            }
            if($dodaj){
                $this->dnevnikRada($upit);
                echo 'Nova utakmica kreirana';
            }
        }
    }
    function obrisiUtakmicu(){
        $upit ='DELETE FROM utakmica WHERE id = ?';
        $obrisi = $this->db->exec($upit, array($_POST["id"]));
        if($obrisi)
        {
            $this->dnevnikRada($upit);
            echo 'Utakmica izbrisana';
        }
    }
    function azurirajUtakmicu(){
        $upit="UPDATE utakmica SET " . $_POST["stupac"] . "='" . $_POST["vrijednost"] . "' WHERE id = '" . $_POST["id"] . "'";
        $azuriraj = $this->db->exec($upit);
        if($azuriraj){
            $this->dnevnikRada($upit);
            echo 'Utakmica ažurirana';
        }

    }

    //Slike
    function slikeRender()
    {
        $this->f3->set('title', 'Moderator');
        $template = new Template();
        $this->f3->set('stranica', 'Moderator/slike.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function dohvatiSlike(){

        $stupci = array('id', 'Naziv');

        $upit = "SELECT korisnik.korisnickoIme, slika.id, klub.naziv as klub, slika.datumVrijeme, slika.naziv, slika.odobreno 
                FROM korisnik, klub, slika WHERE slika.korisnikId = korisnik.id AND slika.klubId = klub.id";
        if (isset($_POST["search"]["value"]))
        {
            $upit .= '
                AND (klub.naziv LIKE "%' . $_POST["search"]["value"] . '%" 
                OR korisnik.korisnickoIme LIKE "%' . $_POST["search"]["value"] . '%") 
                ';
        }
        if (isset($_POST["order"])) {
            $upit .= 'ORDER BY ' . $stupci[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $upit .= 'ORDER BY slika.datumVrijeme DESC ';
        }
        $upit1 = '';

        if ($_POST["length"] != -1) {
            $upit1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $broj_redaka = $this->db->exec($upit);

        $rezlutat = $this->db->exec($upit.$upit1);
        $podaci = array();

        foreach ($rezlutat as $red) {
            $datetime = new DateTime($red["datumVrijeme"]);
            $datumVrijeme = $datetime->format('d.m.Y H:i');
            $sub_array = array();
            $sub_array[] = '<div data-id="' . $red["id"] . '" data-column="id">' . $datumVrijeme. '</div>';
            $sub_array[] = '<img src="../../files/Korisnicke_Slike/'.$red['naziv'].'" height="300" width="300" class="img-thumbnail" />';
            $sub_array[] = '<div data-id="' . $red["id"] . '" data-column="Naziv">' . $red["korisnickoIme"] . '</div>';
            $sub_array[] = '<div data-id="' . $red["id"] . '" data-column="Naziv">' . $red["klub"] . '</div>';
            if($red["odobreno"] == 0){
                $sub_array[] = '<button type="button" class="btn btn-success btn-xs odobri" id="' . $red["id"] . '">Odobri</button>';
            }else{
                $sub_array[] = '<div class="update" data-id="' . $red["id"] . '" data-column="Naziv">Odobreno</div>';
            }

            $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $red["id"] . '">Obriši</button>';
            $podaci[] = $sub_array;
        }

        $total = $this->db->exec("SELECT korisnik.korisnickoIme, klub.naziv as klub, slika.id, slika.datumVrijeme, slika.naziv, slika.odobreno 
                FROM korisnik, klub, slika WHERE slika.korisnikId = korisnik.id AND slika.klubId = klub.id");

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);
    }
    function odobriSliku(){
        $upit = "UPDATE slika SET odobreno=1 WHERE id = ". $_POST["id"];
        $azuriraj = $this->db->exec($upit);
        if($azuriraj){
            $this->loggerFatFree('Moderator '.$this->f3->get("SESSION.id")).' odobrio sliku '.$_POST["id"];
            $this->dnevnikRada($upit);
            echo 'Slika odobrena';
        }
    }
    function obrisiSliku(){
        $upit ='DELETE FROM slika WHERE id = ?';
        $obrisi = $this->db->exec($upit, array($_POST["id"]));
        if($obrisi)
        {
            $this->loggerFatFree('Moderator '.$this->f3->get("SESSION.id")).' obrisao sliku '.$_POST["id"];
            $this->dnevnikRada($upit);
            echo 'Slika izbrisana';
        }
    }
}
//TO DO, kod, utakmica i klub liga, zabraniti moderatoru dodavanje onih de nije moderator