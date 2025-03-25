<?php
// eszkozBetoltes.php
/*if (!function_exists('eszkozokBetoltese')) {
    function eszkozokBetoltese() {
        $db = new mysqli('localhost', 'root', '', 'vizsgaremek_takaritas');
        if ($db->connect_error) {
            die("<div class='alert alert-danger' style='margin-top:60px;'>Adatbázis hiba: ".$db->connect_error."</div>");
        }

        $result = $db->query("SELECT eszkoz.Id, eszkoz.nev, eszkoz.kiszereles AS kisze, eszkoz.keszletenDB AS darab FROM eszkoz");
        
        if ($result && $result->num_rows > 0) {
            while ($adat = $result->fetch_assoc()) {
                echo "<div class='eszkoz-item mb-3 p-3 border rounded'>
                        <h5>".htmlspecialchars($adat['nev'])."</h5>
                        <p>Kiszerelés: ".htmlspecialchars($adat['kisze'])."</p>
                        <div class='input-group mb-2'>
                            <span class='input-group-text'>Darabszám:</span>
                            <input type='number' class='form-control' value='".htmlspecialchars($adat['darab'])."' name='darab_".htmlspecialchars($adat['Id'])."' min='0'>
                        </div>
                        <div class='form-check'>
                            <input class='form-check-input' type='checkbox' name='torlesCheckbox_".htmlspecialchars($adat['Id'])."' id='t_".htmlspecialchars($adat['Id'])."'>
                            <label class='form-check-label' for='t_".htmlspecialchars($adat['Id'])."'>Törlés</label>
                        </div>
                    </div>";
            }
        } else {
            echo "<div class='alert alert-info' style='margin-top:60px;'>Nincsenek eszközök a rendszerben.</div>";
        }
        
        $db->close();
    }
}

eszkozokBetoltese();*/
?>