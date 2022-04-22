<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \Cache;
use App\Korisnik;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija</title>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="/files/cssNovi/sidebarStyle.css" type="text/css"/>
    <link rel="stylesheet" href="/files/cssNovi/style.css" type="text/css"/>
    <link rel="stylesheet" href="/files/cssNovi/table.css" type="text/css"/>
    <link rel="stylesheet" href="/files/cssNovi/login.css" type="text/css"/>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>

    <!--Biblioteka z ikonicama -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body id="boja">
    <!-- Glavna navigacija za neprijavljenog korisnika -->
    <nav class="navbar">
        <div class="inner-width">
            <a class="logo" href="/"></a>
            <button class="menu-toggler">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="navbar-menu">
                <a href="http://localhost/"><i class="fa fa-fw fa-hand-paper"></i>Početna</a>
                <a href="http://localhost/rezultati"><i class="fa fa-fw fa-futbol"></i>Rezlutati</a>
                <a href="http://localhost/prijava"><i class="fa fa-fw fa-sign-in"></i>Prijava</a>
            </div>
        </div>
    </nav>
    <div class="center">
        <h1>Registracija</h1>
        <form method="post" id="registracija" action="http://localhost/Lumen/public/registriraj">
            <div class="txt_field">
                <input name="email" autocomplete="off" type="e-mail" required value=<?php echo Cache::get('email');?>>
                <span></span>
                <label>E-mail</label>
            </div>
            <div class="txt_field">
                <input name="korisnickoIme" type="text" required value=<?php echo Cache::get('korisnickoIme')?>>
                <span></span>
                <label>Korisničko ime</label>
            </div>
            <div class="txt_field">
                <input name="lozinka" type="password" required>
                <span></span>
                <label>Lozinka</label>
            </div>
            <div class="txt_field">
                <input name="potvrdi_lozinku" type="password" required>
                <span></span>
                <label>Ponovi lozinku</label>
            </div>
            <div class="txt_field">
                <input name="ime" type="text" autocomplete="off" required value=<?php echo Cache::get('ime')?>>
                <span></span>
                <label>Ime</label>
            </div>
            <div class="txt_field">
                <input name="prezime" type="text" autocomplete="off" required value=<?php echo Cache::get('prezime')?>>
                <span></span>
                <label>Prezime</label>
            </div>
            <input type="submit" value="Registracija">
            <div class="signup_link" style="color:red">
                <?php echo Cache::get('poruka')?>
            </div>
        </form>
    </div>
</body>
</html>

