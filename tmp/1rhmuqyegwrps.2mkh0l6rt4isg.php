<link rel="stylesheet" href="/files/cssNovi/login.css" type="text/css"/>
<div class="center">
    <h2 style="text-align: center;">Moj profil</h2>
    <form method="post" action="/profil/azuriraj">
        <div class="signup_link"style="text-align: left; font-weight: bolder;">Korisničko ime
        <input style="font-weight: normal" value="<?= ($SESSION['korisnik']) ?>" disabled></div>
        <div class="signup_link"style="text-align: left; font-weight: bolder;">E-mail
            <input style="font-weight: normal" value="<?= ($SESSION['email']) ?>" disabled></div>
        <div class="signup_link"style="text-align: left; font-weight: bolder;">Ime
            <input style="font-weight: normal" name="ime" value="<?= ($SESSION['ime']) ?>"></div>
        <div class="signup_link"style="text-align: left; font-weight: bolder;">Prezime
            <input style="font-weight: normal" name="prezime" value="<?= ($SESSION['prezime']) ?>"></div>
        <div class="signup_link"style="text-align: left; font-weight: bolder;">Nova lozinka
            <input type = "password" name="lozinka" style="font-weight: normal" placeholder="Nova lozinka"></div>

        <?php if ($SESSION['uloga'] > 1): ?> <!-- Provjera je li uloga >=2 odnosno je li moderator il admin -->
            
                <div class="signup_link"style="text-align: left; font-weight: bolder;">Uloga:
                    <input style="font-weight: normal" value="<?= ($nazivUloge) ?>" disabled></div>
                <?php if ($SESSION['uloga'] == 2): ?>
                    
                <div class="signup_link"style="text-align: left; font-weight: bolder;">Moji sportovi:
                    <input style="font-weight: normal" value="<?= ($sportoviModerator) ?>" disabled></div>
                    
                <?php endif; ?>
            
        <?php endif; ?>
        <input type="submit" value="Spremi">
        <div class="signup_link">
        </div>
    </form>
</div>