<?php
require_once "app/models/Korisnik.php";

class AdminController extends Controller
{
    function beforeroute()
    {
        if ($this->f3->get('SESSION.uloga') != 3) { //ako nije admin
            $this->f3->reroute('/');
        }
    }
    function render()
    {
        $this->f3->set('title', 'Admin');
        $template = new Template();
        $this->f3->set('stranica', 'Admin/pocetna.php');
        echo $template->render('Predlozak/predlozak.php');
    }

    //CRUD Korisnik, dohvacanje uloga za select
    function korisniciRender()
    {
        $this->f3->set('title', 'Admin');
        $template = new Template();
        $this->f3->set('stranica', 'Admin/korisnici.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function korisnikDohvatiUloge(){
        $upit = "SELECT * FROM uloga";
        $rezlutat = $this->db->exec($upit);
        $uloge = '';
        $uloge .= '<td><select id ="'.$_POST["stupac"].'" class="form-control selectpicker" style="width: 100px" data-live-search="true"><option value="">Uloga</option>';
        foreach ($rezlutat as $red){
            $red["ulogaIspis"] =$red["naziv"]." (".$red["id"].")";
            $uloge .='<option value="'.$red["id"].'">'.$red["ulogaIspis"].'</option>';
        }
        $uloge .='</select></td>';
        echo $uloge;
    }
    function dohvatiKorisnike()
    {
        $stupci = array('korisnickoIme', 'email', 'lozinka', 'ime', 'prezime', 'uloga_id');

        $upit = "SELECT korisnik.id, korisnik.korisnickoIme, korisnik.email, korisnik.lozinka, korisnik.ime, korisnik.prezime, korisnik.uloga_id, uloga.naziv 
                FROM korisnik, uloga WHERE korisnik.uloga_id = uloga.id ";

        if (isset($_POST["search"]["value"])) //ako nekaj napisem u search u query doda search vrednosti
        {
            $upit .= '
                AND (korisnik.ime LIKE "%' . $_POST["search"]["value"] . '%" 
                OR korisnik.prezime LIKE "%' . $_POST["search"]["value"] . '%"  
                OR korisnik.korisnickoIme LIKE "%' . $_POST["search"]["value"] . '%" 
                )';
        }
        if (isset($_POST["order"])) {
            $upit .= 'ORDER BY ' . $stupci[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $upit .= 'ORDER BY korisnik.id DESC ';
        }
        $upit1 = '';

        if ($_POST["length"] != -1) {
            $upit1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $broj_redaka = $this->db->exec($upit);

        $rezlutat = $this->db->exec($upit.$upit1);
        $podaci = array();

        foreach ($rezlutat as $red) {
            $red["ulogaIspis"] =$red["naziv"]." (".$red["uloga_id"].")";
            $sub_array = array();
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="korisnickoIme">' . $red["korisnickoIme"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="email">' . $red["email"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="lozinka">' . $red["lozinka"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="ime">' . $red["ime"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="prezime">' . $red["prezime"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="uloga_id">' . $red["ulogaIspis"] . '</div>';
            $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $red["id"] . '">Obriši</button>';
            $podaci[] = $sub_array;
        }

        $total = $this->db->exec("SELECT korisnik.id, korisnik.korisnickoIme, korisnik.email, korisnik.lozinka, korisnik.ime, korisnik.prezime, korisnik.uloga_id, uloga.naziv FROM korisnik, uloga WHERE korisnik.uloga_id = uloga.id; ");

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);
    }
    function dodajKorisnika(){
        $lozinkaEnc = password_hash($_POST["lozinka"],PASSWORD_DEFAULT);
        $upit = 'INSERT INTO korisnik(korisnickoIme,email,lozinka,lozinkaEnc,ime,prezime,uloga_id) VALUES(?,?,?,?,?,?,?)';
        $dodaj = $this->db->exec($upit,array($_POST["korisnickoIme"],$_POST["email"],$_POST["lozinka"],$lozinkaEnc,$_POST["ime"],$_POST["prezime"],$_POST["uloga"]));
        if($dodaj){
            $this->dnevnikRada($upit);
            echo 'Novi korisnik kreiran';
        }
    }
    function obrisiKorisnika(){
        $upit ='DELETE FROM korisnik WHERE id = ?';
        $obrisi = $this->db->exec($upit, array($_POST["id"]));
            if($obrisi)
            {
                $this->dnevnikRada($upit);
                echo 'Korisnik izbrisan';
            }
    }
    function azurirajKorisnika(){
        $upit="UPDATE korisnik SET " . $_POST["stupac"] . "='" . $_POST["vrijednost"] . "' WHERE id = '" . $_POST["id"] . "'";
        $azuriraj = $this->db->exec($upit);
        if($azuriraj){
            $this->dnevnikRada($upit);
            echo 'Korisnik azuriran';
        }

    }

    //CRUD Sport
    function sportoviRender(){
        $this->f3->set('title', 'Admin');
        $template = new Template();
        $this->f3->set('stranica', 'Admin/sportovi.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function dohvatiSportove()
    {
        $stupci = array('id','naziv','opis');

        $upit = "SELECT * FROM sport";

        if (isset($_POST["search"]["value"])) //ako nekaj napisem u search u query doda search vrednosti
        {
            $upit .= '
                WHERE naziv LIKE "%' . $_POST["search"]["value"] . '%" 
                OR opis LIKE "%' . $_POST["search"]["value"] . '%"  
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
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="opis">' . $red["opis"] . '</div>';
            $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $red["id"] . '">Obriši</button>';
            $podaci[] = $sub_array;
        }

        $total = $this->db->exec("SELECT * FROM sport");

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);
    }
    function dodajSport(){
        $upit='INSERT INTO sport(id,naziv,opis) VALUES(?,?,?)';
        $dodaj = $this->db->exec($upit,array($_POST["id"],$_POST["naziv"],$_POST["opis"]));
        if($dodaj){
            $this->dnevnikRada($upit);
            echo 'Novi sport kreiran';
        }
    }
    function obrisiSport(){
        $upit ='DELETE FROM sport WHERE id = ?';
        $obrisi = $this->db->exec($upit, array($_POST["id"]));
        if($obrisi)
        {
            $this->dnevnikRada($upit);
            echo 'Sport izbrisan';
        }
    }
    function azurirajSport(){
        $upit = "UPDATE sport SET " . $_POST["stupac"] . "='" . $_POST["vrijednost"] . "' WHERE id = '" . $_POST["id"] . "'";
        $azuriraj = $this->db->exec($upit);
        if($azuriraj){
            $this->dnevnikRada($upit);
            echo 'Sport ažuriran';
        }
    }

    //CRUD Regije
    function regijeRender(){
        $this->f3->set('title', 'Admin');
        $template = new Template();
        $this->f3->set('stranica', 'Admin/regije.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function dohvatiRegije()
    {
        $stupci = array('id','naziv','kratica');

        $upit = "SELECT * FROM regija";

        if (isset($_POST["search"]["value"])) //ako nekaj napisem u search u query doda search vrednosti
        {
            $upit .= '
                WHERE naziv LIKE "%' . $_POST["search"]["value"] . '%" ';
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
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="kratica">' . $red["kratica"] . '</div>';
            $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $red["id"] . '">Obriši</button>';
            $podaci[] = $sub_array;
        }

        $total = $this->db->exec("SELECT * FROM regija");

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);
    }
    function dodajRegiju(){
        $upit = 'INSERT INTO regija(id,naziv,kratica) VALUES(?,?,?)';
        $dodaj = $this->db->exec($upit,array($_POST["id"],$_POST["naziv"],$_POST["kratica"]));
        if($dodaj){
            $this->dnevnikRada($upit);
            echo 'Nova regija kreirana';
        }
    }
    function obrisiRegiju(){
        $upit = 'DELETE FROM regija WHERE id = ?';
        $obrisi = $this->db->exec($upit, array($_POST["id"]));
        if($obrisi)
        {
            $this->dnevnikRada($upit);
            echo 'Regija izbrisana';
        }
    }
    function azurirajRegiju(){
        $upit = "UPDATE regija SET " . $_POST["stupac"] . "='" . $_POST["vrijednost"] . "' WHERE id = '" . $_POST["id"] . "'";
        $azuriraj = $this->db->exec($upit);
        if($azuriraj){
            $this->dnevnikRada($upit);
            echo 'Regija ažurirana';
        }
    }

    //CRUD Sport moderatori, dohvacanje moderatora i sportova za select
    function sportModRender(){
        $this->f3->set('title', 'Admin');
        $template = new Template();
        $this->f3->set('stranica', 'Admin/sportModerator.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function sportModDohvatiModeratore(){
        $upit = "SELECT * FROM korisnik WHERE uloga_id = 2";
        $rezlutat = $this->db->exec($upit);
        $moderatori = '';
        $moderatori .= '<td><select id ="'.$_POST["stupac"].'" class="form-control selectpicker" data-live-search="true"><option value="">Odaberi moderatora</option>';
        foreach ($rezlutat as $red){
            $red["moderatorIspis"] =$red["korisnickoIme"]." (".$red["id"].")";
            $moderatori .='<option value="'.$red["id"].'">'.$red["moderatorIspis"].'</option>';
        }
        $moderatori .='</select></td>';
        echo $moderatori;
    }
    function sportModDohvatiSportove(){
        $upit = "SELECT * FROM sport";
        $rezlutat = $this->db->exec($upit);
        $sport = '';
        $sport .= '<td><select id ="'.$_POST["stupac"].'" class="form-control selectpicker" data-live-search="true"><option value="">Odaberi sport</option>';
        foreach ($rezlutat as $red){
            $red["sportIspis"] =$red["naziv"]." (".$red["id"].")";
            $sport .='<option value="'.$red["id"].'">'.$red["sportIspis"].'</option>';
        }
        $sport .='</select></td>';
        echo $sport;
    }
    function dohvatiSportMod()
    {
        $stupci = array('korisnikId','korisnik.korisnickoIme','sportId','sport.naziv');

        $upit = "SELECT korisnik.id as korisnikId,korisnik.korisnickoIme, sport.id as sportId, sport.naziv 
                    FROM korisnik, sport, sportmoderator WHERE korisnik.id = sportmoderator.korisnik_id 
                    AND sportmoderator.sport_id = sport.id";

        if (isset($_POST["search"]["value"])) //ako nekaj napisem u search u query doda search vrednosti
        {
            $upit .= '
                AND sport.naziv LIKE "%' . $_POST["search"]["value"] . '%" ';
        }
        if (isset($_POST["order"])) {
            $upit .= 'ORDER BY ' . $stupci[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $upit .= 'ORDER BY korisnik.id DESC ';
        }
        $upit1 = '';

        if ($_POST["length"] != -1) {
            $upit1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $broj_redaka = $this->db->exec($upit);

        $rezlutat = $this->db->exec($upit.$upit1);
        $podaci = array();

        foreach ($rezlutat as $red) {
            $red["id"] = $red["korisnikId"].",".$red["sportId"]; //dupli kljuc da znamo koji je koji red
            $red["sport"] =$red["naziv"]." (".$red["sportId"].")";
            $red["korisnik"] =$red["korisnickoIme"]." (".$red["korisnikId"].")";
            $sub_array = array();
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="idKorisnik">' . $red["korisnik"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="idSport">' . $red["sport"] . '</div>';
            $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $red["id"] . '">Obriši</button>';
            $podaci[] = $sub_array;
        }
        $total = $this->db->exec("SELECT korisnik.id,korisnik.korisnickoIme, sport.id, sport.naziv 
                    FROM korisnik, sport, sportmoderator WHERE korisnik.id = sportmoderator.korisnik_id 
                    AND sportmoderator.sport_id = sport.id");

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);
    }
    function dodajSportMod(){
        $upit = 'INSERT INTO sportModerator(sport_id, korisnik_id) VALUES(?,?)';
        $dodaj = $this->db->exec($upit,array($_POST["idSport"],$_POST["idKorisnik"]));
        if($dodaj){
            $this->dnevnikRada($upit);
            echo 'Novi moderator sporta kreiran';
        }
    }
    function obrisiSportMod(){
        $separator = ",";
        $podaci = explode($separator,$_POST["id"]);
        $upit = 'DELETE FROM sportModerator WHERE sport_id = ? AND korisnik_id = ?';
        $obrisi = $this->db->exec($upit, array($podaci[1], $podaci[0]));
        if($obrisi)
        {
            $this->dnevnikRada($upit);
            echo 'Moderator sporta obrisan';
        }
    }

    //CRUD Sport lige, dohvaćanje sportova i regija za select
    function sportLigeRender(){
        $this->f3->set('title', 'Admin');
        $template = new Template();
        $this->f3->set('stranica', 'Admin/sportLige.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function sportLigeDohvatiSportove(){
        $upit = "SELECT * FROM sport";
        $rezlutat = $this->db->exec($upit);
        $sportovi = '';
        $sportovi .= '<td><select id ="'.$_POST["stupac"].'" class="form-control selectpicker" data-live-search="true"><option value="">Odaberi sport</option>';
        foreach ($rezlutat as $red){
            $red["sportIspis"] =$red["naziv"]." (".$red["id"].")";
            $sportovi .='<option value="'.$red["id"].'">'.$red["sportIspis"].'</option>';
        }
        $sportovi .='</select></td>';
        echo $sportovi;
    }
    function sportLigeDohvatiRegije(){
        $upit = "SELECT * FROM regija";
        $rezlutat = $this->db->exec($upit);
        $regije = '';
        $regije .= '<td><select id ="'.$_POST["stupac"].'" class="form-control selectpicker" data-live-search="true"><option value="">Odaberi regiju</option>';
        foreach ($rezlutat as $red){
            $red["regijaIspis"] =$red["naziv"]." (".$red["id"].")";
            $regije .='<option value="'.$red["id"].'">'.$red["regijaIspis"].'</option>';
        }
        $regije .='</select></td>';
        echo $regije;
    }
    function dohvatiSportLige()
    {
        $stupci = array('sportliga.naziv','sport.naziv','regija.naziv');

        $upit = "SELECT sportliga.id as id, sportliga.naziv as liga, sportliga.idSport as idSport, 
                 sportliga.idRegija as idRegija,regija.naziv as regija, sport.naziv as sport 
                 FROM sportliga, regija, sport WHERE sportliga.idSport = sport.id 
                 AND sportliga.idRegija = regija.id";

        if (isset($_POST["search"]["value"])) //ako nekaj napisem u search u query doda search vrednosti
        {
            $upit .= '
                AND (sport.naziv LIKE "%' . $_POST["search"]["value"] . '%" 
                OR regija.naziv LIKE "%' . $_POST["search"]["value"] . '%"
                OR sportliga.naziv LIKE "%' . $_POST["search"]["value"] . '%")';
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
            $red["sportIspis"] =$red["sport"]." (".$red["idSport"].")";
            $red["regijaIspis"] =$red["regija"]."(".$red["idRegija"].")";
            $sub_array = array();
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="liga">' . $red["liga"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="idSport">' . $red["sportIspis"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="idRegija">' . $red["regijaIspis"] . '</div>';
            $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $red["id"] . '">Obriši</button>';
            $podaci[] = $sub_array;
        }
        $total = $this->db->exec("SELECT sportliga.id as id, sportliga.naziv as liga, sportliga.idSport as idSport, 
                 sportliga.idRegija as idRegija,regija.naziv as regija, sport.naziv as sport 
                 FROM sportliga, regija, sport WHERE sportliga.idSport = sport.id 
                 AND sportliga.idRegija = regija.id");

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);
    }
    function dodajSportLige(){
        $upit = 'INSERT INTO sportliga(naziv, idRegija, idSport) VALUES(?,?,?)';
        $dodaj = $this->db->exec($upit,array($_POST["naziv"],$_POST["idRegija"],$_POST["idSport"]));
        if($dodaj){
            $this->dnevnikRada($upit);
            echo 'Nova liga kreirana';
        }
    }
    function obrisiSportLige(){
        $upit = 'DELETE FROM sportliga WHERE id = ?';
        $obrisi = $this->db->exec($upit, array($_POST["id"]));
        if($obrisi)
        {
            $this->dnevnikRada($upit);
            echo 'Liga obrisana';
        }
    }
    function azurirajSportLige(){
        $upit = "UPDATE sportLiga SET " . $_POST["stupac"] . "='" . $_POST["vrijednost"] . "' WHERE id = '" . $_POST["id"] . "'";
        $azuriraj = $this->db->exec($upit);
        if($azuriraj){
            $this->dnevnikRada($upit);
            echo 'Liga ažurirana';
        }
    }

    //CRUD Dnevnik rada
    function dnevnikRadaRender(){
        $this->f3->set('title', 'Admin');
        $template = new Template();
        $this->f3->set('stranica', 'Admin/dnevnikRada.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function dohvatiDnevnikRada()
    {
        $stupci = array('dnevnikrada.radnja','dnevnikrada.datumVrijeme','tiprada.naziv','korisnik.korisnickoIme','korisnik.id');

        $upit = "SELECT dnevnikrada.id, dnevnikrada.radnja, dnevnikrada.datumVrijeme, tiprada.naziv, korisnik.korisnickoIme, korisnik.id AS korisnikId 
                 FROM dnevnikrada, tiprada, korisnik WHERE dnevnikrada.tipRada_id = tiprada.id 
                 AND dnevnikrada.korisnik_id = korisnik.id";

        if (isset($_POST["search"]["value"])) //ako nekaj napisem u search u query doda search vrednosti
        {
            $upit .= '
                AND (korisnik.korisnickoIme LIKE "%' . $_POST["search"]["value"] . '%" 
                OR dnevnikrada.radnja LIKE "%' . $_POST["search"]["value"] . '%")';
        }
        if (isset($_POST["order"])) {
            $upit .= 'ORDER BY ' . $stupci[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $upit .= 'ORDER BY dnevnikrada.datumVrijeme DESC ';
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
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="radnja">' . $red["radnja"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="datumVrijeme">' . $red["datumVrijeme"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="naziv">' . $red["naziv"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="korisnickoIme">' . $red["korisnickoIme"] . '</div>';
            $sub_array[] = '<div contenteditable class="update" data-id="' . $red["id"] . '" data-column="korisnikId">' . $red["korisnikId"] . '</div>';
            //$sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $red["id"] . '">Obriši</button>';
            $podaci[] = $sub_array;
        }
        $total = $this->db->exec("SELECT dnevnikrada.id, dnevnikrada.radnja, dnevnikrada.datumVrijeme, tiprada.naziv, korisnik.korisnickoIme, korisnik.id AS korisnikId 
                 FROM dnevnikrada, tiprada, korisnik WHERE dnevnikrada.tipRada_id = tiprada.id 
                 AND dnevnikrada.korisnik_id = korisnik.id");

        $izlaz = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => count($total),
            "recordsFiltered" => count($broj_redaka),
            "data" => $podaci
        );
        echo json_encode($izlaz);
    }
}