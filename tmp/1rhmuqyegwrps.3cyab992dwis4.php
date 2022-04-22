<link rel="stylesheet" href="/files/cssNovi/login.css" type="text/css"/>
<div class="center">
    <h1>Prijava</h1>
    <form method="post" action="/prijava/login">
        <div class="signup_link" style="color:red">
            <?= ($COOKIE['poruka'])."
" ?>
        </div>
        <div class="txt_field">
            <input type="text" required name="korisnickoIme">
            <span></span>
            <label>Korisničko ime</label>
        </div>
        <div class="txt_field">
            <input type="password" required name="lozinka">
            <span></span>
            <label>Lozinka</label>
        </div>
        <div class="pass">Zaboravljena lozinka?</div>
        <input type="submit" value="Login">
        <div class="signup_link">
            Niste registrirani? <a href="http://localhost/Lumen/public/registracija">Registracija</a>
        </div>
    </form>
</div>