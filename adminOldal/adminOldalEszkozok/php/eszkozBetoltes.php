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
?>
