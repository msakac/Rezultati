<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \Cache;
use App\Korisnik;

class RegistracijaController extends Controller
{
    public function render()
    {
        if(Cache::has('poruka') && !(Cache::has('refresh'))){
            Cache::set('refresh', 1);
        }
        else{
            Cache::flush();
        }
        return view('registracija');
    }

    public function registriraj(Request $req)
    {
        Cache::flush(); //brisanje cache memorije

        //spremanje korisnickog unosa u varijable
        $email = $req->input('email');
        $lozinka = $req->input('lozinka');
        $potvrdi_lozinku = $req->input('potvrdi_lozinku');
        $ime = $req->input('ime');
        $prezime= $req->input('prezime');
        $korisnickoIme = $req->input('korisnickoIme');

        //spremanje podataka u cache memoriju
        Cache::put('email', $email);
        Cache::put('ime', $ime);
        Cache::put('prezime', $prezime);
        Cache::put('korisnickoIme', $korisnickoIme);

        //provjera zauzetosti email i korisnickog imena
        $zauzetoKorisnickoIme = DB::select("SELECT * FROM korisnik WHERE korisnickoIme = ?", [$korisnickoIme]);
        $zauzetEmail = DB::select("SELECT * FROM korisnik WHERE email = ?", [$email]);


        if($zauzetoKorisnickoIme){ //provjera korisnickog imena
            Cache::put('poruka','Korisničko ime je zauzeto!');
            Cache::put('korisnickoIme', '');
            return redirect()->to('http://localhost/Lumen/public/registracija');
            exit();
        }
        if($zauzetEmail){ //provjera e-maila
            Cache::put('poruka','E-mail je zauzet!');
            Cache::put('email', '');
            return redirect()->to('http://localhost/Lumen/public/registracija');
            exit();
        }
        if($lozinka != $potvrdi_lozinku){ //provjera lozinka
            Cache::put('poruka','Lozinke se ne podudaraju!');
            return redirect()->to('http://localhost/Lumen/public/registracija');
            exit();
        }

        $lozinkaEnc = Hash::make($lozinka); //hashiranje lozinke

        //spremanje podataka u bazu podataka
        DB::insert('insert into korisnik (korisnickoIme, email, lozinka, lozinkaEnc, ime, prezime, uloga_id)' .
            'values(?,?,?,?,?,?,?)', [$korisnickoIme, $email, $lozinka, $lozinkaEnc, $ime, $prezime, 1]);
        Cache::flush();
        return redirect()->to('http://localhost/prijava'); //preusmjeravanje na login
    }

}
