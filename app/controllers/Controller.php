<?php
class Controller{

    public $f3;
    public $db;

    function beforeroute(){ // funkcija f3 koja zove prije usmjeravanja - odlicno za koristenje sesije
        if($this->f3->get('SESSION.korisnik') === null) {
            $this->f3->reroute('/prijava');
            exit;
        }
    }

    function dnevnikRada($upit){
        $datumVrijeme = date('Y-m-d H:i:s');
        $id = $this->f3->get('SESSION.id');
        $tipRada = 3;
        if($upit == "Prijava" || $upit == "Odjava" ){
            $tipRada = 1;
        }
        $upitt = 'INSERT INTO dnevnikrada(radnja,datumVrijeme,tipRada_id,korisnik_id) VALUES(?,?,?,?)';
        $this->db->exec($upitt,array($upit,$datumVrijeme,$tipRada,$id));
    }
    function loggerFatFree($radnja){
        $logger = new Log('files/slike.log');
        $logger->write($radnja);
    }

    function __construct(){
        $f3 = Base::instance();
        $this->f3 = $f3;
        $db = new DB\SQL(
            $f3->get('devdb'),
            $f3->get('devdbusername'),
            $f3->get('devdbpassword'),
            array(\PDO::ATTR_ERRMODE=> \PDO::ERRMODE_EXCEPTION) //pokazuje erore z baze podataka
        );
        $this->db=$db;
    }

}