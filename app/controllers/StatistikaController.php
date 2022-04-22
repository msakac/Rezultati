<?php
class StatistikaController extends Controller{
    function render()
    {
        $this->DohvatiLige();
        $this->f3->set('title', 'Statistika');
        $template = new Template();
        $this->f3->set('stranica', 'Ostalo/statistika.php');
        echo $template->render('Predlozak/predlozak.php');
    }

    function DohvatiLige(){
        $lige = array();
        $upit = "select sportliga.id, sportliga.naziv from sportliga";
        $rezlutat = $this->db->exec($upit);
        foreach ($rezlutat as $red) {
           $lige[$red["id"]][] = $red["naziv"];
        }
        $this->f3->set('lige', $lige);
    }
    function dohvatiPodatkeZaGraf(){
        $id = $_POST["liga"];
       $upit = "SELECT klub.naziv, klub.id from klub, klublige, sportliga WHERE klublige.idKlub = klub.id 
                 AND klublige.idLiga = sportliga.id AND sportliga.id = ?";
       $rezlutat = $this->db->exec($upit, $id);
       foreach ($rezlutat as $red) {
           //ukupan broj utakmica
           $upitBrojUtakmica = "SELECT COUNT(*) AS stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                            AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ? AND utakmica.poeniDomacin IS NOT NULL
                            AND utakmica.poeniGost IS NOT NULL AND (utakmica.idDomacin = klub.id OR utakmica.idGost = klub.id)";
           $brojUtakmica = $this->IzvrsiUpit($upitBrojUtakmica, $id, $red["id"]);
           //ukupno pobjedenih utakmica
           $upitPobjedeneUtakmice = "SELECT COUNT(*) AS stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                                    AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ? 
                                    AND ((utakmica.idDomacin = klub.id AND utakmica.poeniDomacin > utakmica.poeniGost) 
                                    OR (utakmica.idGost = klub.id AND utakmica.poeniGost > utakmica.poeniDomacin))";
           $pobjede = $this->IzvrsiUpit($upitPobjedeneUtakmice, $id, $red["id"]);
           //ukupno nerjesenih utakmica
           $upitNerjeseneUtakmice = "SELECT COUNT(*) AS stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                                    AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ? 
                                    AND ((utakmica.idDomacin = klub.id AND utakmica.poeniDomacin = utakmica.poeniGost) 
                                    OR (utakmica.idGost = klub.id AND utakmica.poeniGost = utakmica.poeniDomacin))";
           $nerjeseno = $this->IzvrsiUpit($upitNerjeseneUtakmice, $id, $red["id"]);
           //sum pobjeda i nerjesenih
           $bodovi = $pobjede*3 + $nerjeseno;

           //ukupno zabijenih poena
           $upitPoeniGost = "SELECT SUM(utakmica.poeniGost) as stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                                    AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ?
                                    AND utakmica.idGost = klub.id";
           $upitPoeniDomacin = "SELECT SUM(utakmica.poeniDomacin) as stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                                AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ? 
                                AND utakmica.idDomacin = klub.id";
           $poeni = $this->IzvrsiUpit($upitPoeniDomacin, $id, $red["id"]) + $this->IzvrsiUpit($upitPoeniGost, $id, $red["id"]);

           //ukupno dobivenih poena
           $upitPoeniPrimljeniGost = "SELECT SUM(utakmica.poeniDomacin) as stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                                    AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ?
                                    AND utakmica.idGost = klub.id";
           $upitPoeniPrimljeniDomacin = "SELECT SUM(utakmica.poeniGost) as stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                                AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ? 
                                AND utakmica.idDomacin = klub.id";
           $primljeni = $this->IzvrsiUpit($upitPoeniPrimljeniDomacin, $id, $red["id"]) + $this->IzvrsiUpit($upitPoeniPrimljeniGost, $id, $red["id"]);
           $klub = $red["naziv"];
           $klubBodovi[] = array(
               'klub'   => $klub,
               'bodovi'  => $bodovi,
               'poeniOsvojeni'  => $poeni,
               'poeniPrimljeni'  => $primljeni,
               'brojUtakmica'    => $brojUtakmica
           );
       }
        array_multisort(array_map(function($red) {
            return $red['bodovi'];
        }, $klubBodovi), SORT_DESC, $klubBodovi);
        echo json_encode($klubBodovi);
    }
    function IzvrsiUpit($upit, $idLiga, $idKlub){
        $vrijednost = 0;
        $rezlutat = $this->db->exec($upit, array($idLiga, $idKlub));
        foreach ($rezlutat as $red){
            $vrijednost = $red["stupac"];
        }
        return $vrijednost;
    }
}