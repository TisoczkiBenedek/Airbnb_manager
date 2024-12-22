<?php
session_start();
include "./sql_fuggvenyek.php";
include "./profilBetoltes.php";
include "./ujEszkoz.php";
include "./eszkozBetoltes.php";
include "./eszkozFrissites.php";
include "./eszkozTorles.php";
?>

<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-g7p/KuP4VKh5U5i6V6xLx4t3HkB/qIu9VsXxI8deKLV29r8FTOtrRVEovIbGRIMJ" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/adminOldalEszkozok.js" defer></script>
    <link rel="stylesheet" href="../css/adminOldal.css">
    <title>Admin főoldal</title>
</head>
<body>
    <!--
    <div class="pos-f-t"> 
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark"> 
            <div class="container-fluid"> 
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> 
                    <span class="navbar-toggler-icon"></span> 
                </button> 
                <a class="navbar-brand" href="#">Eszközök</a>
                <div class="collapse navbar-collapse" id="navbarNav"> 
                    <ul class="navbar-nav me-auto"> 
                        <li class="nav-item"> <a class="nav-link active" aria-current="page" href="#">Eszközök</a> </li>
                        <li class="nav-item"> <a class="nav-link" href="#">Lakások</a> </li> 
                        <li class="nav-item"> <a class="nav-link" href="#">Felhasználók</a> </li>
                    </ul> 
                </div> 
                <div class="navbar-right" id="profilAdatok"> 
                    <?php profilBetoltese(); ?> 
                </div> 
            </div>
        </nav> 
    </div>
-->
    <div class="pos-f-t"> 
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark"> 
            <div class="container-fluid"> 
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> 
                    <span class="navbar-toggler-icon"></span> 
                </button> 
                <a class="navbar-brand" href="#">Eszközök</a> 
                <div class="collapse navbar-collapse" id="navbarNav"> 
                    <ul class="navbar-nav me-auto"> 
                        <li class="nav-item"> <a class="nav-link active" aria-current="page" href="#">Eszközök</a> </li> 
                        <li class="nav-item"> <a class="nav-link" href="#">Lakások</a> </li> 
                        <li class="nav-item"> <a class="nav-link" href="#">Felhasználók</a> </li> 
                    </ul> 
                </div> 
                <div class="navbar-right" id="profilAdatok"> 
                    <?php profilBetoltese(); ?> 
                </div> 
            </div> 
        </nav> 
    </div>

    <div id="eszkozTarolo">
        <!-- Frissítés űrlap -->
        <form method="post" action="">
            <input type='hidden' name='frissites' value='1'>
            <button type='submit' id='frissites' name='frissites'>Raktár frissítése</button>
            <button type='button' id='eszkozFelvitele'>Eszköz felvitele</button>
            <button type='submit' name='torles'>Kijelöltek törlése</button>
            <hr>
            <?php eszkozokBetoltese(); ?>
        </form>
        
        <!-- Feltöltés gomb és modal -->
        <div id="felvitel" class="modal" style="display: none">
            <div class="modal-content">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" id="close" class="bi bi-x" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708"/>
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
