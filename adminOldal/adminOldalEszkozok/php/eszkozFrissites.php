<?php
/*
function frissites($keszletenDB, $Id) {
    $db = new mysqli('localhost', 'root', '', 'vizsgaremek_takaritas');

    var_dump("Minden jó!");

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
    var_dump("teszt1");
    foreach ($_POST as $kulcs => $ertek) {
        var_dump($_POST);
        echo "<script>console.log('Kulcs: " . $kulcs . " Érték: " . $ertek . "');</script>"; // Console log a kulcs és érték megjelenítésére
        var_dump("teszt2");
        if (strpos($kulcs, 'darab_') === 0) { // Ellenőrizzük, hogy a kulcsok valóban 'darab_' prefix-szel kezdődnek-e
            var_dump("teszt3");
            $Id = str_replace('darab_', '', $kulcs);
            $keszletenDB = (int)$ertek;
            if ($keszletenDB >= 0) {
                $uzenet = frissites($keszletenDB, $Id);
                if ($uzenet) {
                    echo "<script>alert('Sikeres frissítés!');</script>";
                } else {
                    echo "<script>alert('Hiba történt a frissítés során!');</script>";
                }
            } else {
                echo "<script>alert('A készlet szám nem lehet negatív!');</script>";
            }
        }
    }
}
*/

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
    $adatokVannak = false; // Változó, ami jelezni fogja, ha van adat, amit frissíteni kell
    foreach ($_POST as $kulcs => $ertek) {
        echo "<script>console.log('Kulcs: " . $kulcs . " Érték: " . $ertek . "');</script>";
        if (strpos($kulcs, 'darab_') === 0) {
            $adatokVannak = true; // Van frissítendő adat
            $Id = str_replace('darab_', '', $kulcs);
            $keszletenDB = (int)$ertek;
            if ($keszletenDB >= 0) {
                $uzenet = frissites($keszletenDB, $Id);
                if ($uzenet) {
                    echo "<script>alert('Sikeres frissítés!');</script>";
                } else {
                    echo "<script>alert('Hiba történt a frissítés során!');</script>";
                }
            } else {
                echo "<script>alert('A készlet szám nem lehet negatív!');</script>";
            }
        }
    }
    if (!$adatokVannak) {
        echo "<script>alert('Nincs adat a frissítéshez!');</script>";
    }
}
