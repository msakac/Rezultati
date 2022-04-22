<?php
class KlubController extends Controller{
    public function beforeroute()
    {
    }
    function klubRender(){
        $klub = $name = str_replace('_', ' ', $this->f3->get('PARAMS.klubNaziv'));
        $idKlub = pow($this->f3->get('PARAMS.idKlub'),1/4);
        $this->DohvatiPodatkeKluba($idKlub);
        $this->DohvatiMomcadKluba($idKlub);
        $this->DohvatiNadolazeceUtakmice($idKlub);
        $this->f3->set('title', $klub);
        $template = new Template();
        $this->f3->set('stranica', 'Rezultati/rezultatiKlub.php');
        echo $template->render('Predlozak/predlozak.php');
    }

    function DohvatiMomcadKluba($id){
        $ekipa = array();
        $upitMomcad = "SELECT igrac.Ime, igrac.id, igrac.Prezime, pozicija.Naziv 
                        FROM igrac, pozicija, klub 
                        WHERE klub.id = ?
                        AND klub.id = igrac.idKlub AND igrac.idPozicija = pozicija.id";
        $rezlutat = $this->db->exec($upitMomcad, array($id));

        foreach ($rezlutat as $red){
            $ekipa[$red["id"]][] = $red["Ime"];
            $ekipa[$red["id"]][] = $red["Prezime"];
            $ekipa[$red["id"]][] = $red["Naziv"];
        }
        $this->f3->set('ekipa', $ekipa);
    }
    function DohvatiNadolazeceUtakmice($id){
        $utakmice = array();

        $upit = "SELECT utakmica.id, utakmica.datumVrijeme, utakmica.idSportLiga, utakmica.idStadion, 
       utakmica.idDomacin, utakmica.poeniDomacin, utakmica.idGost, utakmica.poeniGost, 
       sportliga.naziv AS liga, stadion.naziv AS stadion, domacin.naziv AS domacin, 
       gost.naziv AS gost FROM utakmica,sportliga, stadion, klub AS gost, klub AS domacin 
WHERE utakmica.idSportLiga = sportliga.id AND utakmica.idStadion = stadion.id AND 
      utakmica.idDomacin = domacin.id AND utakmica.idGost = gost.id AND (domacin.id = ? OR gost.id = ?)";
        $rezlutat = $this->db->exec($upit, array($id, $id));
        foreach ($rezlutat as $red) {
            $datetime = new DateTime($red["datumVrijeme"]);
            $datumVrijeme = $datetime->format('d.m.Y H:i');
            $rezlutat = $red["poeniDomacin"]." : ".$red["poeniGost"];
            $utakmice[$red["id"]]["datumVrijeme"] = $datumVrijeme;
            $utakmice[$red["id"]][] = $red["domacin"];
            $utakmice[$red["id"]][] = $rezlutat;
            $utakmice[$red["id"]][] = $red["gost"];
        }
        array_multisort(array_map(function($red) {
            return $red['datumVrijeme'];
        }, $utakmice), SORT_ASC, $utakmice);
        $this->f3->set('utakmice', $utakmice);
    }
    function DohvatiPodatkeKluba($id){
        $upit = "select sport.naziv as sport, regija.naziv as regija, regija.kratica, stadion.naziv as stadion, stadion.mjesto, klub.naziv 
                FROM klub, regija, stadion, sport WHERE klub.regija_id = regija.id AND klub.sport_id = sport.id 
                AND klub.idStadion = stadion.id AND klub.id = ?; ";
        $rezlutat = $this->db->exec($upit, array($id));
        foreach ($rezlutat as $red){
            $this->f3->set('sport', $red['sport']);
            $this->f3->set('regija', $red['regija']);
            $this->f3->set('kratica', $red['kratica']);
            $this->f3->set('stadion', $red['stadion']);
            $this->f3->set('mjesto', $red['mjesto']);
        }
    }
}
