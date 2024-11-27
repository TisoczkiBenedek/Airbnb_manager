<?php

function eszkozokBetoltese(){
    $eszkozLekeres_sql = "SELECT eszkoz.Id, eszkoz.nev, eszkoz.kiszereles AS kisze, eszkoz.keszletenDB AS darab FROM eszkoz";
    $eszkozLekeres = adatokLekerese($eszkozLekeres_sql);
    if(is_array($eszkozLekeres)){
        foreach ($eszkozLekeres as $adat) {
            echo "<div id='eszkoz'>
                    <h1>".htmlspecialchars($adat['nev'])."</h1><br>
                    <p>Kiszerelés: ".htmlspecialchars($adat['kisze'])."<br>
                    Jelenleg készleten: 
                    <input type='number' class='db' value='".htmlspecialchars($adat['darab'])."' name='darab_".htmlspecialchars($adat['Id'])."'>
                    <input type='hidden' value='".htmlspecialchars($adat['Id'])."' name='eszkoz_".htmlspecialchars($adat['Id'])."'>
                    </p>
                </div>
                <hr>";    
        }
    } else {
        echo "<h1>Hiba! Nem találtunk eszközöket!</h1>";
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST") { 
    if(isset($_POST["frissites"])) { 
        foreach ($_POST as $kulcs => $ertek) { 
            if(strpos($kulcs, 'darab_') === 0) { 
                $Id = str_replace('darab_', '', $kulcs); 
                $keszletenDB = $ertek; 
                feltoltes($keszletenDB, $Id); 
            } 
        } 
    } 
    
    if (isset($_POST['eszkozFelvitele'])) { 
        $eszkozNev = $_POST['eszkozNev'] ?? ''; 
        $eszkozKiszereles = $_POST['eszkozKiszereles'] ?? ''; 
        $eszkozDarabszam = $_POST['eszkozDarabszam'] ?? ''; 

        if (!empty($eszkozNev) && !empty($eszkozKiszereles) && !empty($eszkozDarabszam)) { 
            $muvelet = "INSERT INTO eszkoz (nev, kiszereles, keszletenDB) VALUES ('$eszkozNev', '$eszkozKiszereles', $eszkozDarabszam)"; 
            adatokLekerese($muvelet); 
            echo "<p>Az új eszköz sikeresen felvitelre került.</p>"; 
        } else { 
            echo "<p>Hiba! Minden mezőt ki kell tölteni.</p>"; 
        } 
    }
}
