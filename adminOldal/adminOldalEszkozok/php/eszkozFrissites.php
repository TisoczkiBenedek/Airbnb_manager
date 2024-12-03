<?php
function frissites($keszletenDB, $Id) {
    $db = new mysqli('localhost', 'root', '', 'vizsgaremek_takaritas');

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // SQL Injection elkerülése
    $query = $db->prepare("UPDATE `eszkoz` SET `keszletenDB`=? WHERE Id=?");
    $query->bind_param("ii", $keszletenDB, $Id); // Paraméterek kötése

    if ($query->execute()) {
        if ($query->affected_rows > 0) {
            return true; 
        } else { 
            //echo "<script>alert('Nincs változás az adatbázisban.');</script>";
        }
    } else {
        //echo "<script>alert('Hiba történt a művelet során: " . $query->error . "');</script>";
    }

    $query->close();
    $db->close();
    return false;
}