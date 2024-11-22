<?php

function feltoltes($keszletenDB, $id) {
    $db = new mysqli('localhost', 'root', '', 'vizsgaremek_takaritas');

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $query = $db->prepare("UPDATE `eszkoz` SET `keszletenDB`=? WHERE Id=?");

    $query->bind_param("ii", $keszletenDB, $id);

    try {
        $query->execute();
        if ($query->affected_rows > 0) {
            echo "Sikeres művelet!";
            return true;
        } else {
            echo "Nem történt változás!";
        }
    } catch (Exception $e) {
        echo "Hiba: " . $e->getMessage();
    }
}

?>