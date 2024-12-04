<?php
session_start();
include "./sql_fuggvenyek.php";
include "./profilBetoltes.php";
include "./ujEszkoz.php";
include "./eszkozBetoltes.php";
include "./eszkozFrissites.php";
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
                <?php profilBetoltese(); ?>
            </div>
        </nav>
    </div>
    <div id="eszkozTarolo">
        <form method="post" action="">
            <input type='submit' value='Raktár frissítése' id='frissites' name='frissites'>
            <button type='button' id='eszkozFelvitele'>Eszköz felvitele</button>
            <div id="felvitel" class="modal"> 
                <div class="modal-content"> 
                    <span class="close">&times;</span> 
                    <h2>Adatbevitel</h2> 
                    <form id="popupForm" method="post" action="feltoltes.php"> 
                        <label for="nev">Név:</label>
                        <input type="text" id="nev" name="nev" required><br> 
                        <label for="kiszereles">Kiszerelés:</label> 
                        <input type="text" id="kiszereles" name="kiszereles" required><br> 
                        <label for="db">Darab:</label> 
                        <input type="number" id="db" name="keszletenDB" required><br>
                        <button type="submit" name="feltoltes" id="feltoltes">Feltöltés</button> 
                    </form> 
                </div> 
            </div> 
            <hr> 
            <?php eszkozokBetoltese(); ?> 
            </form> 
            <div> 
                <?php 
                    if (isset($uzenet)) { 
                        echo "<p>$uzenet</p>"; 
                    } 
                ?> 
            </div> 
        </div> 
    </body> 
</html>