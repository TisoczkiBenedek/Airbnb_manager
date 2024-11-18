<?php

include "./sql_fuggvenyek.php";


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
            echo "<h3 id='nev'>" . htmlspecialchars($felhasznaloNev) . "</h3> <img id='profilKep' src='../kepek/" . htmlspecialchars($profilKep) . "' alt='profilkép'>";
        } else { 
            echo "Hiba a profil betöltésekor!"; 
        } 
    } else { 
        echo "Nincs bejelentkezve!"; 
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
                <a href=#>Felhasználók</a>
                <a href=#>Lakások</a>
                <p id="jelenlegiOldal">Eszközök</p>
            </div>

            <!--<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>-->

            <h2 id="cim">főoldal</h2>
            <div class="navbar-right" id="profilAdatok">
                <?php profilBetoltese(); ?> 
            </div>          
        </nav>
    </div>

    <script src="adminOldal.js"></script>


    
</body>
</html>
