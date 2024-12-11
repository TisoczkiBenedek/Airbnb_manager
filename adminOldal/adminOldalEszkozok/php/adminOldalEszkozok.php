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
        <!-- Frissítés űrlap -->
        <form method="post" action="">
            <input type='hidden' name='frissites' value='1'>
            <button type='submit' id='frissites' name='frissites'>Raktár frissítése</button>
            <button type='button' id='eszkozFelvitele'>Eszköz felvitele</button>
            <hr> 
            <?php eszkozokBetoltese(); ?> 
        </form>
        
        <!-- Feltöltés gomb és modal -->
        <div id="felvitel" class="modal"  style="display: none"> 
            <div class="modal-content"> 
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" id="close" class="bi bi-x" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                </svg>
                <h2 id="adatfelvitel">Adatbevitel</h2> 
                <form id="popupForm" method="post" action="ujEszkoz.php"> 
                    <label for="nev">Név:</label>
                    <input type="text" id="nev" name="nev" required><br> 
                    <label for="kiszereles">Kiszerelés:</label> 
                    <input type="text" id="kiszereles" name="kiszereles" required><br> 
                    <label for="keszletenDB">Darab:</label> 
                    <input type="number" id="keszletenDB" name="keszletenDB" required><br>
                    <button type="submit" name="feltoltes" id="feltoltes">Feltöltés</button> 
                </form> 
            </div> 
        </div> 
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
