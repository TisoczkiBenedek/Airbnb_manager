<?php
session_start();
include "./sql_fuggvenyek.php";
include "./eszkozFrissites.php";

function profilBetoltese(){
    if(isset($_SESSION['emailcim'])){
        $emailcim = $_SESSION['emailcim'];
        $muvelet = "SELECT felhasznalo.profilkep, felhasznalo.vezetekNev, felhasznalo.keresztNev FROM felhasznalo WHERE felhasznalo.emailcim = '{$emailcim}'";
        $eredmeny = adatokLekerese($muvelet);

        if (is_array($eredmeny) && !empty($eredmeny)) {
            $profilKep = $eredmeny[0]['profilkep'];
            $vezetekNev = $eredmeny[0]['vezetekNev'];
            $keresztNev = $eredmeny[0]['keresztNev'];
            $felhasznaloNev = $vezetekNev . " " . $keresztNev;
            echo "<h5 id='nev'>" . htmlspecialchars($felhasznaloNev) . "</h5> 
                  <img id='profilKep' src='../kepek/" . htmlspecialchars($profilKep) . "' alt='profilkép'>";
        } else {
            echo "<h5 id='nev'>Hiba a profil betöltésekor!</h5>";
        }
    } else {
        echo "<h5 id='nev'>Nincs bejelentkezve!</h5>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["frissites"])) {
        foreach ($_POST as $kulcs => $ertek) {
            if (strpos($kulcs, 'darab_') === 0) {
                $Id = str_replace('darab_', '', $kulcs);
                $keszletenDB = (int)$ertek; // Biztosítsd, hogy szám legyen
                if ($keszletenDB >= 0) {  // Készleten szám nem lehet negatív
                    $uzenet = frissites($keszletenDB, $Id);
                } else {
                    echo "<script>alert('A készlet szám nem lehet negatív!');</script>";
                }
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["feltoltes"])) {  // Check if the form has been submitted
        // Get data from the form
        $nev = isset($_POST['nev']) ? $_POST['nev'] : '';  // Name of the item
        $kiszereles = isset($_POST['kiszereles']) ? $_POST['kiszereles'] : '';  // Packaging
        $keszletenDB = isset($_POST['keszletenDB']) ? (int)$_POST['keszletenDB'] : 0;  // Stock quantity

        // Validate the data
        if (!empty($nev) && !empty($kiszereles) && $keszletenDB >= 0) {
            // Call the function to insert the data into the database
            $uzenet = feltoltes($nev, $kiszereles, $keszletenDB);
            if ($uzenet) {
                echo "<script>alert('Sikeres adatfeltöltés!');</script>";
            } else {
                echo "<script>alert('Hiba történt az adatfeltöltés során!');</script>";
            }
        } else {
            echo "<script>alert('Kérjük, töltsd ki a kötelező mezőket, és győződj meg róla, hogy a készlet szám pozitív!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="../js/adminOldalEszkozok.js" defer></script>
    <link rel="stylesheet" href="../css/adminOldal.css">
    <title>Admin főoldal</title>
</head>
<body>
    <div class="pos-f-t">
        <div class="navbar-right">
            <div class="collapse" id="navbarToggleExternalContent">
                <div class="bg-dark p-4">
                    <h4 class="text-white">Collapsed content</h4>
                    <span class="text-muted">Toggleable via the navbar brand.</span>
                </div>
            </div>
        </div>

        <nav class="navbar navbar-dark bg-dark">
            <div id="oldalLinkek">
                <a href="#">Felhasználók</a>
                <a href="#">Lakások</a>
                <p id="jelenlegiOldal">Eszközök</p>
            </div>

            <h2 id="cim">főoldal</h2>
            <div class="navbar-right" id="profilAdatok">
               
            </div>
        </nav>
    </div>
    <div id="eszkozTarolo">
        <form method="post">
            <!--<input type='hidden' name='frissites' value='1'>-->
            <input type='submit' value='Raktár frissítése' id='frissites' name='frissites'>
            <input type='submit' value='Eszköz felvitele' id='eszkozFelvitele'>
            <div id="felvitel" class="modal"> 
                <div class="modal-content"> 
                    <span class="close">&times;</span> 
                    <h2>Adatbevitel</h2> 
                    <form id="popupForm" method="post" action="ujEszkoz.php"> 
                        <label for="nev">Név:</label>
                        <input type="text" id="nev" name="nev" required><br><br>
                        <label for="kiszereles">Kiszerelés:</label>
                        <input type="text" id="kiszereles" name="kiszereles" required><br><br>
                        <label for="db">Darab:</label>
                        <input type="number" id="db" name="db" required><br><br>
                        <button type="submit" name="feltoltes" id="feltoltes" >Feltöltés</button>
                    </form>
                </div>
            </div>
            <hr>
            <?php  
                include "./eszkozBetoltes.php";
                eszkozokBetoltese();
            ?>
        </form>
    </div>
</body>
</html>