<?php
class LigaController extends Controller{
    public function beforeroute()
    {
    }
    function ligaRender(){
        $liga = $name = str_replace('_', ' ', $this->f3->get('PARAMS.ligaNaziv'));

        $idLige = pow($this->f3->get('PARAMS.idLiga'),1/4);
        $this->DohvatiLigeISportove(); //za sidebar
        $this->DohvatiPodatkeLige($idLige);
        $this->DohvatiKluboveLige($idLige);
        $this->DohvatiRasporedUtakmica($idLige);
        $this->f3->set('title', $liga);
        $template = new Template();
        $this->f3->set('stranica', 'Rezultati/rezultatiLiga.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function DohvatiPodatkeLige($id){
        $upit = "select sport.naziv as sport, regija.naziv as regija, regija.kratica FROM sport, regija, sportliga 
                WHERE sportliga.idRegija = regija.id AND sportliga.idSport = sport.id AND sportliga.id = ?;";
        $rezlutat = $this->db->exec($upit, array($id));
        foreach ($rezlutat as $red){
            $this->f3->set('sport', $red['sport']);
            $this->f3->set('regija', $red['regija']);
            $this->f3->set('kratica', $red['kratica']);
        }
    }
    function DohvatiKluboveLige($id){
        $klubovi = array();
        $upit = "SELECT klub.naziv, klub.id from klub, klublige, sportliga WHERE klublige.idKlub = klub.id 
                 AND klublige.idLiga = sportliga.id AND sportliga.id = ?";
        $rezlutat = $this->db->exec($upit, array($id));
        foreach ($rezlutat as $red) {
            //ukupan broj utakmica za svaki klub
            $upitBrojUtakmica = "SELECT COUNT(*) AS stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                            AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ? AND utakmica.poeniDomacin IS NOT NULL
                            AND utakmica.poeniGost IS NOT NULL AND (utakmica.idDomacin = klub.id OR utakmica.idGost = klub.id)";
            $red["brojUtakmica"] = $this->IzvrsiUpit($upitBrojUtakmica, $id, $red["id"]);

            //ukupno pobijedenih utakmica
            $upitPobjedeneUtakmice = "SELECT COUNT(*) AS stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                                    AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ? 
                                    AND ((utakmica.idDomacin = klub.id AND utakmica.poeniDomacin > utakmica.poeniGost) 
                                    OR (utakmica.idGost = klub.id AND utakmica.poeniGost > utakmica.poeniDomacin))";
            $red["pobjede"] = $this->IzvrsiUpit($upitPobjedeneUtakmice, $id, $red["id"]);

            //ukupno nerjesenih utakmica
            $upitNerjeseneUtakmice = "SELECT COUNT(*) AS stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                                    AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ? 
                                    AND ((utakmica.idDomacin = klub.id AND utakmica.poeniDomacin = utakmica.poeniGost) 
                                    OR (utakmica.idGost = klub.id AND utakmica.poeniGost = utakmica.poeniDomacin))";
            $red["nerjeseno"] = $this->IzvrsiUpit($upitNerjeseneUtakmice, $id, $red["id"]);

            //ukupno izgubljenih utakmica
            $red["izgubljene"] = $red["brojUtakmica"] - $red["pobjede"] - $red["nerjeseno"];

            //ukupno postignutih poena, golova
            $upitPoeniGost = "SELECT SUM(utakmica.poeniGost) as stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                                    AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ?
                                    AND utakmica.idGost = klub.id";
            $upitPoeniDomacin = "SELECT SUM(utakmica.poeniDomacin) as stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                                AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ? 
                                AND utakmica.idDomacin = klub.id";
            $poeni = $this->IzvrsiUpit($upitPoeniDomacin, $id, $red["id"]) + $this->IzvrsiUpit($upitPoeniGost, $id, $red["id"]);
            //ukupno dobivenih poena, golova
            $upitPoeniPrimljeniGost = "SELECT SUM(utakmica.poeniDomacin) as stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                                    AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ?
                                    AND utakmica.idGost = klub.id";
            $upitPoeniPrimljeniDomacin = "SELECT SUM(utakmica.poeniGost) as stupac FROM utakmica, klublige, klub, sportliga WHERE klublige.idKlub = klub.id 
                                AND klublige.idLiga = sportliga.id AND sportliga.id = ? AND klub.id = ? 
                                AND utakmica.idDomacin = klub.id";
            $primljeni = $this->IzvrsiUpit($upitPoeniPrimljeniDomacin, $id, $red["id"]) + $this->IzvrsiUpit($upitPoeniPrimljeniGost, $id, $red["id"]);
            $red["poeni"] = $poeni." : ".$primljeni;
            $red["bodovi"] = $red["pobjede"]*3 + $red["nerjeseno"];
            $klubovi[$red["id"]][] = $red["naziv"];
            $klubovi[$red["id"]][] = $red["brojUtakmica"];
            $klubovi[$red["id"]][] = $red["pobjede"];
            $klubovi[$red["id"]][] = $red["nerjeseno"];
            $klubovi[$red["id"]][] = $red["izgubljene"];
            $klubovi[$red["id"]][] = $red["poeni"];
            $klubovi[$red["id"]]["bodovi"] = $red["bodovi"];
        }
        //sortiranje po bodovima
        array_multisort(array_map(function($red) {
            return $red['bodovi'];
        }, $klubovi), SORT_DESC, $klubovi);

        $this->f3->set('klubovi', $klubovi);
    }
    function DohvatiRasporedUtakmica($id){
        $upit = "SELECT utakmica.id, utakmica.datumVrijeme, utakmica.idSportLiga, 
                             utakmica.idStadion, utakmica.idDomacin, utakmica.poeniDomacin, 
                             utakmica.idGost, utakmica.poeniGost, sportliga.naziv AS liga, 
                             stadion.naziv AS stadion, domacin.naziv AS domacin, gost.naziv AS gost 
                             FROM utakmica,sportliga, stadion, klub AS gost, klub AS domacin 
                             WHERE utakmica.idSportLiga = sportliga.id AND utakmica.idStadion = stadion.id 
                             AND utakmica.idDomacin = domacin.id AND utakmica.idGost = gost.id AND sportliga.id = ?";
        $rezlutat = $this->db->exec($upit, array($id));

        $utakmice = array();
        $zavrseneUtakmice = array();
        $datumVrijemeSad = date("Y-m-d H:i:s");
        foreach ($rezlutat as $red) {

            $datetime = new DateTime($red["datumVrijeme"]);
            $datumVrijeme = $datetime->format('d.m.Y H:i');
            $rezlutat = $red["poeniDomacin"]." : ".$red["poeniGost"];

            $to_time =strtotime($red['datumVrijeme']);
            $from_time = strtotime($datumVrijemeSad);
            $minuta = round(($from_time - $to_time   ) / 60). " min";
            if(intval($minuta) < 0){
                $status = "-";
                $utakmice[$red["id"]]["datumVrijeme"] = $datumVrijeme;
                $utakmice[$red["id"]][] = $status;
                $utakmice[$red["id"]][] = $red["domacin"];
                $utakmice[$red["id"]][] = $rezlutat;
                $utakmice[$red["id"]][] = $red["gost"];
            }
            else if(intval($minuta) >= 0 && intval($minuta) <= 90 ){
                //ako utakmica traje
                $status = $minuta;
                $utakmice[$red["id"]]["datumVrijeme"] = $datumVrijeme;
                $utakmice[$red["id"]][] = $status;
                $utakmice[$red["id"]][] = $red["domacin"];
                $utakmice[$red["id"]][] = $rezlutat;
                $utakmice[$red["id"]][] = $red["gost"];
            }else{
                $status = "Kraj";
                $zavrseneUtakmice[$red["id"]]["datumVrijeme"] = $datumVrijeme;
                $zavrseneUtakmice[$red["id"]][] = $status;
                $zavrseneUtakmice[$red["id"]][] = $red["domacin"];
                $zavrseneUtakmice[$red["id"]][] = $rezlutat;
                $zavrseneUtakmice[$red["id"]][] = $red["gost"];
            }
        }
        array_multisort(array_map(function($red) {
            return $red['datumVrijeme'];
        }, $utakmice), SORT_ASC, $utakmice);
        $this->f3->set('utakmice', $utakmice);

        array_multisort(array_map(function($red) {
            return $red['datumVrijeme'];
        }, $zavrseneUtakmice), SORT_ASC, $zavrseneUtakmice);
        $this->f3->set('zavrseneUtakmice', $zavrseneUtakmice);
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