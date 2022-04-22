<?php
class PocetnaController extends Controller{

    function beforeroute(){}

    function render(){
        //$cache = \Cache::instance();
        $this->f3->set('title','Pocetna');
        $this->f3->set('COOKIE.poruka',' ');
        //$cache->set('porukaCache','bok');
        //$this->f3->set('poruka','Pocetna');
        $template = new Template();

        if($this->f3->get('SESSION.korisnik') === null){ //ako nije prijavljen
            $this->f3->set('SESSION.uloga', 0); //ako nije korisnik prijavljen setam uloga na 0 jer inace hice gresku
            //Undefined array key "uloga"
            $this->f3->set('stranica','Pocetna/neprijavljen.php');
            echo $template->render('Predlozak/predlozak.php');
        }else{ //ako je prijavljen
            $this->f3->set('stranica','Pocetna/prijavljen.php');
            echo $template->render('Predlozak/predlozak.php');
        }
    }


}
