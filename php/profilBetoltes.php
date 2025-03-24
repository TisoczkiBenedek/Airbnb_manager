<?php
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

        } else {
            echo "<h5 id='felhNev'>Hiba a profil betöltésekor!</h5>";
        }
    } else {
        echo "<h5 id='felhNev'>Nincs bejelentkezve!</h5>";
    }
}

$action = $_GET['action'] ?? null;
?>
