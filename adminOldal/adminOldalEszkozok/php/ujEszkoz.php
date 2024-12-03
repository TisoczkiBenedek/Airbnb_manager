<?php
function feltoltes($nev, $kiszereles, $keszletenDB) {
    $db = new mysqli('localhost', 'root', '', 'vizsgaremek_takaritas');

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $query = $db->prepare("INSERT INTO `eszkoz`(`nev`, `kiszereles`, `keszletenDB`) VALUES ('?','?','?')");
    $query->bind_param("sss", $keszletenDB, $id);

    if ($query->execute()) {
        if ($query->affected_rows > 0) {
            //echo "<script>alert('Sikeres művelet');</script>";
            return true; 
        } else { 
            //echo "<script>alert('Hiba történt a művelet során');</script>";
        }
    } else {
       // echo "<script>alert('Hiba: " . $query->error . "');</script>";
    }

    $query->close();
    $db->close();
    return false;
}