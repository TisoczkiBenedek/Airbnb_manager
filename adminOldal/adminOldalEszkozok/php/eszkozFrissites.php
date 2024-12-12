<?php
function frissites($keszletenDB, $Id) {
    $db = new mysqli('localhost', 'root', '', 'vizsgaremek_takaritas');

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $query = $db->prepare("UPDATE `eszkoz` SET `keszletenDB`=? WHERE Id=?");
    if ($query === false) {
        echo "<script>console.log('Prepare failed: " . $db->error . "');</script>";
        return false;
    }

    $bind = $query->bind_param("ii", $keszletenDB, $Id);
    if ($bind === false) {
        echo "<script>console.log('Bind param failed: " . $query->error . "');</script>";
        return false;
    }

    $execute = $query->execute();
    if ($execute === false) {
        echo "<script>console.log('Execute failed: " . $query->error . "');</script>";
        return false;
    }

    if ($query->affected_rows > 0) {
        return true;
    } else {
        echo "<script>console.log('No rows affected');</script>";
        return false;
    }

    $query->close();
    $db->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["frissites"])) {
    $adatokVannak = false;
    $uzenetek = [];
    
    foreach ($_POST as $kulcs => $ertek) {
        if (strpos($kulcs, 'darab_') === 0) {
            $adatokVannak = true;
            $Id = str_replace('darab_', '', $kulcs);
            $keszletenDB = (int)$ertek;
            if ($keszletenDB >= 0) {
                $uzenet = frissites($keszletenDB, $Id);
                if ($uzenet) {
                    $uzenetek[] = "Sikeres frissítés ID: $Id!";
                } else {
                    $uzenetek[] = "Hiba történt a frissítés során ID: $Id!";
                }
            } else {
                $uzenetek[] = "A készlet szám nem lehet negatív ID: $Id!";
            }
        }
    }
}
