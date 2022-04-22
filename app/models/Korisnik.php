<?php
class Korisnik extends DB\SQL\Mapper{
    public function __construct(\DB\SQL $db){
        parent::__construct($db, 'korisnik');
    }

    public function  all(){
        $this->load();
        return $this->query;
    }

    public function getById($id)
    {
        $this->load(array('id=?', $id));
        return $this->query;

    }

    public function add($query){
        $this->copyfrom('POST');
        $this->save();
    }

    public function edit($id){
        $this->load(array('id=?',$id));
        $this->copyfrom('POST');
        $this->update();
    }

    public function delete($id){
        $this->load(array('$id=?',$id));
        $this->erase();
    }

    public function dohvatiKorisnika($korisnickoIme){
        $this->load(array('korisnickoIme=?', $korisnickoIme));
    }
}