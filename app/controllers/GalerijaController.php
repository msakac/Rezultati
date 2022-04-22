<?php
class GalerijaController extends Controller{

    function render(){
        $this->f3->set('title', 'Galerija');
        $this->dohvatiKlubove();
        $template = new Template();
        $this->f3->set('stranica', 'Ostalo/galerija.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function dohvatiSveSlike(){
        $upit = "SELECT klub.naziv as klub, slika.id, slika.datumVrijeme, slika.naziv, slika.odobreno FROM klub, slika 
                 WHERE slika.klubId = klub.id AND slika.odobreno = 1 AND klub.naziv LIKE ? ORDER BY slika.datumVrijeme DESC";
        $pretrazi = '%'.$_POST["klub"].'%';
        $rezlutat = $this->db->exec($upit,array($pretrazi));
        $html ='';
        foreach ($rezlutat as $red) {
            $html .= '<a href="/files/Korisnicke_Slike/'.$red['naziv'].'" data-lightbox="mygallery" data-title="'.$red['klub'].'"><img src="/files/Korisnicke_Slike/'.$red['naziv'].'"></a>';
           // $html .= '<figcaption>'.$red["klub"].'</figcaption>';
        }
        echo $html;
    }

    function renderKorisnik(){
        $this->f3->set('title', 'Moja galerija');
        $this->dohvatiKlubove();
        $template = new Template();
        $this->f3->set('stranica', 'Ostalo/galerijaKorisnik.php');
        echo $template->render('Predlozak/predlozak.php');
    }
    function dohvatiSlike(){
        $upit = "SELECT klub.naziv as klub, slika.id, slika.datumVrijeme, slika.naziv, slika.odobreno 
                FROM klub, slika, korisnik WHERE slika.klubId = klub.id AND slika.korisnikId = korisnik.id 
                AND korisnik.id = ? ORDER BY slika.datumVrijeme DESC";
        $rezlutat = $this->db->exec($upit, array($this->f3->get('SESSION.id')));
        $html = '<thead> 
                    <tr>
                     <th style="text-align: center">Datum</th>
                     <th style="text-align: center">Slika</th>
                     <th style="text-align: center">Klub</th>
                     <th style="text-align: center">Odobreno</th>
                     <th style="text-align: center">Obriši</th>
                    </tr></thead>';
        foreach($rezlutat as $red)
        {
            $datetime = new DateTime($red["datumVrijeme"]);
            $datumVrijeme = $datetime->format('d.m.Y H:i');
            if($red["odobreno"] == 0 ? $odobreno='Ne' :$odobreno='Da');
            $html .= '
            <tr>
            <td>'.$datumVrijeme.'</td>
            <td><img src="../../files/Korisnicke_Slike/'.$red['naziv'].'" height="300" width="300" class="img-thumbnail" /></td>
            <td>'.$red["klub"].'</td>
            <td>'.$odobreno.'</td>
            <td><button type="button" name="delete" class="btn btn-danger bt-xs delete" id="'.$red["id"].'">Obriši</button></td>
            </tr>
           ';
        }
        $html .= '</table>';
        echo $html;
    }
    function predajSliku(){
        $direktorij = "files/Korisnicke_Slike/";
        $nazivSlike = basename($_FILES["slika"]["name"]);
        $putanja = $direktorij . $nazivSlike;
        move_uploaded_file($_FILES["slika"]["tmp_name"], $putanja);
        $klub = $_POST["klub"];
        $korisnik = $this->f3->get("SESSION.id");
        $odobreno = 0;
        $datumVrijeme = date("Y-m-d H:i:s");

        $radnja = 'Korisnik '.$korisnik.' dodao novu sliku '.$nazivSlike.' za klub '.$klub;
        $upit = "INSERT INTO slika(datumVrijeme, naziv, korisnikId, klubId, odobreno) VALUES (?,?,?,?,?)";
        $rezlutat = $this->db->exec($upit, array($datumVrijeme, $nazivSlike, $korisnik,$klub,$odobreno));
        if($rezlutat){
            $this->loggerFatFree($radnja);
            $this->dnevnikRada($upit);
            echo 'Slika '.$nazivSlike.' je predana!';
        }else{
            echo'Greska!';
        }
    }
    function obrisiSliku(){
        $upit ='DELETE FROM slika WHERE id = ?';
        $obrisi = $this->db->exec($upit, array($_POST["id"]));
        if($obrisi)
        {
            $this->dnevnikRada($upit);
            $this->loggerFatFree($upit);
            echo 'Slika izbrisana';
        }
    }
    function dohvatiKlubove(){
        $klub = array();
        $upit = "select klub.id, klub.naziv from klub";
        $rezlutat = $this->db->exec($upit);
        foreach ($rezlutat as $red) {
            $klub[$red["id"]][] = $red["naziv"];
        }
        $this->f3->set('klubovi', $klub);
    }

}