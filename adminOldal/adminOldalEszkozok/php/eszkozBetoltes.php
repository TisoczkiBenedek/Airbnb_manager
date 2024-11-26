<?php
function eszkozokBetoltese(){
        $eszkozLekeres_sql = "SELECT eszkoz.Id, eszkoz.nev, eszkoz.kiszereles AS kisze, eszkoz.keszletenDB AS darab FROM eszkoz";
        $eszkozLekeres = adatokLekerese($eszkozLekeres_sql);
        if(is_array($eszkozLekeres)){
            foreach ($eszkozLekeres as $adat) {
                echo "<div id='eszkoz'>
                        <h1>".$adat['nev']."</h1><br>
                        <p>Kiszerelés: ".$adat['kisze']."<br>
                        Jelenleg készleten: 
                        <input type='number' class='db' value='" . $adat['darab'] . "' name=darab_'" . $adat['Id'] . "'>
                        <input type='hidden' value='" . $adat['Id'] . "' name='eszkoz_" . $adat['Id'] . "'>
                        </p>
                    </div>
                    <hr>";    
            }
        }else{
            echo `<h1>Hiba! Nem találtunk eszközöket!</h1>`;
    }
}