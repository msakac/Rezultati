<?php
require_once "app/models/Korisnik.php";

class KorisnikController extends Controller{
    public function beforeroute()
    {

    }

    public function render(){
        $this->f3->set('title','Prijava');
        $template = new Template();

        if($this->f3->get('SESSION.korisnik') === null){ //ako nije prijavljen preusmjeravamo na prijavu
            $this->f3->set('stranica','Ostalo/prijava.php');
            echo $template->render('Predlozak/predlozak.php');

        }else{ //ako je prijavljen preusmjeravamo na pocetnu stranicu 'prijavljen'
            $this->f3->set('stranica','Pocetna/prijavljen.php');
            echo $template->render('Predlozak/predlozak.php');
        }
    }

    public function profilRender(){
        $this->f3->set('title','Profil');
        $template = new Template();

        if($this->f3->get('SESSION.korisnik') === null){ //ako nije prijavljen preusmjeravamo na prijavu
            $this->f3->set('stranica','Ostalo/prijava.php');
            echo $template->render('Predlozak/predlozak.php');

        }else{ //ako je prijavljen preusmjeravamo na pocetnu stranicu 'prijavljen'
            $this->f3->set('stranica','Ostalo/profil.php');
            if($this->f3->get('SESSION.uloga') > 1){
                //dohvacanje naziv uloge
                $idUloga = $this->f3->get('SESSION.uloga');
                $upitUloga = "SELECT naziv from uloga WHERE id = ?";
                $rezlutatUloga = $this->db->exec($upitUloga, $idUloga);
                $nazivUloge = '';
                foreach ($rezlutatUloga as $red){
                    $nazivUloge = $red["naziv"];
                }
                $this->f3->set('nazivUloge', $nazivUloge);
                //dohvacanje sportova
                $upitSportovi = "SELECT sport.naziv FROM sportmoderator, sport 
                                WHERE sportmoderator.sport_id = sport.id AND sportmoderator.korisnik_id = ?";
                $idKorisnik =$this->f3->get('SESSION.id');
                $rezlutatSportovi = $this->db->exec($upitSportovi, $idKorisnik);
                $sportovi = '';
                foreach($rezlutatSportovi as $red){
                    $sportovi .= '<'.$red["naziv"].'> ';
                }
                $this->f3->set('sportoviModerator', $sportovi);
            }
            echo $template->render('Predlozak/predlozak.php');
        }
    }

    public function  prijava(){
        $korisnickoIme = $this->f3->get('POST.korisnickoIme');
        $lozinka = $this->f3->get('POST.lozinka');
        $korisnik = new Korisnik($this->db); //kreiramo novu instancu modela korisnik
        $korisnik->dohvatiKorisnika($korisnickoIme); //dohvcamo korisnika po korisnickom imenu

        if($korisnik->dry()){ //ako je dry true, znaci da nije dohvacen niti jedan korisnik
            $this->f3->set('COOKIE.poruka', 'Korisničko ime ne postoji!');
            $this->f3->reroute('prijava');
        }

        if(password_verify($lozinka, $korisnik->lozinkaEnc)){ //funkcija hashira lozinku i provjerava sa lozinkom u bazi
            $this->f3->set('SESSION.korisnik', $korisnik->korisnickoIme);
            $this->f3->set('SESSION.uloga', $korisnik->uloga_id);//kreiramo sesiju i spremamo korisnicko ime
            $this->f3->set('SESSION.id', $korisnik->id);
            $this->f3->set('SESSION.ime', $korisnik->ime);
            $this->f3->set('SESSION.prezime', $korisnik->prezime);
            $this->f3->set('SESSION.email', $korisnik->email);
            $this->dnevnikRada("Prijava");
            $this->f3->set('COOKIE.poruka',' ');
            $this->f3->reroute('/'); //preusjmeravamo na pocetnu
        }else{ //ako nije dobra lozinka
            $this->f3->set('COOKIE.poruka','Pogrešna lozinka!');
            $this->f3->reroute('prijava');

        }
    }
    public function azurirajPodatke(){

        if($_POST["lozinka"] != ""){
            $lozinkaEnc = password_hash($_POST["lozinka"],PASSWORD_DEFAULT);;
            $id = $this->f3->get('SESSION.id');
            $upit = "UPDATE korisnik SET lozinka = ?, lozinkaEnc = ?, ime = ?, prezime = ? WHERE id = ?";
            $this->db->exec($upit, array($_POST["lozinka"], $lozinkaEnc,$_POST["ime"],$_POST["prezime"], $id));
        }else{
            $id = $this->f3->get('SESSION.id');
            $upit = "UPDATE korisnik SET ime = ?, prezime = ? WHERE id = ?";
            $this->db->exec($upit, array($_POST["ime"],$_POST["prezime"], $id));
        }
        $this->odjava();
    }
    public function odjava(){
        $this->dnevnikRada("Odjava");
        $this->f3->clear('SESSION');
        $this->f3->reroute('/');
    }

}